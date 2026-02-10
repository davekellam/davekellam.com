<?php
/**
 * Import Goodreads RSS data
 */

namespace DaveKellam\Core\GoodreadsImport;

use DateTime;
use WP_CLI;
use WP_Error;

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'goodreads', __NAMESPACE__ . '\\Goodreads_Command' );
}

class Goodreads_Importer {
	public function import_file( string $path, int $author_id, bool $update_existing, bool $skip_covers ) {
		$real_path = realpath( $path );
		if ( ! $real_path || ! is_readable( $real_path ) ) {
			return new WP_Error( 'goodreads_import_missing_file', 'File not found or not readable.' );
		}

		if ( ! post_type_exists( 'book' ) ) {
			return new WP_Error( 'goodreads_import_missing_post_type', 'Post type "book" is not registered.' );
		}

		if ( ! taxonomy_exists( 'book_shelf' ) ) {
			return new WP_Error( 'goodreads_import_missing_taxonomy', 'Taxonomy "book_shelf" is not registered.' );
		}

		if ( $author_id <= 0 ) {
			return new WP_Error( 'goodreads_import_invalid_author', 'Invalid author ID.' );
		}

		libxml_use_internal_errors( true );
		$xml = simplexml_load_file( $real_path );
		if ( ! $xml || ! isset( $xml->channel->item ) ) {
			return new WP_Error( 'goodreads_import_parse_error', 'Failed to parse XML or no items found.' );
		}

		$created = 0;
		$updated = 0;
		$skipped = 0;
		$total = 0;

		foreach ( $xml->channel->item as $item ) {
			$total++;
			$book_id = trim( (string) $item->book_id );
			$review_url = trim( (string) $item->link );
			$existing_post_id = $this->find_existing_post_id( $book_id, $review_url );

			if ( $existing_post_id && ! $update_existing ) {
				$skipped++;
				continue;
			}

			$post_id = $existing_post_id ? $existing_post_id : 0;

			$post_data = $this->build_post_data( $item, $author_id );
			if ( empty( $post_data['post_title'] ) ) {
				$skipped++;
				continue;
			}

			if ( $post_id ) {
				$post_data['ID'] = $post_id;
				$post_id = wp_update_post( $post_data, true );
			} else {
				$post_id = wp_insert_post( $post_data, true );
			}

			if ( is_wp_error( $post_id ) ) {
				$skipped++;
				continue;
			}

			$this->update_meta( $post_id, $item );
			$this->update_shelves( $post_id, $item );

			if ( ! $skip_covers ) {
				$this->maybe_set_cover( $post_id, $item );
			}

			if ( $existing_post_id ) {
				$updated++;
			} else {
				$created++;
			}
		}

		return [
			'created' => $created,
			'updated' => $updated,
			'skipped' => $skipped,
			'total'   => $total,
		];
	}

	private function build_post_data( \SimpleXMLElement $item, int $author_id ) : array {
		$title = trim( (string) $item->title );
		$content = trim( (string) $item->book_description );

		if ( empty( $content ) ) {
			$content = '';
		}

		$read_at = $this->parse_date( (string) $item->user_read_at );
		$post_date = $read_at ? $read_at->format( 'Y-m-d H:i:s' ) : current_time( 'mysql' );
		$post_date_gmt = $read_at ? get_gmt_from_date( $post_date ) : current_time( 'mysql', 1 );

		return [
			'post_type'         => 'book',
			'post_status'       => 'publish',
			'post_title'        => $title,
			'post_content'      => wp_kses_post( $content ),
			'post_author'       => $author_id,
			'post_date'         => $post_date,
			'post_date_gmt'     => $post_date_gmt,
			'post_modified'     => current_time( 'mysql' ),
			'post_modified_gmt' => current_time( 'mysql', 1 ),
		];
	}

	private function update_meta( int $post_id, \SimpleXMLElement $item ) : void {
		$book_id = trim( (string) $item->book_id );
		$review_url = trim( (string) $item->link );
		$guid = trim( (string) $item->guid );
		$author_name = trim( (string) $item->author_name );
		$isbn = trim( (string) $item->isbn );
		$user_rating = (int) $item->user_rating;
		$average_rating = (string) $item->average_rating;
		$read_at = $this->parse_date( (string) $item->user_read_at );
		$date_added = $this->parse_date( (string) $item->user_date_added );
		$published_year = trim( (string) $item->book_published );
		$num_pages = '';

		if ( isset( $item->book->num_pages ) ) {
			$num_pages = trim( (string) $item->book->num_pages );
		}

		$cover_url = $this->pick_cover_url( $item );

		$this->update_meta_value( $post_id, '_goodreads_book_id', $book_id );
		$this->update_meta_value( $post_id, '_goodreads_review_url', $review_url );
		$this->update_meta_value( $post_id, '_goodreads_review_guid', $guid );
		$this->update_meta_value( $post_id, '_goodreads_author', $author_name );
		$this->update_meta_value( $post_id, '_goodreads_isbn', $isbn );
		$this->update_meta_value( $post_id, '_goodreads_user_rating', $user_rating );
		$this->update_meta_value( $post_id, '_goodreads_average_rating', $average_rating );
		$this->update_meta_value( $post_id, '_goodreads_read_date', $read_at ? $read_at->format( 'Y-m-d' ) : '' );
		$this->update_meta_value( $post_id, '_goodreads_date_added', $date_added ? $date_added->format( 'Y-m-d' ) : '' );
		$this->update_meta_value( $post_id, '_goodreads_published_year', $published_year );
		$this->update_meta_value( $post_id, '_goodreads_num_pages', $num_pages );
		$this->update_meta_value( $post_id, '_goodreads_cover_url', $cover_url );
		$this->update_meta_value( $post_id, '_goodreads_user_shelves', trim( (string) $item->user_shelves ) );
	}

