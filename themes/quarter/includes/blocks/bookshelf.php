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
		'post_status'    => 'publish',
		'posts_per_page' => max( 1, (int) ( $attributes['count'] ?? 200 ) ),
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	];

	if ( 'reading' === $status ) {
		$args[] = [
			'post_status'    => [ 'publish', 'draft' ],
			'posts_per_page' => max( 1, (int) ( $attributes['count'] ?? 10 ) ),
			'meta_query'     => [
				[
					'key'     => 'book_read_date',
					'compare' => 'NOT EXISTS',
				],
			],
		];
	} 

	$books_query = new \WP_Query( $args );

	if ( ! $books_query->have_posts() ) {
		return '';
	}

	ob_start();

	$title = trim( (string) ( $attributes['title'] ?? '' ) );
	if ( '' !== $title ) {
		printf( '<h2 class="bookshelf-title">%s</h2>', esc_html( $title ) );
	}

	if ( 'reading' === $status || empty( $attributes['groupByYear'] ) ) {
		render_books_grid( $books_query );
	} else {
		render_books_by_year( $books_query );
	}

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
				$rating    = max( 0, min( 5, (int) get_post_meta( get_the_ID(), 'book_user_rating', true ) ) );
				?>
				<?php if ( $rating ) : ?>
					<p class="book-rating" aria-label="<?php echo esc_attr( sprintf( '%d out of 5 stars', $rating ) ); ?>">
						<span class="book-rating-stars" aria-hidden="true"><?php echo wp_kses_post( str_repeat( '&#9733;', $rating ) . str_repeat( '&#9734;', 5 - $rating ) ); ?></span>
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
