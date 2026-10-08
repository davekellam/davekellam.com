<?php
/**
 * Bookshelf block & utility render functions
 *
 * @package Quarter
 */

namespace Quarter\Theme\Blocks;

/**
 * Render the Bookshelf block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function render_bookshelf( array $attributes ): string {
	if ( ! post_type_exists( 'book' ) ) {
		return '';
	}

	$status = 'reading' === ( $attributes['status'] ?? 'read' ) ? 'reading' : 'read';

	$args = [
		'post_type'      => 'book',
		'posts_per_page' => max( 1, $attributes['count'] ?? 200 ),
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	];

	if ( 'reading' === ( $attributes['status'] ?? 'read' ) ) {
		$args['post_status'] = [ 'publish', 'draft' ];
		$args['meta_query']  = [
			[
				'key'     => 'book_read_date',
				'compare' => 'NOT EXISTS',
			],
		];
	} else {
		$args['post_status'] = 'publish';
	}

	$books_query = new \WP_Query( $args );

	if ( ! $books_query->have_posts() ) {
		return '';
	}

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'wp-block-quarter-bookshelf' ) );

	ob_start();

	printf( '<div %1$s>', $wrapper_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() escapes its own output.

	$title = trim( (string) ( $attributes['title'] ?? '' ) );
	if ( '' !== $title ) {
		printf( '<h2 class="bookshelf-title">%s</h2>', esc_html( $title ) );
	}

	if ( 'reading' === $status || empty( $attributes['groupByYear'] ) ) {
		render_books_grid( $books_query );
	} else {
		render_books_by_year( $books_query );
	}

	echo '</div>';

	wp_reset_postdata();

	return ob_get_clean();
}

/**
 * Render books grouped by year of read date.
 *
 * @param \WP_Query $books_query The book query.
 * @return void
 */
function render_books_by_year( \WP_Query $books_query ): void {
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

		<?php render_book_item( true ); ?>

	<?php endwhile; ?>
	</div>
	<?php
}

/**
 * Render books as a single grid.
 *
 * @param \WP_Query $books_query The book query.
 * @return void
 */
function render_books_grid( \WP_Query $books_query ): void {
	?>
	<div class="books-grid">
		<?php while ( $books_query->have_posts() ) : $books_query->the_post(); ?>
			<?php render_book_item( false ); ?>
		<?php endwhile; ?>
	</div>
	<?php
}

/**
 * Render a single book item.
 *
 * @param bool $show_reading_meta Whether to show rating and read date.
 * @return void
 */
function render_book_item( bool $show_reading_meta ): void {
	?>
	<div class="book-item" tabindex="0">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="book-cover">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php endif; ?>
		<div class="book-overlay">
			<h3 class="book-title"><?php echo esc_html( get_the_title() ); ?></h3>
			<?php
			$author = get_post_meta( get_the_ID(), 'book_author', true );

			if ( $author ) :
				?>
				<p class="book-author">
					<?php
					/* translators: %s: book author name. */
					echo esc_html( sprintf( __( 'By %s', 'quarter' ), $author ) );
					?>
				</p>
			<?php endif; ?>
			<?php if ( $show_reading_meta ) : ?>
				<?php
				$date_read = strtotime( (string) get_post_meta( get_the_ID(), 'book_read_date', true ) );
				$rating    = max( 0, min( 5, round( (float) get_post_meta( get_the_ID(), 'book_user_rating', true ) * 4 ) / 4 ) );
				?>
				<?php if ( $rating > 0 ) : ?>
					<?php
					$whole     = (int) floor( $rating );
					$remainder = (int) round( ( $rating - $whole ) * 4 );

					// Key = number of quarter-stars in the remainder.
					$fractions = [
						1 => '&#188;', // ¼ (one quarter)
						2 => '&#189;', // ½ (one half)
						3 => '&#190;', // ¾ (three quarters)
					];

					$stars = str_repeat( '&#9733;', $whole );
					if ( $remainder > 0 ) {
						$stars .= $fractions[ $remainder ];
					}
					?>
					<p class="book-rating" aria-label="<?php echo esc_attr( sprintf( '%s out of 5 stars', (string) $rating ) ); ?>">
						<span class="book-rating-stars" aria-hidden="true"><?php echo wp_kses_post( $stars ); ?></span>
					</p>
				<?php endif; ?>
				<?php if ( $date_read ) : ?>
					<p class="book-date-read"><?php echo esc_html( __( 'Finished', 'quarter' ) ); ?><br> <?php echo esc_html( gmdate( 'F j', $date_read ) ); ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
