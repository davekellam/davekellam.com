<?php
/**
 * The template for displaying the footer.
 */

?>
<footer class="site-footer">
	<nav aria-labelledby="footer-menu" class="site-footer-menu">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'footer',
				'menu_id'        => 'footer-menu',
				'container'      => 'div',
				'menu_class'     => 'navbar-item',
			]
		);
		?>
	</nav>

	<div class="copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Dave Kellam.</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
