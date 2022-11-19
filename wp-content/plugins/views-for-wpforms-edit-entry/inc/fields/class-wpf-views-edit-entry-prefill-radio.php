<?php
class WPF_Views_Edit_Entry_Prefill_Radio extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'radio' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		$entry_fields = json_decode( $entry->fields, true );
		$field_id = $field['id'];
		$value = isset( $field['show_values'] ) ? $entry_fields[$field_id]['value_raw'] : $entry_fields[$field_id]['value'];
		$properties = $this->get_field_populated_single_property_value_normal_choices( $value, $properties, $field );
		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Radio();