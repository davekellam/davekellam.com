<?php
/**
 * Theme constants and file structure to include
 */

// Useful global constants.
define( 'DAVEKELLAM_VERSION', '25.12.18' );
define( 'DAVEKELLAM_TEMPLATE_URL', get_template_directory_uri() );
define( 'DAVEKELLAM_PATH', get_template_directory() . '/' );
define( 'DAVEKELLAM_INC', DAVEKELLAM_PATH . 'includes/' );

// Include Core and additional functions
require_once DAVEKELLAM_INC . 'init.php';
require_once DAVEKELLAM_INC . 'overrides.php';
require_once DAVEKELLAM_INC . 'scripts.php';
require_once DAVEKELLAM_INC . 'styles.php';
require_once DAVEKELLAM_INC . 'template-tags.php';
