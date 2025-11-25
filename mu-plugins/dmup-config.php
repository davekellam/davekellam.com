<?php
/**
 * Plugin Name: Don't Mess Up Prod Configuration
 * Description: Configuration for the Don't Mess Up Prod plugin
 * Author:      Dave Kellam
 * Author URI:  https://davekellam.com
 * Version:     2025-11-25
 * 
 * @see	https://github.com/davekellam/dont-mess-up-prod
 */

/**
 * Configure minimum capability for the environment indicator
 *
 * By default, the plugin only shows to explicitly allowed users.
 * Use this filter to enable role-based access.
 *
 * @param string|false $capability Current capability setting.
 * @return string|false Modified capability setting.
 */
function dmup_set_minimum_capability( $capability ) {
	return 'publish_posts';
}
add_filter( 'dmup_minimum_capability', 'dmup_set_minimum_capability' );

/**
 * Configure environment URLs for your project
 *
 * Customize the URLs used to detect different environments
 *
 * @param array $urls Current environment URLs array.
 * @return array Modified environment URLs array.
 */
function dmup_set_environment_urls( $urls ) {
	return [
		'local'      => 'https://davekellam.local',
		'staging'    => 'https://davekellamcom.stage.site',
		'production' => 'https://davekellam.com',
	];
}
add_filter( 'dmup_environment_urls', 'dmup_set_environment_urls' );
