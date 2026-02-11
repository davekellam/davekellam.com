<?php
/**
 * Book meta box
 */

namespace DaveKellam\Core\BookMeta;

add_action( 'add_meta_boxes', __NAMESPACE__ . '\\register_book_meta_box' );
add_action( 'save_post_book', __NAMESPACE__ . '\\save_book_meta', 10, 2 );

/**
 * Register the book meta box for the Book post type
 *
 * @return void
 */
function register_book_meta_box(): void {
	add_meta_box(
		'book_meta',
		'Book Details',
		__NAMESPACE__ . '\\render_book_meta_box',
		'book',
		'normal',
		'high'
	);
}

/**
 * Render the book meta box
 *
 * @param \WP_Post $post id
 * @return void
 */
function render_book_meta_box( \WP_Post $post ): void {
	$fields = get_book_meta_fields();
	wp_nonce_field( 'book_meta', 'book_meta_nonce' );
	?>
	<table class="form-table" role="presentation">
		<tbody>
		<?php
		foreach ( $fields as $field ) :
			$key   = $field['key'];
			$value = get_post_meta( $post->ID, $key, true );
			$type  = $field['type'] ?? 'text';
			?>
			<tr>
				<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
				<td>
					<input
						type="<?php echo esc_attr( $type ); ?>"
						id="<?php echo esc_attr( $key ); ?>"
						name="<?php echo esc_attr( $key ); ?>"
						value="<?php echo esc_attr( (string) $value ); ?>"
						class="regular-text"
					>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php
}

/**
 * Save book meta data when the post is saved
 *
 * @param integer  $post_id ID of the post being saved
 * @param \WP_Post $post Post object being saved
 * @return void
 */
function save_book_meta( int $post_id, \WP_Post $post ): void { // phpcs:ignore
	if ( ! isset( $_POST['book_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['book_meta_nonce'], 'book_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = get_book_meta_fields();
	foreach ( $fields as $field ) {
		$key = $field['key'];
		if ( ! array_key_exists( $key, $_POST ) ) {
			continue;
		}

		$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		if ( $value === '' ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $value );
	}
}

/**
 * Retrieve book metadata
 *
 * @return array
 */
function get_book_meta_fields(): array {
	return [
		[
			'key'   => 'book_review_url',
			'label' => 'Review URL',
		],
		[
			'key'   => 'book_author',
			'label' => 'Author',
		],
		[
			'key'   => 'book_isbn',
			'label' => 'ISBN',
		],
		[
			'key'   => 'book_user_rating',
			'label' => 'User Rating',
		],
		[
			'key'   => 'book_read_date',
			'label' => 'Read Date',
			'type'  => 'date',
		],
		[
			'key'   => 'book_date_added',
			'label' => 'Date Added',
			'type'  => 'date',
		],
		[
			'key'   => 'book_published_year',
			'label' => 'Published Year',
		],
		[
			'key'   => 'book_num_pages',
			'label' => 'Pages',
		],
		[
			'key'   => 'book_cover_url',
			'label' => 'Cover URL',
		],
	];
}
