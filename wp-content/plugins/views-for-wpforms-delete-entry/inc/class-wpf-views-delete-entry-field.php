<?php
if ( class_exists( 'WPF_Views_Field' ) ) {
	class WPF_Views_Field_Delete_Entry extends WPF_Views_Field {
		public $field_type = 'deleteEntry' ;


		public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings, $view_Obj ) {
			if ( ! empty( $entry ) && $this->user_has_permission( $entry->user_id, $_view_settings  ) ) {
				$_view_field = $_view_settings->fields->{$_view_field_id};
				$link_text = isset( $_view_field->fieldSettings->linkText ) ?  $_view_field->fieldSettings->linkText : 'Delete Entry Link';
				return '<a href="#" data-wpfentry_id = "' . $entry->entry_id . '"  class=" views-delete-entry ' . $_view_field->fieldSettings->customClass . '">' . $link_text . '</a>';
			}
			return '';
		}

		function user_has_permission( $user_id, $view_settings ) {
			$logged_in_user_id = get_current_user_id();
			if ( ! empty( $logged_in_user_id ) ) {
				if ( ( $logged_in_user_id == $user_id ) || ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_delete_entries' )  ) ) {
					return true;
				}
			}
			return false;
		}
	}

	new WPF_Views_Field_Delete_Entry();
}
