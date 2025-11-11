<?php
/**
 * Override core behaviour
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\Overrides;

// Remove WordPress generator meta
remove_action( 'wp_head', 'wp_generator' );

// Remove Windows Live Writer manifest link 🥔
remove_action( 'wp_head', 'wlwmanifest_link' );

// Remove the link to Really Simple Discovery service endpoint
remove_action( 'wp_head', 'rsd_link' );

// Changes to the admin area
add_action( 'admin_init', __NAMESPACE__ . '\\customize_admin' );

// Modify default queries
add_action( 'pre_get_posts', __NAMESPACE__ . '\\query_modification' );

// Restrict REST-API endpoints
add_filter( 'rest_endpoints', __NAMESPACE__ . '\\restrict_endpoints' );

// Restrict sitemap generation
add_filter( 'wp_sitemaps_add_provider', __NAMESPACE__ . '\\restrict_sitemaps', 10, 2 );

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Customize the admin appearance
 */
function customize_admin() {
	// Remove unused dashboard modules
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );

	// Prevent default custom fields box from being available
	remove_meta_box( 'postcustom', 'post', 'normal' );
}

/**
 * Modify default WordPress queries
 *
 * @param object $query default WordPress query object
 */
function query_modification( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Set query default to 12 posts
	$query->set( 'posts_per_page', 12 );
}

/**
 * Remove user endpoints for unauthorized users.
 *
 * @param  array $endpoints Array of endpoints
 *
 * @return array $endpoints Filtered list of endpoints
 */
function restrict_endpoints( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		if ( isset( $endpoints['/wp/v2/users'] ) ) {
			unset( $endpoints['/wp/v2/users'] );
		}
		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
			unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
	}

	return $endpoints;
}

/**
 * Restrict sitemap generation
 *
 * @param object $provider WP_Sitemaps_Provider instance
 * @param string $name     Name of the sitemap provider
 *
 * @return object|bool $provider WP_Sitemaps_Provider instance
 */
function restrict_sitemaps( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}

	return $provider;
}
