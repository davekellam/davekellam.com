<?php
/**
 * The template for displaying single posts.
 *
 * @package Quarter
 */

get_header();
the_post();
?>

<main class="site-main" id="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<footer class="entry-footer">
			<?php
			$birdsite_tags = get_the_term_list( get_the_ID(), 'birdsite_hashtags', '#', ' / #' );
			$time_string   = sprintf(
				'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( 'Y/m/d \a\t g:ia' ) ),
				esc_html( get_the_date( 'Y/m/d \a\t g:ia' ) ),
			);

			echo '';
			echo '<span class="posted-on">Posted ' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $birdsite_tags ) {
				echo '<span class="entry-birdsite-tags">' . $birdsite_tags . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<p class="notice">Note: this was originally posted on Twitter and might be lacking context.</p>
		</footer>
	</article>
</main>

<?php
get_footer();
