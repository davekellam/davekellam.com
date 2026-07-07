<?php
// phpcs:ignoreFile
/**
 * Import Goodreads RSS data
 */

namespace DaveKellam\Core\GoodreadsImport;

use DateTime;
use WP_Error;

// Initialize settings and admin page
add_action( 'admin_menu', __NAMESPACE__ . '\\register_admin_page' );
add_action( 'admin_init', __NAMESPACE__ . '\\register_settings' );
add_action( 'goodreads_sync_event', __NAMESPACE__ . '\\run_scheduled_sync' );

// Schedule cron on plugin load (mu-plugin always active)
schedule_cron();

class Goodreads_Importer {
	public function import_from_url( string $url, int $author_id, bool $skip_covers ) {
		if ( ! post_type_exists( 'book' ) ) {
			return new WP_Error( 'goodreads_import_missing_post_type', 'Post type "book" is not registered.' );
		}

		if ( $author_id <= 0 ) {
			return new WP_Error( 'goodreads_import_invalid_author', 'Invalid author ID.' );
		}

		// Fetch the RSS feed
		$response = wp_remote_get( $url, [ 'timeout' => 15 ] );
		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'goodreads_import_fetch_error', 'Failed to fetch RSS feed: ' . $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );
		if ( empty( $body ) ) {
			return new WP_Error( 'goodreads_import_empty_response', 'RSS feed returned empty response.' );
		}

		libxml_use_internal_errors( true );
		$xml = simplexml_load_string( $body );
		if ( ! $xml || ! isset( $xml->channel->item ) ) {
			return new WP_Error( 'goodreads_import_parse_error', 'Failed to parse RSS feed or no items found.' );
		}

		$created = 0;
		$skipped = 0;
		$total   = 0;

		foreach ( $xml->channel->item as $item ) {
			++$total;
			$post_data = $this->build_post_data( $item, $author_id );
			if ( empty( $post_data['post_title'] ) ) {
				++$skipped;
				continue;
			}

			// Check for duplicates by title and author
			if ( $this->book_exists( $post_data['post_title'], $post_data['post_author'] ) ) {
				++$skipped;
				continue;
			}

			$post_id = wp_insert_post( $post_data, true );

			if ( is_wp_error( $post_id ) ) {
				++$skipped;
				continue;
			}

			$this->update_meta( $post_id, $item );

			if ( ! $skip_covers ) {
				$this->maybe_set_cover( $post_id, $item );
			}

			++$created;
		}

		return [
			'created' => $created,
			'skipped' => $skipped,
			'total'   => $total,
		];
	}

	private function book_exists( string $title, int $author_id ): bool {
		$existing = get_posts(
			[
				'post_type'      => 'book',
				'post_status'    => 'any',
				'posts_per_page' => 1000,
				'fields'         => 'ids',
			]
		);

		// Double-check exact title match
		foreach ( $existing as $post_id ) {
			if ( $title === get_the_title( $post_id ) ) {
				return true;
			}
		}

		return false;
	}

	private function build_post_data( \SimpleXMLElement $item, int $author_id ): array {
		$title          = trim( (string) $item->title );
		$content        = trim( (string) $item->book_description );
		$read_at        = $this->parse_date( (string) $item->user_read_at );
		$date_added     = $this->parse_date( (string) $item->user_date_added );
		$effective_date = $read_at ?: $date_added;
		$post_date      = $effective_date ? $effective_date->format( 'Y-m-d H:i:s' ) : current_time( 'mysql' );
		$post_date_gmt  = $read_at ? get_gmt_from_date( $post_date ) : current_time( 'mysql', 1 );

		return [
			'post_type'         => 'book',
			'post_status'       => 'publish',
			'post_title'        => $title,
			'post_content'      => $content !== '' ? wp_kses_post( $content ) : '',
			'post_author'       => $author_id,
			'post_date'         => $post_date,
			'post_date_gmt'     => $post_date_gmt,
			'post_modified'     => current_time( 'mysql' ),
			'post_modified_gmt' => current_time( 'mysql', 1 ),
		];
	}

	private function update_meta( int $post_id, \SimpleXMLElement $item ): void {
		$review_url     = trim( (string) $item->link );
		$author_name    = trim( (string) $item->author_name );
		$isbn           = trim( (string) $item->isbn );
		$user_rating    = (int) $item->user_rating;
		$read_at        = $this->parse_date( (string) $item->user_read_at );
		$date_added     = $this->parse_date( (string) $item->user_date_added );
		$published_year = trim( (string) $item->book_published );
		$num_pages      = '';

		if ( isset( $item->book->num_pages ) ) {
			$num_pages = trim( (string) $item->book->num_pages );
		}

		$cover_url = $this->pick_cover_url( $item );

		$this->update_meta_value( $post_id, 'book_review_url', $review_url );
		$this->update_meta_value( $post_id, 'book_author', $author_name );
		$this->update_meta_value( $post_id, 'book_isbn', $isbn );
		$this->update_meta_value( $post_id, 'book_user_rating', $user_rating );
		$this->update_meta_value( $post_id, 'book_read_date', $read_at ? $read_at->format( 'Y-m-d' ) : '' );
		$this->update_meta_value( $post_id, 'book_date_added', $date_added ? $date_added->format( 'Y-m-d' ) : '' );
		$this->update_meta_value( $post_id, 'book_published_year', $published_year );
		$this->update_meta_value( $post_id, 'book_num_pages', $num_pages );
		$this->update_meta_value( $post_id, 'book_cover_url', $cover_url );
	}

	private function update_meta_value( int $post_id, string $meta_key, $value ): void {
		if ( $value === '' || $value === null ) {
			delete_post_meta( $post_id, $meta_key );
			return;
		}

		update_post_meta( $post_id, $meta_key, $value );
	}

	private function pick_cover_url( \SimpleXMLElement $item ): string {
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

	private function maybe_set_cover( int $post_id, \SimpleXMLElement $item ): void {
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
		}
	}

	private function sideload_image( string $url, int $post_id ): int {
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$tmp = download_url( $url );
		if ( is_wp_error( $tmp ) ) {
			return 0;
		}

		$filename   = basename( wp_parse_url( $url, PHP_URL_PATH ) );
		$file_array = [
			'name'     => $filename ? $filename : 'goodreads-cover.jpg',
			'tmp_name' => $tmp,
		];

		$attachment_id = media_handle_sideload( $file_array, $post_id );
		if ( is_wp_error( $attachment_id ) ) {
			wp_delete_file( $tmp );
			return 0;
		}

		return (int) $attachment_id;
	}

	private function parse_date( string $value ): ?DateTime {
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
}

