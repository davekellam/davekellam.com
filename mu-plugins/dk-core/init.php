<?php
/**
 * Setup the core plugin (like a named plugin file or theme's functions.php)
 *
 * This allows some things to persist across theme changes and force activation
 */

namespace DaveKellam\Core;

require_once WPMU_PLUGIN_DIR . '/dk-core/emoji.php';
require_once WPMU_PLUGIN_DIR . '/dk-core/helpers.php';
require_once WPMU_PLUGIN_DIR . '/dk-core/open-graph.php';
require_once WPMU_PLUGIN_DIR . '/dk-core/overrides.php';
require_once WPMU_PLUGIN_DIR . '/dk-core/post-types.php';
require_once WPMU_PLUGIN_DIR . '/dk-core/taxonomies.php';

// Composer autoload
require_once __DIR__ . '/vendor/autoload.php';
