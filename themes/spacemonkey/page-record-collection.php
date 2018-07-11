<?php
/**
 * Template: Record Collection
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
				</header>

				<?php spacemonkey_post_thumbnail(); ?>

				<div class="entry-content">
					<?php the_content(); ?>

					<h3>Albums</h3>
					<?php
						$albums = dk_get_record_collection();
						$albums = explode( "\n", $albums );

						echo '<ul>';
						foreach ( $albums as $album ) {
							echo '<li>' . esc_html( $album ) . '</li>';
						}
						echo '</ul>';
					?>
					
					<h3>Soundtracks/Compilations</h3>
					<?php
						$soundtracks = dk_get_record_collection( 'compilations' );
						$soundtracks = explode( "\n", $soundtracks );

						echo '<ul>';
						foreach ( $soundtracks as $soundtrack ) {
							echo '<li>' . esc_html( $soundtrack ) . '</li>';
						}
						echo '</ul>';
					?>
					
					<h3>Singles</h3>
					<?php
						$singles = dk_get_record_collection( 'singles' );
						$singles = explode( "\n", $singles );

						echo '<ul>';
						foreach ( $singles as $single ) {
							echo '<li>' . esc_html( $single ) . '</li>';
						}
						echo '</ul>';
					?>

				</div>
			</article>

		<?php endwhile; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
