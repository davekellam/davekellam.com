<?php
/**
 * The template for displaying single videos.
 *
 * @package Quarter
 */

get_header();
the_post();

$video_url = get_post_meta( get_the_ID(), 'video_url', true );
$video_id  = '';

if ( $video_url ) {
	preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches );
	if ( ! empty( $matches[1] ) ) {
		$video_id = $matches[1];
	}
}
?>

<main class="site-main" id="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<?php if ( $video_id ) : ?>
			<div class="entry-video">
				<iframe
					width="560"
					height="315"
					src="<?php echo esc_url( 'https://www.youtube-nocookie.com/embed/' . $video_id ); ?>"
					frameborder="0"
				></iframe>
			</div>
		<?php endif; ?>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php quarter_entry_footer(); ?>
	</article>
</main>

<?php
get_footer();
