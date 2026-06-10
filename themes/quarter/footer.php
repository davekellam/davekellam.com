<?php
/**
 * The template for displaying the footer.
 */

?>
<footer class="site-footer">
	<div class="site-footer-inner">
		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'quarter' ); ?>">
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'footer',
					'menu_id'        => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => false,
				]
			);
			?>
		</nav>

		<small class="site-copyright">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</small>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
