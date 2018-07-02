<?php
/**
 * The template for the Photos page
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

				<?php _s_post_thumbnail(); ?>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>

			<hr>

		<?php endwhile; ?>

			<?php $recent_photos = new WP_Query( array(
				'category_name' => 'photo'
			) ); ?>

			<?php while ( $recent_photos->have_posts() ) : ?>

				<?php

				$recent_photos->the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				?>

			<?php endwhile; ?>

			<nav class="navigation pagination" role="navigation">
				<h2 class="screen-reader-text">Posts navigation</h2>
				<div class="nav-links">
					<div class='prev'></div>

					<div class='page-numbers'>1 of <?php echo $recent_photos->max_num_pages; ?></div>

					<div class='next'><a href="<?php echo esc_url( home_url( '/category/photo/page/2/' ) ); ?>">Next</a></div>
				</div>
			</nav>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
