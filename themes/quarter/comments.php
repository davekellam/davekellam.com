<?php
/**
 * The template for displaying comments.
 *
 * @package Quarter
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			if ( 1 === $comment_count ) {
				printf(
					/* translators: %s: post title */
					esc_html__( 'One comment on &ldquo;%s&rdquo;', 'quarter' ),
					get_the_title() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					esc_html__( '%1$s comments on &ldquo;%2$s&rdquo;', 'quarter' ),
					number_format_i18n( $comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					get_the_title() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				[
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				]
			);
			?>
		</ol>

		<?php the_comments_pagination(); ?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'quarter' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</section>
