<?php
/**
 * The template for displaying 404 pages.
 *
 * @package Quarter
 */

get_header();
?>

<main class="site-main" id="main">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing found', 'quarter' ); ?></h1>
	</header>

	<div class="page-content">
		<p><?php esc_html_e( "This page doesn't exist or has moved. Try searching for it.", 'quarter' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</main>

<?php
get_footer();
