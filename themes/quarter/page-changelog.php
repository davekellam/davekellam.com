<?php
/**
 * The template for displaying the changelog archive.
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Changelog', 'quarter' ); ?></h1>
	</header>

	<div class="entry-list">

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
				$month = get_the_date( 'F Y' );
				?>

				<?php if ( $month !== $current_month ) : ?>
					<?php if ( '' !== $current_month ) : ?>
						</ul>
					<?php endif; ?>
					<h2 class="changelog-month"><?php echo esc_html( $month ); ?></h2>
					<ul class="changelog-list">
					<?php $current_month = $month; ?>
				<?php endif; ?>

				<li class="changelog-entry">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
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

	</div>
</main>

<?php
get_footer();
