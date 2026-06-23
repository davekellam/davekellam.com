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
	$tags = get_the_tag_list( '/ ', ' / ' );

	$time_string = sprintf(
		'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( 'Y/m/d \a\t g:ia' ) ),
		esc_html( get_the_date( 'Y/m/d \a\t g:ia' ) ),
	);

	echo '<footer class="entry-footer">';
	echo '<span class="posted-on">Posted ' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( $tags ) {
		echo '<span class="entry-tags">' . $tags . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</footer>';
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
