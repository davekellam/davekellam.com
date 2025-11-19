<?php
/**
 * Overrides
 *
 * @package davekellam\theme
 */

namespace DaveKellam\Theme\DK25\Overrides;

add_filter( 'login_headertext', __NAMESPACE__ . '\\login_logo_url_title' );
add_filter( 'login_headerurl', __NAMESPACE__ . '\\login_logo_url' );

// Disable big image threshold
add_filter( 'big_image_size_threshold', '__return_false' );

// Conditionally load block assets (only load styles on pages where the block is present)
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Override logo link on login page
 *
 * @return string
 */
function login_logo_url() {
	return home_url();
}

/**
 * Override login title on login page
 *
 * @return string
 */
function login_logo_url_title() {
	return 'Dave Kellam';
}
