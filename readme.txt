=== WP Database Optimizer ===
Contributors: hadibarzegarzadeh
Tags: database, optimize, clean, revisions, transients
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Optimize and clean your WordPress database by removing revisions, auto-drafts, orphaned meta, and expired transients.

== Description ==

Keep your WordPress site running fast and lean with WP Database Optimizer. This plugin provides a simple dashboard to track down and remove unnecessary data from your database. You can clean up old post revisions, auto-drafts, orphaned post meta, and expired transients with just a click. The plugin also features automatic scheduling so you don't even have to remember to do it yourself!

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-database-optimizer` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to the DB Optimizer dashboard to review and clean your database.

== Frequently Asked Questions ==

= Is this safe for my database? =
Yes, it only removes data that WordPress defines as temporary or safely disposable (like revisions or expired transients). However, it is always recommended to backup your database before running any cleanup tools.

= Can I automate the cleanups? =
Yes! Navigate to the Settings tab under DB Optimizer to configure automatic daily cleanups.

== Changelog ==

= 1.0.0 =
* Initial release.
