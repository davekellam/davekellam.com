<?php
/**
 * Setup css files
 *
 * @package davekellam\theme
 */

namespace DaveKellam\Theme\DK25\Styles;

// Load Frontend styles
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\load_styles' );

// Load login styles
// add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\load_login_styles' );


/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function load_styles() {
	wp_enqueue_style(
		'frontend',
		DAVEKELLAM_TEMPLATE_URL . '/dist/css/frontend.min.css',
		[],
		DAVEKELLAM_VERSION,
		'screen'
	);
}

/**
 * Add a custom stylesheet to alter the appearance of the login page
 */
function load_login_styles() {
	wp_enqueue_style(
		'login-style',
		get_stylesheet_directory_uri() . '/dist/css/login.min.css',
		false,
		DAVEKELLAM_VERSION
	);
}
