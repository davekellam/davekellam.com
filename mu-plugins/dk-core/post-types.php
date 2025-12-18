<?php
/**
 * Setup post types
 */

namespace DaveKellam\Core\PostTypes;

add_action( 'init', __NAMESPACE__ . '\\albums' );

// enable excerpts for pages
add_post_type_support( 'page', 'excerpt' );

/**
 * Register the News post type
 */
function albums() {
	// Bail if extended CPTs is not available (i.e. no composer install)
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		return;
	}

	register_extended_post_type(
		'record-album',
		[
			'menu_icon' => 'dashicons-album',
			'supports'  => [ 'title', 'editor', 'thumbnail' ],
			'public'    => false,
			'show_ui'   => true,
		],
		[
			'singular' => 'Album',
			'plural'   => 'Albums',
		]
	);
}
