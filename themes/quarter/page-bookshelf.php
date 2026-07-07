<?php
/**
 * The template for recently read books.
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

		<div class="page-content">
			<?php the_content(); ?>
		</div>

		<?php
		$books_query = new WP_Query(
			[
				'post_type'      => 'book',
				'posts_per_page' => 200,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);
		?>

		<?php if ( $books_query->have_posts() ) : ?>

			<?php
			$current_year = '';
			while ( $books_query->have_posts() ) :
				$books_query->the_post();
				$year = get_the_date( 'Y' );
				?>

				<?php if ( $year !== $current_year ) : ?>
					<?php if ( '' !== $current_year ) : ?>
						</div>
					<?php endif; ?>
					<h2><?php echo esc_html( $year ); ?></h2>
					<div class="books-grid">
					<?php $current_year = $year; ?>
				<?php endif; ?>

				<div class="book-item">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="book-cover">
							<?php the_post_thumbnail( 'medium' ); ?>
						</div>
					<?php endif; ?>
					<div class="book-overlay">
						<h3 class="book-title"><?php echo esc_html( get_the_title() ); ?></h3>
						<?php
						$author = get_post_meta( get_the_ID(), 'book_author', true );
						if ( $author ) :
							?>
							<p class="book-author">By <?php echo esc_html( $author ); ?></p>
						<?php endif; ?>
					</div>
				</div>

			<?php endwhile; ?>
			</div>

		<?php else : ?>

			<p><?php esc_html_e( 'No books found', 'quarter' ); ?></p>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</article>
</main>

<?php
get_footer();
