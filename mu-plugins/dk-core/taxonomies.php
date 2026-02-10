<?php
/**
 * Define custom taxonomies
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\Taxonomies;

add_action( 'init', __NAMESPACE__ . '\\book_shelves' );

/**
 * Register the Book Shelf taxonomy
 */
function book_shelves() {
	register_taxonomy(
		'book_shelf',
		[ 'book' ],
		[
			'labels'       => [
				'name'          => 'Book Shelves',
				'singular_name' => 'Book Shelf',
			],
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'rewrite'      => [ 'slug' => 'book-shelf' ],
		]
	);
}
