<?php
/**
 * Plugin Name: DK Under Construction
 * Description: Display a retro under-construction banner with a 90s style animated gif.
 * Version: 1.1.0
 * Author: Dave Kellam
 * 
 * HALLO person reading code 👋
 * This plugin is probably a hot mess.
 * I co-opted the robots into reliving the 90s web.
 * Sorry, not sorry.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DK_UC_VERSION', '1.1.0' );
define( 'DK_UC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DK_UC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Enqueue assets only when the banner will be output.
 */
function dk_uc_enqueue_assets() {
    if ( is_admin() || wp_doing_ajax() ) {
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
            'storageKey' => 'dk_uc_banner_dismissed',
            'storageTtl' => DAY_IN_SECONDS,
        )
    );
}
add_action( 'wp_enqueue_scripts', 'dk_uc_enqueue_assets' );

/**
 * Print the banner markup immediately after the opening body tag.
 */
function dk_uc_render_banner() {
    if ( is_admin() || wp_doing_ajax() ) {
        return;
    }

    $gif_url = apply_filters( 'dk_uc_banner_gif_url', DK_UC_PLUGIN_URL . 'assets/gifs/construction.gif' );
    ?>
    <div class="dk-uc-banner" role="region" aria-label="Under construction notice">
        <a href="#content" class="dk-uc-skip-link">Skip under construction message</a>
        <div class="dk-uc-inner">
            <img class="dk-uc-gif" src="<?php echo esc_url( $gif_url ); ?>" alt="Under construction">
            <div class="dk-uc-text">
                <p class="dk-uc-title">Under Construction</p>
                <p class="dk-uc-message">Building in public 👉 
                    <a href="https://github.com/davekellam/davekellam.com">github repo</a> / 
                    <a href="https://davekellam.com/changelog/">changelog</a>
                </p>
            </div>
            <button type="button" class="dk-uc-dismiss" aria-label="Hide under construction message">&times;</button>
        </div>
    </div>
    <?php
}
add_action( 'wp_body_open', 'dk_uc_render_banner' );
