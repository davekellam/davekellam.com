<?php
/**
 * Registration functions for theme blocks
 */

namespace Quarter\Theme\Blocks;

add_action( 'init', __NAMESPACE__ . '\\register_bookshelf_block' );

/**
 * Register the Bookshelf block using WordPress 7.0 PHP-only registration.
 *
 * @return void
 */
function register_bookshelf_block(): void {
	register_block_type(
		'quarter/bookshelf',
		[
			'title'           => __( 'Bookshelf', 'quarter' ),
			'description'     => __( 'Display books you have read or are currently reading.', 'quarter' ),
			'icon'            => 'book-alt',
			'category'        => 'widgets',
			'attributes'      => [
				'status'      => [
					'label'   => __( 'Show', 'quarter' ),
					'type'    => 'string',
					'enum'    => [ 'read', 'reading' ],
					'default' => 'read',
				],
				'title'       => [
					'label'   => __( 'Title', 'quarter' ),
					'type'    => 'string',
					'default' => '',
				],
				'count'       => [
					'label'   => __( 'Number of books', 'quarter' ),
					'type'    => 'integer',
					'default' => 200,
				],
                'groupByYear' => [
					'label'   => __( 'Group by year', 'quarter' ),
					'type'    => 'boolean',
					'default' => true,
				],
			],
			'supports'        => [
				'autoRegister' => true,
				'align'        => true,
			],
			'render_callback' => __NAMESPACE__ . '\\render_bookshelf',
		]
	);
}