	private function update_meta_value( int $post_id, string $meta_key, $value ) : void {
		if ( $value === '' || $value === null ) {
			delete_post_meta( $post_id, $meta_key );
			return;
		}

		update_post_meta( $post_id, $meta_key, $value );
	}

	private function update_shelves( int $post_id, \SimpleXMLElement $item ) : void {
		$raw_shelves = trim( (string) $item->user_shelves );
		if ( $raw_shelves === '' ) {
			return;
		}

		$shelves = array_filter( array_map( 'trim', explode( ',', $raw_shelves ) ) );
		if ( empty( $shelves ) ) {
			return;
		}

		wp_set_post_terms( $post_id, $shelves, 'book_shelf', false );
	}

	private function pick_cover_url( \SimpleXMLElement $item ) : string {
		$preferred = [
			'book_large_image_url',
			'book_medium_image_url',
			'book_image_url',
			'book_small_image_url',
		];

		foreach ( $preferred as $field ) {
			if ( isset( $item->{$field} ) ) {
				$url = trim( (string) $item->{$field} );
				if ( $url !== '' ) {
					return $url;
				}
			}
		}

		return '';
	}

	private function maybe_set_cover( int $post_id, \SimpleXMLElement $item ) : void {
		$cover_url = $this->pick_cover_url( $item );
		if ( $cover_url === '' ) {
			return;
		}

		if ( has_post_thumbnail( $post_id ) ) {
			return;
		}

		$attachment_id = $this->sideload_image( $cover_url, $post_id );
		if ( $attachment_id ) {
			set_post_thumbnail( $post_id, $attachment_id );
			update_post_meta( $post_id, '_goodreads_cover_attachment_id', $attachment_id );
		}
	}

	private function sideload_image( string $url, int $post_id ) : int {
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$tmp = download_url( $url );
		if ( is_wp_error( $tmp ) ) {
			return 0;
		}

		$filename = basename( parse_url( $url, PHP_URL_PATH ) );
		$file_array = [
			'name'     => $filename ? $filename : 'goodreads-cover.jpg',
			'tmp_name' => $tmp,
		];

		$attachment_id = media_handle_sideload( $file_array, $post_id );
		if ( is_wp_error( $attachment_id ) ) {
			@unlink( $tmp );
			return 0;
		}

		return (int) $attachment_id;
	}

	private function parse_date( string $value ) : ?DateTime {
		$value = trim( $value );
		if ( $value === '' ) {
			return null;
		}

		$timestamp = strtotime( $value );
		if ( ! $timestamp ) {
			return null;
		}

		$date = new DateTime();
		$date->setTimestamp( $timestamp );
		return $date;
	}

	private function find_existing_post_id( string $book_id, string $review_url ) : int {
		if ( $book_id !== '' ) {
			$existing = get_posts( [
				'post_type'      => 'book',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_goodreads_book_id',
				'meta_value'     => $book_id,
			] );

			if ( ! empty( $existing ) ) {
				return (int) $existing[0];
			}
		}

		if ( $review_url !== '' ) {
			$existing = get_posts( [
				'post_type'      => 'book',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_goodreads_review_url',
				'meta_value'     => $review_url,
			] );

			if ( ! empty( $existing ) ) {
				return (int) $existing[0];
			}
		}

		return 0;
	}
}

class Goodreads_Command {
	/**
	 * Import a Goodreads RSS XML file into the book post type.
	 *
	 * ## OPTIONS
	 *
	 * --file=<path>
	 * : Path to the Goodreads RSS XML file
	 *
	 * [--author=<id>]
	 * : Author ID for imported posts (default: 1)
	 *
	 * [--update-existing]
	 * : Update posts that already exist
	 *
	 * [--skip-covers]
	 * : Skip downloading cover images
	 *
	 * ## EXAMPLES
	 *
	 *     wp goodreads import --file=/path/to/goodreads.xml --author=1
	 *
	 * @when after_wp_load
	 */
	public function import( array $args, array $assoc_args ) : void {
		$path = $assoc_args['file'] ?? '';
		$author_id = isset( $assoc_args['author'] ) ? (int) $assoc_args['author'] : 1;
		$update_existing = isset( $assoc_args['update-existing'] );
		$skip_covers = isset( $assoc_args['skip-covers'] );

		if ( empty( $path ) ) {
			WP_CLI::error( 'Missing --file argument.' );
		}


		$importer = new Goodreads_Importer();
		$result = $importer->import_file( $path, $author_id, $update_existing, $skip_covers );
		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}

		$created = $result['created'] ?? 0;
		$updated = $result['updated'] ?? 0;
		$skipped = $result['skipped'] ?? 0;
		$total = $result['total'] ?? 0;

		WP_CLI::success( "Import complete. Total: {$total}, Created: {$created}, Updated: {$updated}, Skipped: {$skipped}." );
	}
}
