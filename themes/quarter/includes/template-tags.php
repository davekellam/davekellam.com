<?php
/**
 * Custom template tags.
 *
 * @package Quarter
 */

/**
 * Output formatted post date with a time element.
 */
function quarter_post_date(): void {
	$date_format = get_option( 'date_format' );
	printf(
		'<time class="entry-date" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date( $date_format ) )
	);
}

/**
 * Output an entry footer with tags and categories.
 */
function quarter_entry_footer(): void {
	$tags = get_the_tag_list( '', ', ' );

	if ( $tags ) {
		echo '<footer class="entry-footer">';

		if ( $tags ) {
			printf(
				'<span class="entry-tags">%1$s %2$s</span>',
				esc_html__( 'Tags:', 'quarter' ),
				$tags // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</footer>';
	}
}

/**
 * Output archive pagination using paginate_links().
 */
function quarter_pagination(): void {
	$pagination = paginate_links(
		[
			'prev_text' => esc_html__( '&laquo; Prev', 'quarter' ),
			'next_text' => esc_html__( 'Next &raquo;', 'quarter' ),
			'type'      => 'list',
		]
	);

	if ( $pagination ) {
		echo '<nav class="pagination" aria-label="' . esc_attr__( 'Posts navigation', 'quarter' ) . '">';
		echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</nav>';
	}
}
