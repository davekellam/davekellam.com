<?php
/**
 * Spacemonkey functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package spacemonkey
 */

if ( ! function_exists( 'spacemonkey_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function spacemonkey_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on spacemonkey, use a find and replace
		 * to change 'spacemonkey' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'spacemonkey', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head, then remove comments feeds :P
		add_theme_support( 'automatic-feed-links' );
		add_filter( 'feed_links_show_comments_feed', '__return_false' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'spacemonkey' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}
endif;
add_action( 'after_setup_theme', 'spacemonkey_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function spacemonkey_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'spacemonkey_content_width', 1200 );
}
add_action( 'after_setup_theme', 'spacemonkey_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function spacemonkey_scripts() {

	// create css versions based on file date
	$css_ver = date( "ymdHi", filemtime( get_stylesheet_directory() . '/style.css' ) );

	wp_enqueue_style( 'spacemonkey', get_template_directory_uri() . '/style.css', false, $css_ver );

	wp_enqueue_script( 'spacemonkey-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'spacemonkey-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'spacemonkey_scripts' );

/**
 * Remove Jetpack styles
 */
function remove_jetpack_styles() {
	wp_deregister_style( 'jetpack-widgets' ); // Widgets
	// wp_deregister_style( 'jetpack-subscriptions' ); // Subscribe by email
}
add_action( 'wp_print_styles', 'remove_jetpack_styles' );

/**
 * Customize Emoji usage
 */
function emoji_control() {
	// Remove Emoji from RSS feed (not a fan of forcing the look on people outside the site,
	// I find it also has knock-on effects for micro.blog and Reeder that I dislike personally).
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
}
add_action( 'init', 'emoji_control' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Record collection utility functions
 */
require get_template_directory() . '/inc/record-collection.php';

/**
 * Generate monthly archive listing with transient caching
 * 
 * @param int $cache_hours How many hours to cache the result (default 24)
 * @return string HTML output for the archive listing
 */
function dk_get_monthly_archives( $cache_hours = 24 ) {
    $transient_key = 'dk_monthly_archives';
    
    // Try to get cached version
    $cached = get_transient( $transient_key );
    if ( false !== $cached && ! empty( $cached ) ) {
        return $cached;
    }
    
    // Use wp_get_archives to get months with posts
    $archive_list = wp_get_archives( array(
        'type'            => 'monthly',
        'format'          => 'custom',
        'echo'            => false,
        'post_type'       => 'post',
        'show_post_count' => false,
    ) );
    
    // Parse the archive links to extract year/month
    $has_posts = array();
    $years = array();
    $years_with_posts = array();
    
    if ( $archive_list ) {
        preg_match_all( '/href=[\'"]([^\'"]*\/(\d{4})\/(\d{2})\/?)[\'"]/', $archive_list, $matches );
        if ( ! empty( $matches[2] ) ) {
            foreach ( $matches[2] as $index => $year ) {
                $month = $matches[3][$index];
                $key = $year . '-' . $month;
                $has_posts[$key] = true;
                $years[] = (int) $year;
                $years_with_posts[ (int) $year ] = true;
            }
        }
    }
    
    // Get year range
    if ( empty( $years ) ) {
        return '';
    }
    
    $current_year = (int) date( 'Y' );
    $oldest_year = min( $years );
    
    // Month abbreviations
    $month_abbr = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
    
    // Build HTML
    $output = '';
    
    for ( $year = $current_year; $year >= $oldest_year; $year-- ) {
        // Skip years with no posts
        if ( ! isset( $years_with_posts[ $year ] ) ) {
            continue;
        }
        
        $output .= '<ul>' . "\n";
        $output .= '<li class="year">' . $year . ':</li>' . "\n";
        
        for ( $month = 1; $month <= 12; $month++ ) {
            $month_key = $year . '-' . str_pad( $month, 2, '0', STR_PAD_LEFT );
            $month_name = $month_abbr[$month - 1];
            
            if ( isset( $has_posts[$month_key] ) ) {
                $url = home_url( '/' . $year . '/' . str_pad( $month, 2, '0', STR_PAD_LEFT ) . '/' );
                $output .= '<li><a href="' . esc_url( $url ) . '">' . $month_name . '</a></li>' . "\n";
            } else {
                $output .= '<li class="no-archive">' . $month_name . '</li>' . "\n";
            }
        }
        
        $output .= '</ul>' . "\n";
    }
    
    // Cache for specified hours (only if we have output)
    if ( ! empty( $output ) ) {
        set_transient( $transient_key, $output, $cache_hours * HOUR_IN_SECONDS );
    }
    
    return $output;
}

/**
 * Clear the monthly archives cache
 * Useful to call after importing content or publishing posts
 */
function dk_clear_monthly_archives_cache() {
    delete_transient( 'dk_monthly_archives' );
}

// Clear cache when a post is published or updated
add_action( 'publish_post', 'dk_clear_monthly_archives_cache' );
add_action( 'delete_post', 'dk_clear_monthly_archives_cache' );

