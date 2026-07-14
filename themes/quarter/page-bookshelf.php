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
			<h1><?php the_title(); ?></h1>
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

				<div class="book-item" tabindex="0">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="book-cover">
							<?php the_post_thumbnail(); ?>
						</div>
					<?php endif; ?>
					<div class="book-overlay">
						<h3 class="book-title"><?php echo esc_html( get_the_title() ); ?></h3>
						<?php
						$author    = get_post_meta( get_the_ID(), 'book_author', true );
						$date_read = strtotime( (string) get_post_meta( get_the_ID(), 'book_read_date', true ) );
						$rating    = max( 0, min( 5, (int) get_post_meta( get_the_ID(), 'book_user_rating', true ) ) );

						if ( $author ) :
							?>
							<p class="book-author">By <?php echo esc_html( $author ); ?></p>
						<?php endif; ?>
						<?php if ( $rating ) : ?>
							<p class="book-rating" aria-label="<?php echo esc_attr( sprintf( '%d out of 5 stars', $rating ) ); ?>">
								<span class="book-rating-stars" aria-hidden="true"><?php echo wp_kses_post( str_repeat( '&#9733;', $rating ) . str_repeat( '&#9734;', 5 - $rating ) ); ?></span>
							</p>
						<?php endif; ?>
						<?php if ( $date_read ) : ?>
							<p class="book-date-read">Finished<br> <?php echo esc_html( gmdate( 'F j', $date_read ) ); ?></p>
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
