<?php
/**
 * Quarter theme bootstrap
 *
 * @package Quarter
 */

define( 'QUARTER_VERSION', '1.0.0' );
define( 'QUARTER_PATH', get_template_directory() . '/' );
define( 'QUARTER_URL', get_template_directory_uri() );
define( 'QUARTER_INC', QUARTER_PATH . 'includes/' );

require_once QUARTER_INC . 'init.php';
require_once QUARTER_INC . 'styles.php';
require_once QUARTER_INC . 'scripts.php';
require_once QUARTER_INC . 'template-tags.php';
