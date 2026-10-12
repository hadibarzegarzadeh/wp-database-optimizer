<?php
defined('ABSPATH') || exit;
if (!current_user_can('manage_options')) exit;
?>
<div class="wrap">
    <h1><?php esc_html_e('Optimizer Settings', 'wp-database-optimizer'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('wp_db_opt_settings_group');
        do_settings_sections('wp-db-opt-settings');
        submit_button();
        ?>
    </form>
</div>
