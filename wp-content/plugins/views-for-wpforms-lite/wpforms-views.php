<?php
/*
 * Plugin Name: Views for WPForms Lite
 * Plugin URI: https://formviewswp.com/
 * Description: Display WPForms Entries in frontend.
 * Version: 2.7.4
 * Author: WebHolics
 * Author URI: https://formviewswp.com/
 * Text Domain: views-for-wpforms-lite
 *
 * Copyright 2022.
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( "WPFORMS_VIEWS_URL_LITE", plugins_url() . "/" . basename( dirname( __FILE__ ) ) );
define( "WPFORMS_VIEWS_DIR_URL_LITE", WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ) );

function wpforms_views_lite_activate() {
	if ( is_plugin_active( 'views-for-wpforms/wpforms-views.php' ) ) {
		deactivate_plugins( 'views-for-wpforms/wpforms-views.php' );
	}
}
register_activation_hook( __FILE__, 'wpforms_views_lite_activate' );


add_action( 'plugins_loaded', 'wpforms_views_include_files' );

function wpforms_views_include_files() {
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/helpers.php';

	//Backend
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/admin/class-wpforms-views-posttype.php';
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/admin/class-wpforms-views-metabox.php';
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/admin/class-wpforms-views-ajax.php';
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/admin/review/class-wpforms-views-review.php';
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/admin/class-wpforms-views-upgrade-to-pro-page.php';

	//Frontend
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/class-wpforms-views-common.php';
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/pagination.php';
	require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/class-wpforms-views-shortcode.php';
}


add_action( 'admin_enqueue_scripts', 'wpforms_views_admin_scripts' );

add_action( 'wp_enqueue_scripts', 'wpforms_views_frontend_scripts' );

function wpforms_views_admin_scripts( $hook ) {
	global $post;
	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'wpforms-views' === $post->post_type ) {

			wp_enqueue_style( 'font-awesome', WPFORMS_VIEWS_URL_LITE . '/assets/css/font-awesome.css' );
			wp_enqueue_style( 'pure-css', WPFORMS_VIEWS_URL_LITE . '/assets/css/pure-min.css' );
			wp_enqueue_style( 'pure-grid-css', WPFORMS_VIEWS_URL_LITE . '/assets/css/grids-responsive-min.css' );
			wp_enqueue_style( 'wpforms-views-admin', WPFORMS_VIEWS_URL_LITE . '/assets/css/wpforms-views-admin.css' );


			$js_dir    = WPFORMS_VIEWS_DIR_URL_LITE . '/build/static/js';
			$js_files = array_diff( scandir( $js_dir ), array( '..', '.' ) );
			$count = 0;
			foreach ( $js_files as $js_file ) {
				if ( strpos( $js_file , '.js.map'  )  === false  ) {
					$js_file_name = $js_file;
					wp_enqueue_script( 'wpforms_views_script' . $count, WPFORMS_VIEWS_URL_LITE . '/build/static/js/' . $js_file_name, array( 'jquery' ), '', true );
					$count++;
					// wp_localize_script( 'react_grid_script'.$count, 'formData' , $form_data );
				}
			}

			$css_dir    = WPFORMS_VIEWS_DIR_URL_LITE . '/build/static/css';
			$css_files = array_diff( scandir( $css_dir ), array( '..', '.' ) );

			foreach ( $css_files as $css_file ) {
				if ( strpos( $css_file , '.css.map'  ) === false ) {
					$css_file_name = $css_file;
				}
			}

			wp_enqueue_style( 'wpforms_views_style', WPFORMS_VIEWS_URL_LITE . '/build/static/css/' . $css_file_name );
		}
	}
}


function wpforms_views_frontend_scripts() {
	wp_enqueue_style( 'pure-css', WPFORMS_VIEWS_URL_LITE . '/assets/css/pure-min.css' );
	wp_enqueue_style( 'pure-grid-css', WPFORMS_VIEWS_URL_LITE . '/assets/css/grids-responsive-min.css' );
	wp_enqueue_style( 'wpforms-views-front', WPFORMS_VIEWS_URL_LITE . '/assets/css/wpforms-views-display.css' );
}
