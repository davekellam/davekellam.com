<?php
/**
 * Dashboard widget for quick changelog entries
 */

namespace DaveKellam\Core\ChangelogDashboard;

add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\register_widget' );
add_action( 'admin_post_changelog_quick_add', __NAMESPACE__ . '\\handle_submission' );
add_action( 'admin_notices', __NAMESPACE__ . '\\admin_notice' );

function register_widget(): void {
	wp_add_dashboard_widget(
		'changelog_quick_add',
		esc_html__( 'Quick Changelog', 'quarter' ),
		__NAMESPACE__ . '\\render_widget'
	);
}

function render_widget(): void {
	?>
	<form name="changelog-quick-add" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<label for="changelog_title">
			<?php esc_html_e( 'Title', 'quarter' ); ?>
		</label>
		<input type="text" name="changelog_title" id="changelog_title" class="widefat" required />

		<label for="changelog_content">
			<?php esc_html_e( 'Description', 'quarter' ); ?>
		</label>
		<textarea name="changelog_content" id="changelog_content" class="widefat" rows="3" style="margin-bottom:0.5em;"></textarea>

		<input type="hidden" name="action" value="changelog_quick_add" />
		<?php wp_nonce_field( 'changelog_quick_add', 'changelog_nonce' ); ?>
		<?php submit_button( esc_html__( 'Add Changelog Entry', 'quarter' ), 'primary', 'changelog_submit', false ); ?>
	</form>
	<?php
}

function handle_submission(): void {
	if ( ! isset( $_POST['changelog_nonce'] ) || ! wp_verify_nonce( $_POST['changelog_nonce'], 'changelog_quick_add' ) ) {
		wp_die( esc_html__( 'Invalid nonce.', 'quarter' ) );
	}

	if ( ! current_user_can( 'publish_posts' ) ) {
		wp_die( esc_html__( 'Insufficient permissions.', 'quarter' ) );
	}

	$title   = isset( $_POST['changelog_title'] ) ? sanitize_text_field( wp_unslash( $_POST['changelog_title'] ) ) : '';
	$content = isset( $_POST['changelog_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['changelog_content'] ) ) : '';

	if ( empty( $title ) ) {
		wp_die( esc_html__( 'Title is required.', 'quarter' ) );
	}

	$post_id = wp_insert_post(
		[
			'post_type'    => 'changelog',
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
		]
	);

	if ( is_wp_error( $post_id ) ) {
		wp_die( esc_html__( 'Failed to create changelog entry.', 'quarter' ) );
	}

	$redirect = add_query_arg(
		[
			'changelog_added' => '1',
		],
		wp_get_referer()
	);

	wp_safe_redirect( esc_url_raw( $redirect ) );
	exit;
}

function admin_notice(): void {
	if ( ! isset( $_GET['changelog_added'] ) || '1' !== $_GET['changelog_added'] ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'dashboard' !== $screen->id ) {
		return;
	}

	printf(
		'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
		esc_html__( 'Changelog entry added.', 'quarter' )
	);
}
