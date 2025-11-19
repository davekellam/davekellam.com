<?php
/**
 * Setup css files
 *
 * @package davekellam\theme
 */

namespace DaveKellam\Theme\DK25\Styles;

// Load Frontend styles
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\load_styles' );

// Load Print styles
add_action( 'wp_print_styles', __NAMESPACE__ . '\\load_print_styles' );

// Load scripts and styles for admin areas:
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\load_admin_styles', 20 );
add_action( 'admin_init', __NAMESPACE__ . '\\add_editor_styles' );

// Load login styles
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\load_login_styles' );


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
		filemtime( DAVEKELLAM_PATH . 'dist/css/frontend.min.css' ),
		'screen'
	);
}

/**
 * Enqueue styles for print
 *
 * @return void
 */
function load_print_styles() {
	wp_enqueue_style(
		'print',
		DAVEKELLAM_TEMPLATE_URL . '/dist/css/print.min.css',
		[],
		filemtime( DAVEKELLAM_PATH . 'dist/css/print.min.css' ),
		'print'
	);
}


/**
 * Register and load admin CSS
 */
function load_admin_styles() {
	// Register Styles
	wp_register_style(
		'admin-style',
		get_template_directory_uri() . '/dist/css/admin.min.css',
		false,
		DAVEKELLAM_VERSION
	);

	// Enqueue Styles
	wp_enqueue_style( 'admin-style' );
}

/**
 * Add a stylesheet to customize the appearance of the editor
 */
function add_editor_styles() {
	$stylesheet = './dist/css/editor.min.css';

	add_editor_style( $stylesheet );
}

/**
 * Add a custom stylesheet to alter the appearance of the login page
 */
function load_login_styles() {
	wp_enqueue_style(
		'login-style',
		get_stylesheet_directory_uri() . '/dist/css/login.min.css',
		false,
		filemtime( DAVEKELLAM_PATH . 'dist/css/login.min.css' )
	);
}
