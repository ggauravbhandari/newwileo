<?php

if ( class_exists( 'WPF_Views_Field' ) ) {

	class WPF_Views_Approve_Entries_Field extends WPF_Views_Field {

		public $field_type = 'approveEntries' ;

		public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings, $view_Obj ) {
			if ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_edit_entries' ) ) {

				$field_value = '<select data-formid="' . $_view_settings->formId . '" data-entryid="' . $entry->entry_id . '" class="wpf_approve_entries_select">
				<option value="1" ' . selected( wpf_views_get_approval_status( $entry->entry_id ), 1, false ) . '>Approve</option>
				<option value="0" ' . selected( wpf_views_get_approval_status( $entry->entry_id ), 0, false ) . '>Unapprove</option>
				</select>';

				return $field_value;
			}else {
				return '';
			}

		}



	}
	new WPF_Views_Approve_Entries_Field();
}