/**
 * Register admin menu page
 */
function register_admin_page(): void {
	add_management_page(
		'Goodreads Importer',
		'Goodreads',
		'manage_options',
		'goodreads-importer',
		__NAMESPACE__ . '\\render_admin_page'
	);
}

/**
 * Register settings
 */
function register_settings(): void {
	register_setting(
		'goodreads_importer',
		'goodreads_feed_url',
		[
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => false,
		]
	);

	register_setting(
		'goodreads_importer',
		'goodreads_sync_interval',
		[
			'type'              => 'string',
			'default'           => 'daily',
			'sanitize_callback' => [ __NAMESPACE__ . '\\Goodreads_Settings', 'sanitize_interval' ],
			'show_in_rest'      => false,
		]
	);

	register_setting(
		'goodreads_importer',
		'goodreads_author_id',
		[
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'show_in_rest'      => false,
		]
	);

	register_setting(
		'goodreads_importer',
		'goodreads_skip_covers',
		[
			'type'              => 'boolean',
			'default'           => false,
			'sanitize_callback' => fn( $value ) => (bool) $value,
			'show_in_rest'      => false,
		]
	);

	add_settings_section(
		'goodreads_importer_section',
		'Goodreads Importer Settings',
		__NAMESPACE__ . '\\render_settings_section',
		'goodreads_importer'
	);

	add_settings_field(
		'goodreads_feed_url_field',
		'Feed URL',
		__NAMESPACE__ . '\\render_feed_url_field',
		'goodreads_importer',
		'goodreads_importer_section'
	);

	add_settings_field(
		'goodreads_sync_interval_field',
		'Sync Interval',
		__NAMESPACE__ . '\\render_sync_interval_field',
		'goodreads_importer',
		'goodreads_importer_section'
	);

	add_settings_field(
		'goodreads_author_id_field',
		'Author ID',
		__NAMESPACE__ . '\\render_author_id_field',
		'goodreads_importer',
		'goodreads_importer_section'
	);

	add_settings_field(
		'goodreads_skip_covers_field',
		'Skip Covers',
		__NAMESPACE__ . '\\render_skip_covers_field',
		'goodreads_importer',
		'goodreads_importer_section'
	);

	// Reschedule cron when settings are saved
	if ( isset( $_POST['option_page'] ) && $_POST['option_page'] === 'goodreads_importer' ) {
		add_action( 'update_option_goodreads_sync_interval', __NAMESPACE__ . '\\reschedule_cron' );
	}
}

/**
 * Render admin page
 */
