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
				<li><a href="https://twitter.com/davekellam/"><?php echo get_svg( 'twitter' ); ?></a></li>
				<li><a href="https://pinboard.in/u:davekellam"><?php echo get_svg( 'pinboard' ); ?></a></li>
				<li><a href="https://instagram.com/davekellam"><?php echo get_svg( 'instagram' ); ?></a></li>
				<li><a href="https://davekellam.tumblr.com"><?php echo get_svg( 'tumblr' ); ?></a></li>
				<li><a href="https://micro.blog/davekellam"><?php echo get_svg( 'microblog' ); ?></a></li>
			</ul>
		</div>

		<div class="site-info">
			<p>
				&copy;1998&ndash;<?php echo date( 'Y' ); ?> &middot; 
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
