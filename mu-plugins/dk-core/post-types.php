<?php
/**
 * Setup post types
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\PostTypes;

// Not alphabetical, defines order in left-hand column
// add_action( 'init', __NAMESPACE__ . '\\albums' );

/**
 * Register the News post type
 */
function albums() {
	register_extended_post_type(
		'post',
		[
			'menu_icon' => 'dashicons-megaphone',
		],
		[
			'singular' => 'News',
			'plural'   => 'News',
			'slug'     => 'news',

		]
	);
}
