<?php
defined('ABSPATH') || exit;
if (!current_user_can('manage_options')) exit;

require_once WP_DB_OPT_PATH . 'includes/class-wp-db-opt-list-table.php';

$list_table = new Wp_Db_Opt_List_Table();
$list_table->prepare_items();
?>
<div class="wrap">
    <h1><?php esc_html_e('Cleanup Logs', 'wp-database-optimizer'); ?></h1>
    <form method="get">
        <input type="hidden" name="page" value="wp-db-opt-logs" />
        <?php $list_table->display(); ?>
    </form>
</div>
