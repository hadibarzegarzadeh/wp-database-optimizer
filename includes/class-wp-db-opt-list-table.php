<?php
defined('ABSPATH') || exit;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Wp_Db_Opt_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct([
            'singular' => 'log',
            'plural'   => 'logs',
            'ajax'     => false
        ]);
    }

    public function get_columns() {
        return [
            'id'            => __('ID', 'wp-database-optimizer'),
            'action_type'   => __('Action Type', 'wp-database-optimizer'),
            'items_cleaned' => __('Items Cleaned', 'wp-database-optimizer'),
            'created_at'    => __('Date', 'wp-database-optimizer')
        ];
    }

    public function get_sortable_columns() {
        return [
            'id'         => ['id', false],
            'created_at' => ['created_at', true]
        ];
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'db_opt_logs';

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id';
        $order = isset($_GET['order']) && strtolower($_GET['order']) === 'asc' ? 'ASC' : 'DESC';
        $valid_columns = ['id', 'action_type', 'items_cleaned', 'created_at'];
        if (!in_array($orderby, $valid_columns, true)) {
            $orderby = 'id';
        }

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $offset), ARRAY_A);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);

        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'items_cleaned':
                return absint($item[$column_name]);
            case 'action_type':
            case 'created_at':
                return esc_html($item[$column_name]);
            default:
                return '';
        }
    }
}
