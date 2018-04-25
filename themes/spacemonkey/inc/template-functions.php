<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package _s
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function _s_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', '_s_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function _s_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', '_s_pingback_header' );

/**
 * Generate a post title and slug from the content 
 */
function sm_generate_title_and_slug( $data ) {

    if( 'post' == $data['post_type'] && empty( $data['post_title'] ) ) {
		
		// Quick way to strip media (could do something like use media type to inform title)
		$title = wp_trim_excerpt( $data['post_content'] ); 
		
		// Limit generated excerpt to 10 words instead of 55 by wp_trim_excerpt (could go with character length)
		$title = wp_trim_words( $title, 10, '' ); 

		// Set the title and slug
        $data['post_title'] = $title;
        $data['post_name'] = sanitize_title( $title );

    }

    return $data;

}
add_filter( 'wp_insert_post_data', 'sm_generate_title_and_slug' );