function render_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}

	$feed_url   = get_option( 'goodreads_feed_url' );
	$interval   = get_option( 'goodreads_sync_interval', 'daily' );
	$last_sync  = get_option( 'goodreads_last_sync', 0 );
	$last_count = get_option( 'goodreads_last_sync_count', [] );
	$sync_error = get_option( 'goodreads_sync_error', '' );

	$last_sync_text = $last_sync ? wp_date( 'Y-m-d H:i:s', $last_sync ) : 'Never';
	?>
	<div class="wrap">
		<h1>Goodreads Importer</h1>

		<div style="max-width: 800px;">
			<!-- Status Section -->
			<div style="background: #f1f1f1; padding: 20px; margin-bottom: 30px; border-radius: 5px;">
				<h2 style="margin-top: 0;">Status</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Last Sync</th>
						<td><?php echo esc_html( $last_sync_text ); ?></td>
					</tr>
				<?php if ( ! empty( $last_count ) ) : ?>
				<tr>
					<th scope="row">Last Import Stats</th>
					<td>
						Created: <strong><?php echo absint( $last_count['created'] ?? 0 ); ?></strong>,
						Skipped: <strong><?php echo absint( $last_count['skipped'] ?? 0 ); ?></strong>,
						Total: <strong><?php echo absint( $last_count['total'] ?? 0 ); ?></strong>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( $sync_error ) : ?>
				<tr>
					<th scope="row">Last Error</th>
					<td style="color: #dc3545;">
						<code><?php echo esc_html( $sync_error ); ?></code>
					</td>
				</tr>
				<?php endif; ?>
			</table>
		</div>

		<!-- Settings Section -->
		<form method="post" action="options.php" style="background: white; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
			<?php settings_fields( 'goodreads_importer' ); ?>
			<?php do_settings_sections( 'goodreads_importer' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
</div>

<style>
	.goodreads-input {
		width: 100%;
		max-width: 500px;
		padding: 8px;
		border: 1px solid #ddd;
		border-radius: 3px;
	}
</style>
	<?php
}

function render_settings_section(): void {
	echo '<p>Configure your Goodreads RSS feed and sync settings.</p>';
}

function render_feed_url_field(): void {
	$url = get_option( 'goodreads_feed_url' );
	echo '<input type="url" name="goodreads_feed_url" value="' . esc_attr( $url ) . '" class="goodreads-input" placeholder="https://www.goodreads.com/review/list_rss/12345" />';
	echo '<p class="description">Your Goodreads RSS feed URL (found in account settings)</p>';
}

function render_sync_interval_field(): void {
	$interval = get_option( 'goodreads_sync_interval', 'daily' );
	?>
	<select name="goodreads_sync_interval" id="goodreads_sync_interval">
		<option value="daily" <?php selected( $interval, 'daily' ); ?>>Daily</option>
		<option value="weekly" <?php selected( $interval, 'weekly' ); ?>>Weekly</option>
		<option value="monthly" <?php selected( $interval, 'monthly' ); ?>>Monthly</option>
	</select>
	<p class="description">How often to automatically sync your Goodreads library</p>
	<?php
}

function render_author_id_field(): void {
	$author_id = get_option( 'goodreads_author_id', 1 );
	echo '<input type="number" name="goodreads_author_id" value="' . absint( $author_id ) . '" min="1" style="width: 100px;" />';
	echo '<p class="description">WordPress user ID to assign imported books to</p>';
}

function render_skip_covers_field(): void {
	$skip = get_option( 'goodreads_skip_covers', false );
	echo '<input type="checkbox" name="goodreads_skip_covers" value="1" ' . checked( $skip, true, false ) . ' />';
	echo '<span>Skip downloading cover images</span>';
}

/**
 * Run scheduled sync
 */
function run_scheduled_sync(): void {
	$url       = get_option( 'goodreads_feed_url' );
	$author_id = get_option( 'goodreads_author_id', 1 );
	$skip_covers = get_option( 'goodreads_skip_covers', false );

	if ( empty( $url ) ) {
		update_option( 'goodreads_sync_error', 'Feed URL not configured' );
		return;
	}

	$importer = new Goodreads_Importer();
	$result   = $importer->import_from_url( $url, $author_id, $skip_covers );

	if ( is_wp_error( $result ) ) {
		update_option( 'goodreads_sync_error', $result->get_error_message() );
		return;
	}

	// Update last sync time
	update_option( 'goodreads_last_sync', time() );
	update_option( 'goodreads_last_sync_count', $result );
	delete_option( 'goodreads_sync_error' );
}

/**
 * Schedule cron events
 */
function schedule_cron(): void {
	if ( ! wp_next_scheduled( 'goodreads_sync_event' ) ) {
		$interval = get_option( 'goodreads_sync_interval', 'daily' );
		$recurrence = in_array( $interval, [ 'daily', 'weekly', 'monthly' ], true ) ? $interval : 'daily';
		wp_schedule_event( time(), $recurrence, 'goodreads_sync_event' );
	}
}

/**
 * Reschedule cron based on interval setting
 */
function reschedule_cron(): void {
	$interval = get_option( 'goodreads_sync_interval', 'daily' );

	// Remove existing schedule
	$timestamp = wp_next_scheduled( 'goodreads_sync_event' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'goodreads_sync_event' );
	}

	// Schedule new one
	$recurrence = in_array( $interval, [ 'daily', 'weekly', 'monthly' ], true ) ? $interval : 'daily';
	wp_schedule_event( time(), $recurrence, 'goodreads_sync_event' );
}

/**
 * Settings helper class
 */
class Goodreads_Settings {
	public static function sanitize_interval( $value ): string {
		$allowed = [ 'daily', 'weekly', 'monthly' ];
		return in_array( $value, $allowed, true ) ? $value : 'daily';
	}
}
