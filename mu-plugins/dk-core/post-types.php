<?php
/**
 * Setup post types
 */

namespace DaveKellam\Core\PostTypes;

add_action( 'init', __NAMESPACE__ . '\\albums' );
add_action( 'init', __NAMESPACE__ . '\\books' );
add_action( 'init', __NAMESPACE__ . '\\tweets' );

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

/**
 * Register the Book post type
 */
function books() {
	// Bail if extended CPTs is not available (i.e. no composer install)
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		return;
	}

	register_extended_post_type(
		'book',
		[
			'menu_icon'    => 'dashicons-book',
			'supports'     => [ 'title', 'thumbnail' , 'editor' ],
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'block_editor' => false,
		],
		[
			'singular' => 'Book',
			'plural'   => 'Books',
		]
	);
}

/**
 * 
 *
 * @return void
 */
function tweets() {
	// Bail if extended CPTs is not available (i.e. no composer install)
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		return;
	}

	register_extended_post_type(
		'birdsite_tweet',
		[
			'menu_icon'    => 'dashicons-megaphone',
			'supports'     => [ 'title', 'editor' ],
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'block_editor' => false,
		],
		[
			'singular' => 'Tweet',
			'plural'   => 'Tweets',
			'slug'     => 'tweets',
		]
	);
}
