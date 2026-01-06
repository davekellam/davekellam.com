<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="elsewhere">
			<ul>
				<li><a href="https://bsky.app/profile/davekellam.com"><?php echo get_svg( 'bluesky' ); ?></a></li>
				<li><a href="https://www.flickr.com/photos/davekellam"><?php echo get_svg( 'flickr' ); ?></a></li>
				<li><a href="https://davekellam.tumblr.com"><?php echo get_svg( 'tumblr' ); ?></a></li>
				<li><a href="https://github.com/davekellam"><?php echo get_svg( 'github' ); ?></a></li>
				<li><a href="https://www.last.fm/user/eightface"><?php echo get_svg( 'lastfm' ); ?></a></li>
			</ul>
		</div>

		<div class="site-info">
			<p>
				&copy;1981&ndash;<?php echo date( 'Y' ); ?> &middot; 
				<a href="<?php echo esc_url( home_url( '/colophon/' ) ); ?>">Colophon</a> &middot;
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a> &middot;
				<a href="<?php echo esc_url( home_url( '/feed/' ) ); ?>">RSS</a>
			</p>
		</div>

	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
