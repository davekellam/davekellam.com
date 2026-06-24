<?php
/**
 * Template Name: Videos
 *
 * @package Quarter
 */

get_header();
the_post();
?>

<main class="site-main" id="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php
		$videos = new WP_Query(
			[
				'post_type'      => 'video',
				'posts_per_page' => 20,
				'no_found_rows'  => true,
			]
		);
		?>

		<?php if ( $videos->have_posts() ) : ?>

			<div class="entry-list">
				<?php while ( $videos->have_posts() ) : $videos->the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
						<a href="<?php the_permalink(); ?>" class="entry-thumbnail-link">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium' ); ?>
							<?php endif; ?>
							<h2 class="entry-title"><?php the_title(); ?></h2>
						</a>
						<div class="entry-meta">
							<?php quarter_post_date(); ?>
						</div>
						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div>
					</article>

				<?php endwhile; ?>
			</div>

		<?php else : ?>

			<p><?php esc_html_e( 'No videos yet.', 'quarter' ); ?></p>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</article>
</main>

<?php
get_footer();
