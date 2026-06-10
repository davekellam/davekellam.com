<?php
/**
 * Enqueue theme stylesheets.
 *
 * @package Quarter
 */

namespace Quarter\Theme\Styles;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_styles' );

/**
 * Enqueue front-end stylesheets.
 */
function enqueue_styles(): void {
	$css_file = QUARTER_PATH . 'dist/css/style.css';
	$version  = file_exists( $css_file )
		? date( 'ymdHi', filemtime( $css_file ) )
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
 * Enqueue editor-specific stylesheet so block editor matches the front end.
 */
function enqueue_editor_styles(): void {
	$css_file = QUARTER_PATH . 'dist/css/style.css';
	$version  = file_exists( $css_file )
		? date( 'ymdHi', filemtime( $css_file ) )
		: QUARTER_VERSION;

	wp_enqueue_style(
		'quarter-editor',
		QUARTER_URL . '/dist/css/style.css',
		[],
		$version,
		'screen'
	);
}
