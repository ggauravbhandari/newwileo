<?php

if ( class_exists( 'WPF_Views_Field' ) ) {

	class WPF_Views_Approval_Status_Field extends WPF_Views_Field {

		public $field_type = 'approvalStatus' ;

		public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings, $view_Obj ) {
			if ( ! empty( $entry ) ) {

				if ( wpf_views_get_approval_status( $entry->entry_id ) ) {
					$field_value = 'Approved';
				}else {
					$field_value = 'Unapproved';
				}

				return $field_value;

			}
		}

	}
	new WPF_Views_Approval_Status_Field();
}
