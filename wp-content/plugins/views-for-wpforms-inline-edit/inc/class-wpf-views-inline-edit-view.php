<?php

class WPF_VIEWS_INLINE_EDIT_View {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ), 100 );
		add_action( 'wpf_views_before_table_content',  array( $this, 'add_toogle_button' ), 10, 4 );
		add_action( 'wpf_views_before_loop_content',  array( $this, 'add_toogle_button' ), 10, 4 );
		add_filter( 'wpforms_view_table_classes',  array( $this, 'add_inline_edit_classname' ), 10, 3 );
			add_filter( 'wpforms_view_loop_classes',  array( $this, 'add_inline_edit_classname' ), 10, 3 );
		add_action( 'wpforms_views_after', array( $this, 'enqueue_scripts' ), 15, 3 );
	}
	function add_styles() {

		wp_enqueue_style( 'jqeryui', WPF_VIEWS_INLINE_EDIT_URL . '/assets/css/jquery-ui.min.css', false );
		wp_enqueue_style( 'wpf-fields-layout', WPF_VIEWS_INLINE_EDIT_URL . '/assets/css/wpf-fields-layout.css', false );
		wp_enqueue_style( 'wpf-inline-edit', WPF_VIEWS_INLINE_EDIT_URL . '/assets/css/wpf-inline-edit.css', false );
		wp_enqueue_style( 'editable', WPF_VIEWS_INLINE_EDIT_URL . '/assets/editable-js/css/jqueryui-editable.css', false );


	}

	function enqueue_scripts( $view_id, $view_content, $view_settings ) {
		$inlineEdit = isset( $view_settings->viewSettings->multipleentries->inlineEdit )?$view_settings->viewSettings->multipleentries->inlineEdit:false;

		$elementor_edit_mode = isset($_GET['action']) && $_GET['action'] === 'elementor' ? true: false;
		if ( ! empty( $inlineEdit ) && is_user_logged_in() && !$elementor_edit_mode ) {
			wp_dequeue_script( 'jquery-ui-core');
			wp_enqueue_script( 'wpf-inline-edit', WPF_VIEWS_INLINE_EDIT_URL . '/assets/js/wpf-inline-edit.js', array( 'jquery' ) );
			wp_enqueue_script( 'jqueryui', WPF_VIEWS_INLINE_EDIT_URL . '/assets/js/jquery-ui-1.11.0.min.js', array( 'jquery' ) );

			wp_enqueue_script( 'editable-js', WPF_VIEWS_INLINE_EDIT_URL . '/assets/editable-js/js/jqueryui-editable.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'editable-name', WPF_VIEWS_INLINE_EDIT_URL . '/assets/js/fields/name.js', array( 'jquery', 'editable-js' ) );
			wp_enqueue_script( 'editable-radio', WPF_VIEWS_INLINE_EDIT_URL . '/assets/js/fields/radio.js', array( 'jquery', 'editable-js' ) );
			wp_enqueue_script( 'editable-address', WPF_VIEWS_INLINE_EDIT_URL . '/assets/js/fields/address.js', array( 'jquery', 'editable-js' ) );

			$js_settings = array();
			$js_settings['url']           = admin_url( 'admin-ajax.php' );
			$js_settings['nonce']         = wp_create_nonce( 'wpf_inline_edit' );
			$js_settings['templates']     = WPF_Views_Inline_Edit_Field::get_field_templates();

			wp_localize_script( 'wpf-inline-edit', 'wpf_inline_edit', $js_settings );
		}
	}

	public function add_toogle_button( $content, $view_id, $view_settings ) {
		$inlineEdit = isset( $view_settings->viewSettings->multipleentries->inlineEdit )?$view_settings->viewSettings->multipleentries->inlineEdit:false;
		//if ( ! empty( $inlineEdit ) && ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_inline_edit' ) ) ) {
		if ( ! empty( $inlineEdit ) ) {
			$label = apply_filters('wpf/inline-edit/label', 'Enable Inline Edit');
			$content .= '<a href="#" data-status="disabled" class="inline-edit-enable">'.$label.'</a><div class="clearfix"></div>';
		}
		return $content;
	}

	public function add_inline_edit_classname( $classnames, $view_id, $view_settings ) {
		$inlineEdit = isset( $view_settings->viewSettings->multipleentries->inlineEdit )?$view_settings->viewSettings->multipleentries->inlineEdit:false;
		if ( ! empty( $inlineEdit ) ) {
			$classnames .= ' wpf-inline-edit-view';
		}

		return $classnames;
	}


}
new WPF_VIEWS_INLINE_EDIT_View();
