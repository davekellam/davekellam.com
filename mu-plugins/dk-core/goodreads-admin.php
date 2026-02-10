<?php
/**
 * Goodreads import admin screen
 */

namespace DaveKellam\Core\GoodreadsImport;

add_action( 'admin_menu', __NAMESPACE__ . '\\register_goodreads_admin' );
add_filter( 'upload_mimes', __NAMESPACE__ . '\\allow_xml_uploads' );

function allow_xml_uploads( array $mimes ) : array {
	$mimes['xml'] = 'text/xml';
	return $mimes;
}

function register_goodreads_admin() : void {
	add_management_page(
		'Goodreads Import',
		'Goodreads Import',
		'manage_options',
		'goodreads-import',
		__NAMESPACE__ . '\\render_goodreads_admin'
	);
}

function render_goodreads_admin() : void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice = '';
	$error = '';

	if ( isset( $_POST['goodreads_import_submit'] ) ) {
		check_admin_referer( 'goodreads_import' );

		if ( empty( $_FILES['goodreads_xml']['tmp_name'] ) ) {
			$error = 'Please choose an XML file to import.';
		} else {
			$author_id = isset( $_POST['goodreads_author_id'] ) ? (int) $_POST['goodreads_author_id'] : get_current_user_id();
			$update_existing = isset( $_POST['goodreads_update_existing'] );
			$skip_covers = isset( $_POST['goodreads_skip_covers'] );

			$upload = wp_handle_upload( $_FILES['goodreads_xml'], [ 'test_form' => false ] );

			if ( isset( $upload['error'] ) ) {
				$error = $upload['error'];
			} else {
				$importer = new Goodreads_Importer();
				$result = $importer->import_file( $upload['file'], $author_id, $update_existing, $skip_covers );

				if ( is_wp_error( $result ) ) {
					$error = $result->get_error_message();
				} else {
					$created = $result['created'] ?? 0;
					$updated = $result['updated'] ?? 0;
					$skipped = $result['skipped'] ?? 0;
					$total = $result['total'] ?? 0;
					$notice = "Import complete. Total: {$total}, Created: {$created}, Updated: {$updated}, Skipped: {$skipped}.";
				}

				if ( ! empty( $upload['file'] ) && file_exists( $upload['file'] ) ) {
					@unlink( $upload['file'] );
				}
			}
		}
	}

	$author_id = get_current_user_id();
	?>
	<div class="wrap">
		<h1>Goodreads Import</h1>
		<?php if ( $notice ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>
		<?php if ( $error ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $error ); ?></p></div>
		<?php endif; ?>
		<form method="post" enctype="multipart/form-data">
			<?php wp_nonce_field( 'goodreads_import' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="goodreads_xml">Goodreads XML</label></th>
					<td><input type="file" id="goodreads_xml" name="goodreads_xml" accept=".xml" required></td>
				</tr>
				<tr>
					<th scope="row"><label for="goodreads_author_id">Author ID</label></th>
					<td><input type="number" id="goodreads_author_id" name="goodreads_author_id" value="<?php echo esc_attr( (string) $author_id ); ?>" min="1"></td>
				</tr>
				<tr>
					<th scope="row">Options</th>
					<td>
						<label>
							<input type="checkbox" name="goodreads_update_existing" value="1">
							Update existing posts
						</label>
						<br>
						<label>
							<input type="checkbox" name="goodreads_skip_covers" value="1">
							Skip cover downloads
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Import Goodreads Data', 'primary', 'goodreads_import_submit' ); ?>
		</form>
	</div>
	<?php
}
