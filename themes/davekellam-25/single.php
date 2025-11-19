<?php
/**
 * The template for the default Single post view
 */

get_header();
the_post(); // setup post data
?>

<main id="content">
	<section class="grid">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</section>
</main>

<?php
get_footer();
