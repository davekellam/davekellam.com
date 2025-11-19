<?php
/**
 * Custom template tags for this theme.
 *
 * This file is for custom template tags only and it should not contain
 * functions that will be used for filtering or adding an action.
 *
 * All functions should be prefixed in order to prevent pollution of the
 * global namespace and potential conflicts with functions from plugins.
 *
 * Example: `davekellam_function()`
 */

/**
 * Utility function for returning the url of a theme image
 *
 * @param string $filename The filename with extension
 * @param bool   $echo_url Echo the url
 *
 * @return string
 */
function davekellam_get_image_url( $filename, $echo_url = false ) {
	$url = get_template_directory_uri() . '/dist/images/' . $filename;

	if ( $echo_url ) {
		echo $url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return null;
	}

	return $url;
}

/**
 * Function to generate the pagination on various archive page views
 *
 * Needs to produce Prev/Next and 1 ... # links
 */
function davekellam_get_pagination() {
	$args = [
		'prev_text' => '',
		'next_text' => '',
	];

	$pagination = paginate_links( $args );

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
