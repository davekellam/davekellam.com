<?php
/**
 * Turn off the built-in WordPress emoji
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\Emoji;

// Add Actions
add_action( 'init', __NAMESPACE__ . '\\disable_emojis' );

// Add filters
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\\disable_emojis_remove_dns_prefetch', 10, 2 );
add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\\disable_emojis_tinymce' );
add_filter( 'emoji_svg_url', '__return_false' ); // Removes the emoji CDN URL, if it's somehow loaded

// Ensure any registered emoji styles (and their inline CSS) are dequeued/deregistered late.
add_action( 'wp_print_styles', __NAMESPACE__ . '\\dequeue_emoji_styles', 100 );
// add_action( 'admin_print_styles', __NAMESPACE__ . '\\dequeue_emoji_styles', 100 );


/**
 * Disable the emojis.
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'wp_enqueue_emoji_styles' );
	remove_action( 'admin_print_styles', 'wp_enqueue_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins An array of TinyMCE Plugins.
 *
 * @return array Difference between the two arrays.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, [ 'wpemoji' ] );
	}

	return [];
}

/**
 * Remove emoji CDN hostname from DNS pre-fetching hints.
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 *
 * @return array Difference between the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {

		// Strip out any URLs referencing the WordPress.org emoji location
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
				unset( $urls[ $key ] );
			}
		}
	}

	return $urls;
}

/**
 * Dequeue and deregister emoji styles to remove inline CSS like
 * <style id="wp-emoji-styles-inline-css">...</style>
 */
function dequeue_emoji_styles() {
    wp_dequeue_style( 'wp-emoji-styles' );
}