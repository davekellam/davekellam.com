<?php
/**
 * The template for displaying archive pages.
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">

	<header class="page-header">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</header>

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

					<div class="entry-link">
						&rarr; <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Link', 'quarter' ); ?></a>
					</div>
				</article>

			<?php endwhile; ?>

			<?php quarter_pagination(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'No posts found.', 'quarter' ); ?></p>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
