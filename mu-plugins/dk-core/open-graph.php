<?php
/**
 * Setup Meta, OpenGraph and other header tags for the site
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\Meta;

// Setup Actions and Fitlers
add_filter( 'wp_head', __NAMESPACE__ . '\\open_graph_tags' );
add_filter( 'wp_head', __NAMESPACE__ . '\\meta' );
add_action( 'wp_head', __NAMESPACE__ . '\\add_indieweb_tags' );


/**
 * Produce Open Graph tags for a page
 */
function open_graph_tags() {
	global $post;
	global $wp;

	$tags = [
		'title'       => wp_title( '—', false, 'right' ) . get_bloginfo( 'name' ), // "{page title} - {site title}"
		'site_name'   => get_bloginfo( 'name' ),
		'description' => ( ! empty( get_the_excerpt() ) && ! is_search() ) ? get_the_excerpt() : get_bloginfo( 'description' ),
		'url'         => home_url( add_query_arg( [], $wp->request ) ) . '/', // get current page url
		'type'        => 'page',
		'image'       => ( has_post_thumbnail() ) ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '',
	];
	// Contexts
	if ( is_single() ) {
		$tags['type'] = 'article';
	} elseif ( is_archive() ) {
		$tags['type'] = 'archive';
	} elseif ( is_search() ) {
		$tags['type'] = 'search';
	}

	$tag_string = [];

	foreach ( $tags as $prop => $content ) {
		$tag_string[] = '<meta property="og:' . esc_attr( $prop ) . '" content="' . esc_html( $content ) . '">' . "\n";
	}

	$html = implode( '', $tag_string );

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Produce general meta tags for header
 */
function meta() {
	$tags = [
		'description' => ( ! empty( get_the_excerpt() ) && ! is_search() ) ? get_the_excerpt() : get_bloginfo( 'description' ),
	];

	$tag_string = [];

	foreach ( $tags as $prop => $content ) {
		$tag_string[] = '<meta name="' . esc_attr( $prop ) . '" content="' . esc_html( $content ) . '">' . "\n";
	}

	$html = implode( '', $tag_string );

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Add header tags for indieweb
 */
function add_indieweb_tags() {
	?>
	
	<!-- IndieWeb https://indieweb.org/How_to_set_up_web_sign-in_on_your_own_domain -->
	<link rel="me" href="https://bsky.app/profile/davekellam.com" />
	<link rel="me" href="https://github.com/davekellam" />
	<link rel="me" href="https://instagram.com/davekellam" />
	<link rel="me" href="https://davekellam.tumblr.com/" />
	<link rel="me" href="https://flickr.com/photos/davekellam" />
	<link rel="me" href="https://www.last.fm/user/eightface" />

	<?php
}
