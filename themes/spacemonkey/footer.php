<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="newsletter">
			<form style="background-color: #eeeeee; padding: 25px 15px 15px; margin-top: 20px; text-align: center;" action="https://tinyletter.com/davekellam" method="post" target="popupwindow">

				<input id="tlemail" style="width: 75%;" name="email" type="text" placeholder="email address" aria-label="Enter your email address" />

				<input name="embed" type="hidden" value="1" /><input type="submit" value="Subscribe" />

				<a href="https://tinyletter.com" target="_blank" rel="noopener">Newsletter powered by TinyLetter</a>

			</form>
		</div>
		<div class="site-info">
		<p>&copy;1998&ndash;<?php echo date( 'Y' ); ?> &middot; <a href="http://davekellam.com">Dave Kellam</a></p>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
