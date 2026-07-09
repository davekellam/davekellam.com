<?php
/**
 * The template for displaying all pages.
 *
 * @package Quarter
 */

get_header();
the_post();
?>

<main class="site-main" id="main">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>

			<h2>Monthly Archive</h2>

			<section class="archive-monthly">
				<?php echo DaveKellam\Core\Helpers\dk_get_monthly_archives(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</section>

			<h2>Top 50 Tags</h2>

			<p><?php wp_tag_cloud( 'smallest=0.8&largest=1.6&unit=rem' ); ?></p>
		</div>
</main>

<?php
get_footer();
