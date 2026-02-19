<?php
/**
 * Define custom taxonomies
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\Taxonomies;

add_action( 'init', __NAMESPACE__ . '\\register_taxonomies' );

function register_taxonomies() {
	register_taxonomy(
		'birdsite_hashtags',
		'birdsite_tweet',
		[
			'label'        => 'Hashtags',
			'public'       => true,
			'show_ui'      => true,
			'show_in_rest' => true,
			'hierarchical' => false,
		]
	);
}
