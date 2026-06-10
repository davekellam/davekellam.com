<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="site-header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-name" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'quarter' ); ?>">
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => false,
				]
			);
			?>
		</nav>
	</div>
</header>
