<?php
class WPF_Views_Edit_Entry_Prefill_Payment_Checkbox extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'payment-checkbox' ;


	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		$entry_fields = json_decode( $entry->fields, true );
		$field_id = $field['id'];
		if ( ! empty( $field['choices'] ) && is_array( $field['choices'] ) ) {
			if ( isset( $field['show_values'] ) ) {
				$values_array = explode( ",", $entry_fields[$field_id]['value_raw'] );
			}else {
				$values_array = explode( "\n", $entry_fields[$field_id]['value_choice'] );
			}
			foreach ( $values_array as $value ) {
				$properties = $this->get_field_populated_single_property_value_normal_choices( $value, $properties, $field );
			}
		}

		return  $properties;

	}


}
new WPF_Views_Edit_Entry_Prefill_Payment_Checkbox();
