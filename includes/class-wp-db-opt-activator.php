<?php
defined('ABSPATH') || exit;

class Wp_Db_Opt_Activator {
    public static function activate() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'db_opt_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            action_type varchar(50) NOT NULL,
            items_cleaned int(11) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        if (!wp_next_scheduled('wp_db_opt_daily_cleanup')) {
            wp_schedule_event(time(), 'daily', 'wp_db_opt_daily_cleanup');
        }

        $default_settings = [
            'enable_auto_cleanup' => 0,
            'clean_revisions' => 1,
            'clean_auto_drafts' => 1,
            'clean_transients' => 1,
            'clean_orphaned_postmeta' => 0
        ];
        add_option('wp_db_opt_settings', $default_settings);
    }

    public static function deactivate() {
        wp_clear_scheduled_hook('wp_db_opt_daily_cleanup');
    }
}
