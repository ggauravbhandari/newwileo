<?php

if ( class_exists( 'WPF_Views_Field' ) ) {

	class WPF_Views_Edit_Entry_Link extends WPF_Views_Field {

		public $field_type = 'editEntry' ;

		public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings, $view_Obj ) {

			$_view_field = $_view_settings->fields->{$_view_field_id};
			if ( ! empty( $entry ) && $this->user_has_permission( $entry->user_id, $_view_settings  ) ) {

			if ( isset( $_view_settings->viewSettings->editEntries ) ) {
				$edit_page_url = $_view_settings->viewSettings->editEntries->editPage;
				$edit_page_url = add_query_arg( 'edit_wpfentry', 'true', $edit_page_url );
				$edit_page_url = add_query_arg( 'wpfentry_id', $entry->entry_id, $edit_page_url );
				$link_text = isset( $_view_field->fieldSettings->linkText ) ? $_view_field->fieldSettings->linkText : 'Edit Entry';
				$field_value = '<a href="' . esc_url_raw( $edit_page_url ) . '" class="' . $_view_field->fieldSettings->customClass . '">' . $link_text . '</a>';
			} else if ( $_view_field->formFieldId == 'deleteEntry' ) {
				$field_value = '<a class="' . $_view_field->fieldSettings->customClass . '">' . $_view_field->fieldSettings->linkText . '</a>';
			}
		}
		return $field_value;

		}

		function user_has_permission( $user_id, $view_settings ) {
		$logged_in_user_id = get_current_user_id();
		if ( ! empty( $logged_in_user_id ) ) {
			$admin_allowed = ! empty( $view_settings->viewSettings->editEntries->allowAdminToEdit ) ? $view_settings->viewSettings->editEntries->allowAdminToEdit: false;
			//var_dump($logged_in_user_id); die;
			if ( ( $logged_in_user_id == $user_id ) || ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_edit_entries' )  ) ) {
				return true;
			}
		}
		return false;
	}

	}
	new WPF_Views_Edit_Entry_Link();
}
