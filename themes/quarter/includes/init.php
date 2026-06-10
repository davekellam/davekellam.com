<?php
/**
 * Core setup, hooks, and PHP block template registration.
 *
 * @package Quarter
 */

namespace Quarter\Theme\Init;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\theme_setup' );
add_action( 'init', __NAMESPACE__ . '\\register_block_templates' );

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

	register_nav_menus(
		[
			'primary' => esc_html__( 'Primary Menu', 'quarter' ),
			'footer'  => esc_html__( 'Footer Menu', 'quarter' ),
		]
	);
}

/**
 * Register block templates via PHP (WP 6.7+ Template Registration API).
 *
 * These provide block-based template definitions without requiring HTML
 * template files or the full Site Editor. Classic PHP template files
 * (single.php, archive.php, etc.) take precedence when present.
 */
function register_block_templates(): void {
	if ( ! function_exists( 'wp_register_block_template' ) ) {
		return;
	}

	$templates = [
		'quarter//index'   => [
			'title'   => __( 'Index', 'quarter' ),
			'content' => quarter_block_template( 'index' ),
		],
		'quarter//single'  => [
			'title'       => __( 'Single Post', 'quarter' ),
			'post_types'  => [ 'post' ],
			'content'     => quarter_block_template( 'single' ),
		],
		'quarter//page'    => [
			'title'      => __( 'Page', 'quarter' ),
			'post_types' => [ 'page' ],
			'content'    => quarter_block_template( 'page' ),
		],
		'quarter//archive' => [
			'title'   => __( 'Archive', 'quarter' ),
			'content' => quarter_block_template( 'archive' ),
		],
		'quarter//search'  => [
			'title'   => __( 'Search Results', 'quarter' ),
			'content' => quarter_block_template( 'search' ),
		],
		'quarter//404'     => [
			'title'   => __( '404 Not Found', 'quarter' ),
			'content' => quarter_block_template( '404' ),
		],
	];

	foreach ( $templates as $name => $args ) {
		wp_register_block_template( $name, $args );
	}
}

/**
 * Returns inline block markup for a given template slug.
 *
 * @param string $slug Template slug.
 * @return string Block template markup.
 */
function quarter_block_template( string $slug ): string {
	$header  = '<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->';
	$footer  = '<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->';

	$templates = [
		'index'   => $header . '
<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
<!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:group {"className":"entry","layout":{"type":"constrained"}} --><div class="wp-block-group entry">
<!-- wp:post-title {"isLink":true,"level":2} /-->
<!-- wp:post-excerpt /-->
<!-- wp:post-date /-->
</div><!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->
<!-- wp:query-pagination-numbers /-->
<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
</main>
<!-- /wp:group -->' . $footer,

		'single'  => $header . '
<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
<!-- wp:post-title {"level":1} /-->
<!-- wp:post-meta /-->
<!-- wp:post-featured-image /-->
<!-- wp:post-content /-->
<!-- wp:post-terms {"term":"post_tag","prefix":"Tags: "} /-->
<!-- wp:post-navigation-link {"type":"previous"} /-->
<!-- wp:post-navigation-link {"type":"next"} /-->
<!-- wp:comments -->
<div class="wp-block-comments">
<!-- wp:comments-title /-->
<!-- wp:comment-template -->
<!-- wp:comment-author-name /-->
<!-- wp:comment-date /-->
<!-- wp:comment-content /-->
<!-- /wp:comment-template -->
<!-- wp:comments-pagination -->
<!-- wp:comments-pagination-previous /-->
<!-- wp:comments-pagination-numbers /-->
<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->
<!-- wp:post-comments-form /-->
</div>
<!-- /wp:comments -->
</main>
<!-- /wp:group -->' . $footer,

		'page'    => $header . '
<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
<!-- wp:post-title {"level":1} /-->
<!-- wp:post-featured-image /-->
<!-- wp:post-content /-->
</main>
<!-- /wp:group -->' . $footer,

		'archive' => $header . '
<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
<!-- wp:query-title {"type":"archive"} /-->
<!-- wp:term-description /-->
<!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:group {"className":"entry","layout":{"type":"constrained"}} --><div class="wp-block-group entry">
<!-- wp:post-title {"isLink":true,"level":2} /-->
<!-- wp:post-date /-->
<!-- wp:post-excerpt /-->
</div><!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->
<!-- wp:query-pagination-numbers /-->
<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
</main>
<!-- /wp:group -->' . $footer,

		'search'  => $header . '
<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
<!-- wp:query-title {"type":"search"} /-->
<!-- wp:search {"label":"Search","buttonText":"Search"} /-->
<!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:group {"className":"entry","layout":{"type":"constrained"}} --><div class="wp-block-group entry">
<!-- wp:post-title {"isLink":true,"level":2} /-->
<!-- wp:post-date /-->
<!-- wp:post-excerpt /-->
</div><!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->
<!-- wp:query-pagination-numbers /-->
<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
</main>
<!-- /wp:group -->' . $footer,

		'404'     => $header . '
<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
<!-- wp:heading {"level":1} -->
<h1>' . esc_html__( 'Nothing found', 'quarter' ) . '</h1>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>' . esc_html__( 'It looks like nothing was found at this location.', 'quarter' ) . '</p>
<!-- /wp:paragraph -->
<!-- wp:search {"label":"Search","buttonText":"Search"} /-->
</main>
<!-- /wp:group -->' . $footer,
	];

	return $templates[ $slug ] ?? '';
}
