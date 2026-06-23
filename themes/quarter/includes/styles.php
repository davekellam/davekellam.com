<?php
/**
 * Enqueue theme stylesheets.
 *
 * @package Quarter
 */

namespace Quarter\Theme\Styles;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
add_action( 'admin_init', __NAMESPACE__ . '\\add_editor_styles' );

/**
 * Enqueue front-end stylesheets.
 */
function enqueue_styles(): void {
	$css_file = QUARTER_PATH . 'dist/css/style.css';
	$version  = file_exists( $css_file )
		? gmdate( 'ymdHi', filemtime( $css_file ) )
		: QUARTER_VERSION;

	wp_enqueue_style(
		'quarter',
		QUARTER_URL . '/dist/css/style.css',
		[],
		$version,
		'screen'
	);
}

/**
 * Add a stylesheet to customize the appearance of the editor
 *
 * @todo Consider using enqueue_block_editor_assets instead of add_editor_styles or different approach
 */
function add_editor_styles() {
	$stylesheet = './dist/css/style.css';

	add_editor_style( $stylesheet );
}
