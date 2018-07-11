<?php
/**
 * The template for displaying all pages
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
				</header>

				<?php spacemonkey_post_thumbnail(); ?>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>

		<?php endwhile; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
