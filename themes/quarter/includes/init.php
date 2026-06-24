<?php
/**
 * Core theme setup and hooks.
 *
 * @package Quarter
 */

namespace Quarter\Theme\Init;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\theme_setup' );
add_filter( 'get_the_archive_title_prefix', '__return_false' );

/**
 * Sets up theme defaults and registers WordPress feature support.
 */
function theme_setup(): void {
	load_theme_textdomain( 'quarter', QUARTER_PATH . 'languages' );

	add_theme_support( 'align-wide' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );

	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		]
	);

	remove_theme_support( 'core-block-patterns' );

	// Conditionally load block assets only when a block is present on the page.
	add_filter( 'should_load_separate_core_block_assets', '__return_true' );

	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus(
		[
			'primary' => esc_html__( 'Primary Menu', 'quarter' ),
			'footer'  => esc_html__( 'Footer Menu', 'quarter' ),
		]
	);
}
