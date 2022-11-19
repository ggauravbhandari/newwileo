<?php
class WPF_Views_Field_Checkbox extends WPF_Views_Field {

	public $field_type = 'checkbox' ;


	public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings, $view_Obj ) {
		$_view_field = $_view_settings->fields->{$_view_field_id};
		$field_id = $_view_field->formFieldId;
		$entry_fields = json_decode( $entry->fields, true );
		if (   isset( $entry_fields[$field_id] ) ) {
			$field_value_raw = $entry_fields[$field_id]['value'];
			$field_value_raw = preg_split( '/\r\n|\r|\n/', $field_value_raw );
			$field_value = implode( ',', $field_value_raw );
		}

		return $field_value;
	}


}
new WPF_Views_Field_Checkbox();
