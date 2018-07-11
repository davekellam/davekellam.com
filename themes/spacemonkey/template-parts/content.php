<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! in_category( 'micro' ) ) : // @todo refine this a bit more, works for now ?>
	<header class="entry-header">
		<?php if ( is_singular( 'post' ) ) : ?>
			<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		<?php else : ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php endif; ?>
	</header>
	<?php endif; ?>

	<?php // _s_post_thumbnail(); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>

	<footer class="entry-footer">

		<span class="entry-meta">
			<?php _s_posted_on(); ?>
		</span>

		<?php spacemonkey_tags(); ?>
		
	</footer>
</article>

<?php if ( ! is_single() ) : ?>
	<hr>
<?php endif; ?>