<?php
/**
 * Setup post types
 */

namespace DaveKellam\Core\PostTypes;

add_action( 'init', __NAMESPACE__ . '\\albums' );
add_action( 'init', __NAMESPACE__ . '\\books' );
add_action( 'init', __NAMESPACE__ . '\\tweets' );
add_action( 'init', __NAMESPACE__ . '\\changelog' );
add_action( 'init', __NAMESPACE__ . '\\videos' );

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
			'public'       => false,
			'show_ui'      => true,
			'block_editor' => false,
			'admin_cols'   => array(
				// A featured image column:
				'featured_image' => array(
					'title'          => 'Cover',
					'featured_image' => 'thumbnail',
					'width'          => 80,
				),
				'title',
				// A meta field column:
				'read'           => array(
					'title'       => 'Read',
					'meta_key'    => 'book_read_date',
					'date_format' => 'Y-m-d',
					'default'     => 'DESC',
				),
			),
		],
		[
			'singular' => 'Book',
			'plural'   => 'Books',
		]
	);
}

/**
 * Register the Changelog post type
 */
function changelog() {
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		return;
	}

	register_extended_post_type(
		'changelog',
		[
			'menu_icon'    => 'dashicons-editor-ul',
			'labels'       => [
				'menu_name' => 'Changelog',
			],
			'supports'     => [ 'title', 'editor' ],
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'block_editor' => false,
			'has_archive'  => false,
			'admin_cols'   => [
				'title',
				'the_content' => array(
					'title'      => 'Content',
					'post_field' => 'post_content',
				),
				'published'   => array(
					'title'       => 'Published',
					'post_field'  => 'post_date',
					'date_format' => 'Y.m.d',
					'default'     => 'DESC',
				),
			],
		],
		[
			'singular' => 'Entry',
			'plural'   => 'Entries',
		]
	);
}

/**
 * Register the Video post type
 */
function videos() {
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		return;
	}

	register_extended_post_type(
		'video',
		[
			'menu_icon'    => 'dashicons-video-alt3',
			'supports'     => [ 'title', 'editor', 'thumbnail' ],
			'public'       => true,
			'show_ui'      => true,
			'show_in_rest' => true,
			'has_archive'  => false,
		],
		[
			'singular' => 'Video',
			'plural'   => 'Videos',
		]
	);
}

/**
 * Register the Tweet post type
 *
 * @return void
 */
function tweets() {
	// Bail if extended CPTs is not available (i.e. no composer install)
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		return;
	}

	register_extended_post_type(
		'birdsite_tweet', // original post type name
		[
			'menu_icon'    => 'dashicons-megaphone',
			'supports'     => [ 'title', 'editor' ],
			'public'       => true,
			'show_ui'      => true,
			'show_in_rest' => true,
			'block_editor' => false,
			'rewrite'      => [
				'permastruct' => 'tweets/%year%/%monthnum%/%birdsite_tweet%/',
			],
		],
		[
			'singular' => 'Tweet',
			'plural'   => 'Tweets',
		]
	);
}
