<?php
/**
 * Core setup, site hooks and filters.
 */

namespace DaveKellam\Theme\DK25\Init;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\theme_setup' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup() {
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );

	add_theme_support(
		'html5',
		[
			'search-form',
			'gallery',
		]
	);

	remove_theme_support( 'core-block-patterns' );

	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus(
		[
			'primary' => esc_html__( 'Primary Menu', 'davekellam' ),
			'footer'  => esc_html__( 'Footer - Main', 'davekellam' ),
		]
	);
}
