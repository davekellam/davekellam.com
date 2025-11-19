<?php
/**
 * The Homepage Template
 **/

?>

get_header(); ?>

<main id="content">
	<section class="grid">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
