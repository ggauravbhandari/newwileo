<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Views for WPForms - Inline Editing
 * Plugin URI: https://formviewswp.com
 * Description: Allow users to Edit their entries from site frontend.
 * Version: 1.2.7
 * Author: WebHolics
 * Author URI: https://formviewswp.com
 *
 * Copyright 2022 Aman Saini.
 */
define( "WPF_VIEWS_INLINE_EDIT_DIR_URL", WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ) );
define( "WPF_VIEWS_INLINE_EDIT_URL", plugins_url() . "/" . basename( dirname( __FILE__ ) ) );

define( 'WPF_VIEWS_INLINE_EDIT_VERSION',  '1.2.7' );
define( 'WPF_VIEWS_INLINE_EDIT_PLUGIN_FILE', __FILE__ );

add_action( 'plugins_loaded', 'wpf_views_inine_edit_include_files', 20 );
function wpf_views_inine_edit_include_files(){
	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/admin/class-wpf-views-inline-edit-enable.php';
	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/admin/updater/license.php';
	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/admin/class-wpf-views-inline-edit-db.php';
	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/admin/class-wpf-views-inline-edit-ajax.php';
	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/admin/class-wpf-views-inline-edit-settings.php';
	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/class-wpf-views-user-registration-integration.php';

	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/class-wpf-views-inline-edit-view.php';

	require_once WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/fields/class-wpf-views-inline-edit-field.php';
		// Load all field files automatically
		foreach ( glob( WPF_VIEWS_INLINE_EDIT_DIR_URL . '/inc/fields/class-wpf-views-inline-edit-field*.php' ) as $inline_field_filename ) {
			include_once( $inline_field_filename );
		}
}