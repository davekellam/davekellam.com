<?php
/**
 * RSS Feed Overrides & Tweaks
 */

namespace DaveKellam\Core\RSS;

// Feed imagery
add_filter( 'the_content_feed', __NAMESPACE__ . '\\prepend_featured_image_to_content' );
add_filter( 'the_excerpt_feed', __NAMESPACE__ . '\\prepend_featured_image_to_content' );

// Contact info
add_action( 'rss2_head', __NAMESPACE__ . '\\add_managing_editor_to_rss' );

// Add filter for Atom feed author information
add_action( 'atom_head', __NAMESPACE__ . '\\add_author_to_atom' );

/**
 * Prepend featured image to RSS feed content
 *
 * @param string $content The post content
 * @return string Modified content with featured image at the top
 */
function prepend_featured_image_to_content( $content ) {
	if ( has_post_thumbnail() ) {
		$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );

		if ( $featured_image ) {
			$image_html = sprintf(
				'<div class="rss-featured-image"><img src="%s" alt="%s" /></div>',
				esc_url( $featured_image ),
				esc_attr( get_the_title() )
			);

			$content = $image_html . $content;
		}
	}

	return $content;
}

/**
 * Add managing editor to RSS feeds
 */
function add_managing_editor_to_rss() {
	echo '<managingEditor>mail@davekellam.com (Dave Kellam)</managingEditor>' . "\n";
}

/**
 * Add author information to Atom feeds
 */
function add_author_to_atom() {
	echo '<author>' . "\n";
	echo '  <name>Dave Kellam</name>' . "\n";
	echo '  <email>mail@davekellam.com</email>' . "\n";
	echo '</author>' . "\n";
}
