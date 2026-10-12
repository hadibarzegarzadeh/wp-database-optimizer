<?php
/*
Plugin Name: WP Database Optimizer
Description: Optimize and clean your WordPress database by removing revisions, auto-drafts, orphaned meta, and expired transients.
Version: 1.0.0
Requires at least: 6.0
Requires PHP: 7.4
Author: hadibarzegarzadeh
License: GPL-2.0-or-later
Text Domain: wp-database-optimizer
*/

defined('ABSPATH') || exit;

define('WP_DB_OPT_VERSION', '1.0.0');
define('WP_DB_OPT_PATH', plugin_dir_path(__FILE__));
define('WP_DB_OPT_URL', plugin_dir_url(__FILE__));

require_once WP_DB_OPT_PATH . 'includes/class-wp-db-opt-activator.php';
register_activation_hook(__FILE__, ['Wp_Db_Opt_Activator', 'activate']);
register_deactivation_hook(__FILE__, ['Wp_Db_Opt_Activator', 'deactivate']);

require_once WP_DB_OPT_PATH . 'includes/class-wp-db-opt-cleaner.php';
require_once WP_DB_OPT_PATH . 'includes/class-wp-db-opt-admin.php';
require_once WP_DB_OPT_PATH . 'includes/class-wp-db-opt-plugin.php';

add_action('plugins_loaded', ['Wp_Db_Opt_Plugin', 'init']);
