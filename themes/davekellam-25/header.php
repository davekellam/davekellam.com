<?php
/**
 * The template for displaying the header.
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<noscript>
			<link rel='stylesheet' id='noscript-css' href='<?php echo esc_url( get_template_directory_uri() ); ?>/dist/css/noscript.min.css' type='text/css' media='all' /><?php // phpcs:ignore WordPress ?>
		</noscript>
		<?php wp_head(); ?>
	</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="header" class="header">
	<a href="#content" class="skip-to-main">Skip to main content</a>
	
	<a href="<?php echo esc_url( home_url() ); ?>" aria-label="Dave Kellam Home">
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/dist/images/dk-logo.svg" alt="Dave Kellam Logo" class="header-logo" />	
	</a>
	
	<nav aria-labelledby="menu-main" class="nav-menu-main">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'primary',
				'menu_id'        => 'menu-main',
				'container'      => 'div',
				'menu_class'     => 'header-nav',
			]
		);
		?>
	</nav>
</header>
