<?php
/**
 * The template for displaying the changelog archive.
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<?php
		$changelog_query = new WP_Query(
			[
				'post_type'      => 'changelog',
				'posts_per_page' => 100,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);
		?>

		<?php if ( $changelog_query->have_posts() ) : ?>

			<?php
			$current_month = '';
			while ( $changelog_query->have_posts() ) :
				$changelog_query->the_post();
				$month = get_the_date( 'F' );
				?>

				<?php if ( $month !== $current_month ) : ?>
					<?php if ( '' !== $current_month ) : ?>
						</ul>
					<?php endif; ?>
					<h2 class="wp-block-heading"><?php echo esc_html( $month ); ?></h2>
					<ul class="wp-block-list">
					<?php $current_month = $month; ?>
				<?php endif; ?>

				<li>
					<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?> - <?php echo esc_html( get_the_content() ); ?>
				</li>

			<?php endwhile; ?>
			</ul>

		<?php else : ?>

			<p><?php esc_html_e( 'No changelog entries yet.', 'quarter' ); ?></p>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

		<div class="page-content">
			<?php the_content(); ?>
		</div>

	</article>
</main>

<?php
get_footer();
