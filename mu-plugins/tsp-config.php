<?php
/**
 * Plugin Name: ThemeSwitcher Pro Custom Functionality
 * Plugin URI: https://themeswitcher.com
 * Description: Custom functionality for ThemeSwitcher Pro.
 * Version: 1.0.0
 * Author URI: https://davekellam.com
 * Author: Dave Kellam
 */
// phpcs:ignoreFile

add_action( 'admin_menu', function () {
    global $menu;

    foreach ( $menu as $index => $item ) {
        if ( isset( $item[2] ) && 'themeswitcher-pro' === $item[2] ) {
            $menu[ $index ][0] = 'TSP'; // Shorter menu label
            break;
        }
    }
}, 99 );

