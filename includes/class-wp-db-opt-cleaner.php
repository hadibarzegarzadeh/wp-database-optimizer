<?php
defined('ABSPATH') || exit;

class Wp_Db_Opt_Cleaner {
    
    public function count_revisions() {
        global $wpdb;
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'");
    }

    public function clean_revisions() {
        global $wpdb;
        $count = $this->count_revisions();
        if ($count > 0) {
            $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision'");
            $this->log_action('clean_revisions', $count);
        }
        return $count;
    }

    public function count_auto_drafts() {
        global $wpdb;
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'");
    }

    public function clean_auto_drafts() {
        global $wpdb;
        $count = $this->count_auto_drafts();
        if ($count > 0) {
            $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'");
            $this->log_action('clean_auto_drafts', $count);
        }
        return $count;
    }

    public function count_expired_transients() {
        global $wpdb;
        $now = time();
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE %s AND option_value < %d",
            '_transient_timeout_%',
            $now
        ));
    }

    public function clean_expired_transients() {
        global $wpdb;
        $now = time();
        $count = $this->count_expired_transients();
        
        if ($count > 0) {
            $wpdb->query($wpdb->prepare(
                "DELETE FROM $wpdb->options WHERE option_name LIKE %s AND option_value < %d",
                '_transient_timeout_%',
                $now
            ));
            $wpdb->query(
                "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%' AND option_name NOT IN (SELECT REPLACE(option_name, '_transient_timeout_', '_transient_') FROM (SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%') as t)"
            );
            $this->log_action('clean_expired_transients', $count);
        }
        return $count;
    }

    public function count_orphaned_postmeta() {
        global $wpdb;
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON pm.post_id = p.ID WHERE p.ID IS NULL");
    }

    public function clean_orphaned_postmeta() {
        global $wpdb;
        $count = $this->count_orphaned_postmeta();
        if ($count > 0) {
            $wpdb->query("DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON pm.post_id = p.ID WHERE p.ID IS NULL");
            $this->log_action('clean_orphaned_postmeta', $count);
        }
        return $count;
    }

    private function log_action($type, $count) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'db_opt_logs';
        $wpdb->insert(
            $table_name,
            [
                'action_type' => $type,
                'items_cleaned' => $count,
                'created_at' => current_time('mysql')
            ],
            ['%s', '%d', '%s']
        );
    }
}
