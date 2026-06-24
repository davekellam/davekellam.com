<?php
/**
 * The template for displaying the video archive.
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
					<a href="<?php the_permalink(); ?>" class="entry-thumbnail-link">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium' ); ?>
						<?php endif; ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					</a>
					<div class="entry-meta">
						<?php quarter_post_date(); ?>
					</div>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>
				</article>

			<?php endwhile; ?>

			<?php quarter_pagination(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'No videos found.', 'quarter' ); ?></p>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
