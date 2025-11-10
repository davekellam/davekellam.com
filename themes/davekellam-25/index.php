<?php
/**
 * This is the main template file.
 *
 * @package DaveKellam-25
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
