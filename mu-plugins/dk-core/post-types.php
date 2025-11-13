<?php
/**
 * Setup post types
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\PostTypes;

// Not alphabetical, defines order in left-hand column
add_action( 'init', __NAMESPACE__ . '\\albums' );

/**
 * Register the News post type
 */
function albums() {
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
