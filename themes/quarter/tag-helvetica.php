<?php
/**
 * The template for displaying Helvetica tag archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">

	<header class="page-header">
		<h2>Helveti.ca Archive</h2>
		<p>Hello 👋</p>

		<p>This serves as an archive for the <a href="https://www.tumblr.com/helveticablog">Helveti.ca Tumblr blog</a>. 
			That site lost the link to the custom domain, styling, etc.
		</p>
		<p>
			<em>Imported: Dec 15 2025</em><br>
			<em>Updated: July 9, 2026</em>
		</p>
	</header><!-- .page-header -->

	<div class="entry-list">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
					<header class="entry-header">
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="entry-meta">
							<?php quarter_post_date(); ?>
						</div>
					</header>

					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>

					<div class="entry-link">
						&rarr; <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Link', 'quarter' ); ?></a>
					</div>
				</article>

			<?php endwhile; ?>

			<?php quarter_pagination(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'No posts found.', 'quarter' ); ?></p>

		<?php endif; ?>

	</div>

</main><!-- #main -->

<?php
get_footer();
