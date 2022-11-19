<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Views for WPForms - Approve Entries
 * Plugin URI: https://nfviews.com
 * Description: Approve WPForms Entries before you can show them in your View.
 * Version: 1.2
 * Author: Webholics
 * Author URI: https://webholics.org
 *
 * Copyright 2020 Aman Saini.
 */
define( "WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL", WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ) );

define( 'WPF_VIEWS_APPROVE_SUBMISSIONS_VERSION',  '1.2' );
define( 'WPF_VIEWS_APPROVE_SUBMISSIONS_PLUGIN_FILE', __FILE__ );
function wpf_views_approve_entries_include_files() {
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/admin/updater/license.php';
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/admin/class-wpf-approve-entries-enable.php';
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/admin/class-wpf-approve-entries-metabox.php';
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/admin/class-wpf-approve-entries-settings.php';
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/fields/class-wpf-views-approval-status.php';
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/fields/class-wpf-views-approve-entries.php';
	require_once WPF_VIEWS_APPROVE_SUBMISSIONS_DIR_URL . '/inc/wpf-approve-entries-query.php';

}

add_action( 'plugins_loaded', 'wpf_views_approve_entries_include_files', 15 );

/**
 * Proper way to enqueue scripts and styles.
 */
function wpf_views_approve_entries_scripts() {
    wp_enqueue_script( 'wpf_approve_entries', plugins_url() . "/" . basename( dirname( __FILE__ ) ) . '/js/script.js', array('jquery') );
		wp_localize_script( 'wpf_approve_entries', 'wpf_approve_entries',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);
}
add_action( 'wp_enqueue_scripts', 'wpf_views_approve_entries_scripts' );

function wpf_views_get_approval_status( $entry_id ) {
	global $wpdb;
	$entry_meta_table = WPForms_Views_Common::get_entry_meta_table_name();
	$results = $wpdb->get_results( "SELECT * FROM {$entry_meta_table} where `entry_id`={$entry_id} && `type`='approve'" );
	$status = 0;

	if ( ! empty( $results ) && is_array( $results ) ) {
		if ( $results[0]->data == '1' ) {
			$status = 1;
		}

	}
	return $status;
}
