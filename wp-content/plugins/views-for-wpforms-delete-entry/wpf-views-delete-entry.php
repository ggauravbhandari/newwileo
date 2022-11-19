<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Views for WPForms - Delete Entries
 * Plugin URI: https://formviewswp.com
 * Description: Allow users to Delete their entries from site frontend.
 * Version: 1.1.1
 * Author: WebHolics
 * Author URI: https://webholics.org
 *
 * Copyright 2021 Aman Saini.
 */
define( "WPF_VIEWS_DELETE_ENTRY_DIR_URL", WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ) );
define( "WPF_VIEWS_DELETE_ENTRY_URL", plugins_url() . "/" . basename( dirname( __FILE__ ) ) );

define( 'WPF_VIEWS_DELETE_ENTRY_VERSION',  '1.1.1' );
define( 'WPF_VIEWS_DELETE_ENTRY_PLUGIN_FILE', __FILE__ );

function wpf_views_delete_entry_include_files() {
	require_once WPF_VIEWS_DELETE_ENTRY_DIR_URL . '/inc/admin/class-wpf-views-delete-entry-enable.php';
	require_once WPF_VIEWS_DELETE_ENTRY_DIR_URL . '/inc/admin/updater/license.php';
	require_once WPF_VIEWS_DELETE_ENTRY_DIR_URL . '/inc/admin/class-wpf-views-delete-entry-settings.php';
	require_once WPF_VIEWS_DELETE_ENTRY_DIR_URL . '/inc/admin/class-wpf-views-delete-entry-db.php';
	require_once WPF_VIEWS_DELETE_ENTRY_DIR_URL . '/inc/class-wpf-views-delete-entry-field.php';
	require_once WPF_VIEWS_DELETE_ENTRY_DIR_URL . '/inc/class-wpf-views-delete-entry.php';

}

add_action( 'plugins_loaded', 'wpf_views_delete_entry_include_files', 15 );