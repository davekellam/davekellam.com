<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package spacemonkey
 */

get_header();

$pagination = get_the_posts_pagination( array(
	'mid_size' => 5,
	'prev_text' => __( '&larr;', 'spacemonkey' ),
	'next_text' => __( '&rarr;', 'spacemonkey' ),
) );

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :
			if ( is_home() ) :
			?>
				<div class="archive-meta">
					Follow posts with <a href="https://micro.blog/davekellam">Micro.blog</a> 
					or <a href="<?php echo bloginfo( 'rss2_url' ); ?>">RSS</a>
				</div>
			<?php endif; ?>
			
			<?php
			
			if ( is_paged() ) {
				echo $pagination;
			}

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			echo $pagination;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
