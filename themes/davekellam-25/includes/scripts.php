<?php
/**
 * Core script files
 *
 * @package davekellam\theme
 */

namespace DaveKellam\Theme\DK25\Scripts;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts' );

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	wp_enqueue_script(
		'frontend',
		DAVEKELLAM_TEMPLATE_URL . '/dist/js/frontend.min.js',
		[],
		DAVEKELLAM_VERSION,
		true
	);

	wp_localize_script(
		'frontend',
		'mainScript',
		[
			'assetPath' => get_template_directory_uri() . '/dist/',
		]
	);
}
