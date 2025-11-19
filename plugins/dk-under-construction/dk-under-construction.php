<?php
/**
 * Plugin Name: DK Under Construction
 * Description: Display a retro under-construction banner with a 90s style animated gif.
 * Version: 1.1.0
 * Author: Dave Kellam
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DK_UC_VERSION', '1.1.0' );
define( 'DK_UC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DK_UC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Determine whether the banner should be shown on the current request.
 */
function dk_uc_should_display_banner() {
    if ( is_admin() || wp_doing_ajax() ) {
        return false;
    }

    if ( is_user_logged_in() ) {
        // return false;
    }

    if ( isset( $_COOKIE['dk_uc_banner_dismissed'] ) && '1' === $_COOKIE['dk_uc_banner_dismissed'] ) {
        return false;
    }

    return true;
}

/**
 * Enqueue assets only when the banner will be output.
 */
function dk_uc_enqueue_assets() {
    if ( ! dk_uc_should_display_banner() ) {
        return;
    }

    $style_path  = DK_UC_PLUGIN_PATH . 'assets/css/style.css';
    $script_path = DK_UC_PLUGIN_PATH . 'assets/js/script.js';

    wp_enqueue_style(
        'dk-under-construction',
        DK_UC_PLUGIN_URL . 'assets/css/style.css',
        array(),
        file_exists( $style_path ) ? (string) filemtime( $style_path ) : DK_UC_VERSION
    );

    wp_enqueue_script(
        'dk-under-construction',
        DK_UC_PLUGIN_URL . 'assets/js/script.js',
        array(),
        file_exists( $script_path ) ? (string) filemtime( $script_path ) : DK_UC_VERSION,
        true
    );

    wp_localize_script(
        'dk-under-construction',
        'dkUcBanner',
        array(
            'cookieName'   => 'dk_uc_banner_dismissed',
            'cookieMaxAge' => DAY_IN_SECONDS,
        )
    );
}
add_action( 'wp_enqueue_scripts', 'dk_uc_enqueue_assets' );

/**
 * Print the banner markup immediately after the opening body tag.
 */
function dk_uc_render_banner() {
    if ( ! dk_uc_should_display_banner() ) {
        return;
    }

    $gif_url = apply_filters( 'dk_uc_banner_gif_url', DK_UC_PLUGIN_URL . 'assets/gifs/construction.gif' );
    ?>
    <div class="dk-uc-banner" role="region" aria-label="Under construction notice">
        <div class="dk-uc-inner">
            <img class="dk-uc-gif" src="<?php echo esc_url( $gif_url ); ?>" alt="Under construction">
            <div class="dk-uc-text">
                <p class="dk-uc-title">Under Construction</p>
                <p class="dk-uc-message">Building in public 👉 <a href="https://github.com/davekellam/davekellam.com">davekellam./davekellam.com</a></p>
            </div>
            <button type="button" class="dk-uc-dismiss" aria-label="Hide under construction message">&times;</button>
        </div>
    </div>
    <?php
}
add_action( 'wp_body_open', 'dk_uc_render_banner' );

/**
 * Fallback for themes that do not call wp_body_open().
 */
function dk_uc_render_banner_fallback() {
    if ( did_action( 'wp_body_open' ) ) {
        return;
    }

    dk_uc_render_banner();
}
add_action( 'wp_footer', 'dk_uc_render_banner_fallback', 5 );