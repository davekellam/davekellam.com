<?php
/**
 * Helper functions
 *
 * @package davekellam\core
 */

namespace DaveKellam\Core\Helpers;

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