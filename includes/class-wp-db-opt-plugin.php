<?php
defined('ABSPATH') || exit;

class Wp_Db_Opt_Plugin {
    public static function init() {
        if (is_admin()) {
            Wp_Db_Opt_Admin::init();
        }
        
        add_action('wp_db_opt_daily_cleanup', [self::class, 'run_scheduled_cleanup']);
    }

    public static function run_scheduled_cleanup() {
        $options = get_option('wp_db_opt_settings', []);
        if (empty($options['enable_auto_cleanup'])) {
            return;
        }

        $cleaner = new Wp_Db_Opt_Cleaner();
        
        if (!empty($options['clean_revisions'])) {
            $cleaner->clean_revisions();
        }
        if (!empty($options['clean_auto_drafts'])) {
            $cleaner->clean_auto_drafts();
        }
        if (!empty($options['clean_transients'])) {
            $cleaner->clean_expired_transients();
        }
        if (!empty($options['clean_orphaned_postmeta'])) {
            $cleaner->clean_orphaned_postmeta();
        }
    }
}
