<?php
defined('ABSPATH') || exit;
if (!current_user_can('manage_options')) exit;

$cleaner = new Wp_Db_Opt_Cleaner();
$revisions = $cleaner->count_revisions();
$auto_drafts = $cleaner->count_auto_drafts();
$transients = $cleaner->count_expired_transients();
$postmeta = $cleaner->count_orphaned_postmeta();

$url = admin_url('admin-post.php');
?>
<div class="wrap wp-db-opt-wrap">
    <h1><?php esc_html_e('Database Optimizer Dashboard', 'wp-database-optimizer'); ?></h1>

    <?php if (isset($_GET['cleaned'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php printf(esc_html__('Successfully cleaned %d items.', 'wp-database-optimizer'), absint($_GET['cleaned'])); ?></p>
        </div>
    <?php endif; ?>

    <div class="wp-db-opt-cards">
        <div class="wp-db-opt-card <?php echo $revisions > 0 ? 'danger' : 'success'; ?>">
            <h3><?php esc_html_e('Post Revisions', 'wp-database-optimizer'); ?></h3>
            <div class="stat"><?php echo esc_html($revisions); ?></div>
            <?php if ($revisions > 0): ?>
                <form action="<?php echo esc_url($url); ?>" method="post">
                    <?php wp_nonce_field('wp_db_opt_clean_action'); ?>
                    <input type="hidden" name="action" value="wp_db_opt_clean">
                    <input type="hidden" name="clean_type" value="revisions">
                    <button type="submit" class="button button-primary wp-db-opt-clean-btn"><?php esc_html_e('Clean Revisions', 'wp-database-optimizer'); ?></button>
                </form>
            <?php endif; ?>
        </div>

        <div class="wp-db-opt-card <?php echo $auto_drafts > 0 ? 'danger' : 'success'; ?>">
            <h3><?php esc_html_e('Auto Drafts', 'wp-database-optimizer'); ?></h3>
            <div class="stat"><?php echo esc_html($auto_drafts); ?></div>
            <?php if ($auto_drafts > 0): ?>
                <form action="<?php echo esc_url($url); ?>" method="post">
                    <?php wp_nonce_field('wp_db_opt_clean_action'); ?>
                    <input type="hidden" name="action" value="wp_db_opt_clean">
                    <input type="hidden" name="clean_type" value="auto_drafts">
                    <button type="submit" class="button button-primary wp-db-opt-clean-btn"><?php esc_html_e('Clean Auto Drafts', 'wp-database-optimizer'); ?></button>
                </form>
            <?php endif; ?>
        </div>

        <div class="wp-db-opt-card <?php echo $transients > 0 ? 'danger' : 'success'; ?>">
            <h3><?php esc_html_e('Expired Transients', 'wp-database-optimizer'); ?></h3>
            <div class="stat"><?php echo esc_html($transients); ?></div>
            <?php if ($transients > 0): ?>
                <form action="<?php echo esc_url($url); ?>" method="post">
                    <?php wp_nonce_field('wp_db_opt_clean_action'); ?>
                    <input type="hidden" name="action" value="wp_db_opt_clean">
                    <input type="hidden" name="clean_type" value="transients">
                    <button type="submit" class="button button-primary wp-db-opt-clean-btn"><?php esc_html_e('Clean Transients', 'wp-database-optimizer'); ?></button>
                </form>
            <?php endif; ?>
        </div>

        <div class="wp-db-opt-card <?php echo $postmeta > 0 ? 'danger' : 'success'; ?>">
            <h3><?php esc_html_e('Orphaned Post Meta', 'wp-database-optimizer'); ?></h3>
            <div class="stat"><?php echo esc_html($postmeta); ?></div>
            <?php if ($postmeta > 0): ?>
                <form action="<?php echo esc_url($url); ?>" method="post">
                    <?php wp_nonce_field('wp_db_opt_clean_action'); ?>
                    <input type="hidden" name="action" value="wp_db_opt_clean">
                    <input type="hidden" name="clean_type" value="postmeta">
                    <button type="submit" class="button button-primary wp-db-opt-clean-btn"><?php esc_html_e('Clean Post Meta', 'wp-database-optimizer'); ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
