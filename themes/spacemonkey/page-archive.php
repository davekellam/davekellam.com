<?php
/*
Template Name: Archive
*/

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

		<h1>Archive</h1>

		<p>
            The archive is dusty. 
            There have been a variety of blogging platforms, layouts, redesigns, reconstructions, &amp; rewhatevers over the years.
            As you go further back, there's an increased likelihood that you'll run into stale links and general weirdness.
            Enjoy.
        </p>

        <?php get_search_form(); ?>

		<h2>Monthly Archive</h2>

		<section class="archive-monthly">
            <?php echo dk_get_monthly_archives(); ?>
		</section>

		<h2 class="clear">Top 50 Tags</h2>

		<p><?php wp_tag_cloud( 'smallest=0.8&largest=1.8&unit=rem' ); ?></p>

    </main>
</div>

<?php get_footer();