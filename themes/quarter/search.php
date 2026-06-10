<?php
/**
 * The template for displaying search results.
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">

	<header class="page-header">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Results for: %s', 'quarter' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
	</header>

	<?php get_search_form(); ?>

	<div class="entry-list">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
					<header class="entry-header">
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="entry-meta">
							<?php quarter_post_date(); ?>
						</div>
					</header>

					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>
				</article>

			<?php endwhile; ?>

			<?php quarter_pagination(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'No results found. Try a different search.', 'quarter' ); ?></p>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
