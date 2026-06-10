<?php
/**
 * The main template file — blog index or latest posts.
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">
	<div class="entry-list">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
					<header class="entry-header">
						<?php if ( is_singular() ) : ?>
							<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php else : ?>
							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
						<?php endif; ?>

						<div class="entry-meta">
							<?php quarter_post_date(); ?>
						</div>
					</header>

					<div class="entry-content">
						<?php the_excerpt(); ?>
					</div>
				</article>

			<?php endwhile; ?>

			<?php quarter_pagination(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'No posts found.', 'quarter' ); ?></p>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
