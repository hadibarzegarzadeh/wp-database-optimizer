<?php
defined('ABSPATH') || exit;

class Wp_Db_Opt_Admin {

    public static function init() {
        add_action('admin_menu', [self::class, 'add_menus']);
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_post_wp_db_opt_clean', [self::class, 'handle_clean_action']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_assets']);
    }

    public static function add_menus() {
        add_menu_page(
            __('DB Optimizer', 'wp-database-optimizer'),
            __('DB Optimizer', 'wp-database-optimizer'),
            'manage_options',
            'wp-db-opt',
            [self::class, 'render_dashboard'],
            'dashicons-database',
            80
        );

        add_submenu_page(
            'wp-db-opt',
            __('Dashboard', 'wp-database-optimizer'),
            __('Dashboard', 'wp-database-optimizer'),
            'manage_options',
            'wp-db-opt',
            [self::class, 'render_dashboard']
        );

        add_submenu_page(
            'wp-db-opt',
            __('Logs', 'wp-database-optimizer'),
            __('Logs', 'wp-database-optimizer'),
            'manage_options',
            'wp-db-opt-logs',
            [self::class, 'render_logs']
        );

        add_submenu_page(
            'wp-db-opt',
            __('Settings', 'wp-database-optimizer'),
            __('Settings', 'wp-database-optimizer'),
            'manage_options',
            'wp-db-opt-settings',
            [self::class, 'render_settings']
        );
    }

    public static function enqueue_assets($hook_suffix) {
        if (strpos($hook_suffix, 'wp-db-opt') === false) {
            return;
        }

        wp_enqueue_style('wp-db-opt-admin-css', WP_DB_OPT_URL . 'admin/css/admin.css', [], WP_DB_OPT_VERSION);
        wp_enqueue_script('wp-db-opt-admin-js', WP_DB_OPT_URL . 'admin/js/admin.js', [], WP_DB_OPT_VERSION, true);

        wp_localize_script('wp-db-opt-admin-js', 'wpDbOptData', [
            'confirmMsg' => __('Are you sure you want to clean these items? This action cannot be undone.', 'wp-database-optimizer')
        ]);
    }

    public static function register_settings() {
        register_setting('wp_db_opt_settings_group', 'wp_db_opt_settings', [self::class, 'sanitize_settings']);

        add_settings_section('wp_db_opt_main_section', __('Auto Cleanup Settings', 'wp-database-optimizer'), '__return_false', 'wp-db-opt-settings');

        add_settings_field('enable_auto_cleanup', __('Enable Daily Auto Cleanup', 'wp-database-optimizer'), [self::class, 'render_checkbox_field'], 'wp-db-opt-settings', 'wp_db_opt_main_section', ['id' => 'enable_auto_cleanup']);
        add_settings_field('clean_revisions', __('Clean Revisions', 'wp-database-optimizer'), [self::class, 'render_checkbox_field'], 'wp-db-opt-settings', 'wp_db_opt_main_section', ['id' => 'clean_revisions']);
        add_settings_field('clean_auto_drafts', __('Clean Auto Drafts', 'wp-database-optimizer'), [self::class, 'render_checkbox_field'], 'wp-db-opt-settings', 'wp_db_opt_main_section', ['id' => 'clean_auto_drafts']);
        add_settings_field('clean_transients', __('Clean Expired Transients', 'wp-database-optimizer'), [self::class, 'render_checkbox_field'], 'wp-db-opt-settings', 'wp_db_opt_main_section', ['id' => 'clean_transients']);
        add_settings_field('clean_orphaned_postmeta', __('Clean Orphaned Post Meta', 'wp-database-optimizer'), [self::class, 'render_checkbox_field'], 'wp-db-opt-settings', 'wp_db_opt_main_section', ['id' => 'clean_orphaned_postmeta']);
    }

    public static function sanitize_settings($input) {
        $sanitized = [];
        $sanitized['enable_auto_cleanup'] = isset($input['enable_auto_cleanup']) ? 1 : 0;
        $sanitized['clean_revisions'] = isset($input['clean_revisions']) ? 1 : 0;
        $sanitized['clean_auto_drafts'] = isset($input['clean_auto_drafts']) ? 1 : 0;
        $sanitized['clean_transients'] = isset($input['clean_transients']) ? 1 : 0;
        $sanitized['clean_orphaned_postmeta'] = isset($input['clean_orphaned_postmeta']) ? 1 : 0;
        return $sanitized;
    }

    public static function render_checkbox_field($args) {
        $options = get_option('wp_db_opt_settings', []);
        $id = esc_attr($args['id']);
        $checked = !empty($options[$id]) ? 'checked' : '';
        echo "<input type='checkbox' name='wp_db_opt_settings[$id]' value='1' $checked />";
    }

    public static function handle_clean_action() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized', 'wp-database-optimizer'));
        }

        check_admin_referer('wp_db_opt_clean_action');

        $type = isset($_POST['clean_type']) ? sanitize_text_field($_POST['clean_type']) : '';
        $cleaner = new Wp_Db_Opt_Cleaner();
        $count = 0;

        switch ($type) {
            case 'revisions':
                $count = $cleaner->clean_revisions();
                break;
            case 'auto_drafts':
                $count = $cleaner->clean_auto_drafts();
                break;
            case 'transients':
                $count = $cleaner->clean_expired_transients();
                break;
            case 'postmeta':
                $count = $cleaner->clean_orphaned_postmeta();
                break;
        }

        wp_redirect(add_query_arg(['page' => 'wp-db-opt', 'cleaned' => $count], admin_url('admin.php')));
        exit;
    }

    public static function render_dashboard() {
        require_once WP_DB_OPT_PATH . 'admin/views/dashboard.php';
    }

    public static function render_logs() {
        require_once WP_DB_OPT_PATH . 'admin/views/logs.php';
    }

    public static function render_settings() {
        require_once WP_DB_OPT_PATH . 'admin/views/settings.php';
    }
}
