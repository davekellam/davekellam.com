<?php
/**
 * Enqueue theme scripts.
 *
 * @package Quarter
 */

namespace Quarter\Theme\Scripts;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Enqueue front-end scripts.
 */
function enqueue_scripts(): void {
	// Conditionally enqueue comment-reply script.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
