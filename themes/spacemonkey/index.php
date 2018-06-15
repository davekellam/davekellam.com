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

global $wp_query;

$current_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$last_page = $wp_query->max_num_pages;

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : ?>

				<?php

				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				?>

			<?php endwhile; ?>

			<nav class="navigation pagination" role="navigation">
				<h2 class="screen-reader-text">Posts navigation</h2>
				<div class="nav-links">
					<?php
					echo "<div class='prev'>" . get_previous_posts_link( '<span>&larr;</span> Prev' ) . "</div>";

					echo "<div class='page-numbers'>" . $current_page . " of " . $last_page . "</div>";

					echo "<div class='next'>" . get_next_posts_link( 'Next <span>&rarr;</span>' ) . "</div>";
					?>
				</div>
			</nav>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
