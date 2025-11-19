<?php
/**
 * Core script files
 *
 * @package davekellam\theme
 */

namespace DaveKellam\Theme\DK25\Scripts;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts' );

// Load scripts and styles for admin areas:
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\load_admin_scripts', 20 );

// Load editor scripts
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\editor_scripts' );

// Google Tag Manager
add_action( 'wp_head', __NAMESPACE__ . '\\google_tag_manager_head', 10 );
add_action( 'after_body', __NAMESPACE__ . '\\google_tag_manager_body' );

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	wp_enqueue_script(
		'frontend',
		DAVEKELLAM_TEMPLATE_URL . '/dist/js/frontend.min.js',
		[ 'jquery', 'react', 'react-dom' ],
		filemtime( DAVEKELLAM_PATH . 'dist/js/frontend.min.js' ),
		true
	);

	wp_localize_script(
		'frontend',
		'mainScript',
		[
			'assetPath' => get_template_directory_uri() . '/dist/',
		]
	);
}

/**
 * Load client-side scripts for the block editor
 *
 * @return void
 */
function editor_scripts() {
	wp_enqueue_script(
		'editor-scripts',
		get_stylesheet_directory_uri() . '/dist/js/editor.min.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		filemtime( get_stylesheet_directory() . '/dist/js/editor.min.js' ),
		true
	);
}

/**
 * Register and load admin JavaScript
 */
function load_admin_scripts() {

	// Enqueue Scripts
	wp_enqueue_script(
		'admin-scripts',
		get_template_directory_uri() . '/dist/js/admin.min.js',
		false,
		DAVEKELLAM_VERSION,
		true
	);
}

/**
 * GTM code to appear in the <head>
 */
function google_tag_manager_head() {
	$gtm_id = function_exists( 'get_field' ) ? get_field( 'gtm_id', 'options' ) : null;

	if ( $gtm_id ) :
		?>
		<!-- Google Tag Manager -->
		<script>(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
			var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', '<?php echo esc_attr( 'GTM-' . $gtm_id ); ?>');
		</script>
		<!-- End Google Tag Manager -->
		<?php
	endif;
}

/**
 * GTM code to appear just after the opening <body> tag
 */
function google_tag_manager_body() {
	$gtm_id = function_exists( 'get_field' ) ? get_field( 'gtm_id', 'options' ) : null;

	if ( $gtm_id ) :
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( 'GTM-' . $gtm_id ); ?>"
					height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	endif;
}
