<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$table_name = $wpdb->prefix . 'db_opt_logs';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

delete_option('wp_db_opt_settings');

wp_clear_scheduled_hook('wp_db_opt_daily_cleanup');
