<?php

class WPF_Views_Delete_Entry {

	public function __construct() {

		add_action( 'wp_enqueue_scripts', [$this, 'add_scripts'] );
		add_action( 'wp_ajax_wpf_views_delete_entry', [$this, 'maybe_delete_entry'] );

	}

	public function add_scripts() {
		wp_enqueue_script( 'wpf_views_delete', WPF_VIEWS_DELETE_ENTRY_URL . '/assets/js/wpf-delete-entry.js', array( 'jquery' ) );
		wp_localize_script( 'wpf_views_delete', 'wpf_views_delete',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);

	}

	public function maybe_delete_entry() {

		if ( isset( $_POST['entryId'] ) ) {
			$entry_id = sanitize_text_field($_POST['entryId']);

			$result = WPF_Views_Delete_Entry_Db()->delete($entry_id);
			// var_dump($result);
			echo $result;
			wp_die();
		}

	}

}
new WPF_Views_Delete_Entry();
