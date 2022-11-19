<?php
class WPF_Views_Edit_Entry_Prefill_Select extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'select' ;


	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		if ( ! empty( $field['multiple'] ) ) {
			$values_array = explode( "\n", $field_saved_value );
			foreach ( $values_array as $value ) {
				$properties = $this->get_field_populated_single_property_value_normal_choices( $value, $properties, $field );
			}

		}else{
		$properties = $this->get_field_populated_single_property_value_normal_choices( $field_saved_value, $properties, $field );
		}
		return  $properties;

	}
}
new WPF_Views_Edit_Entry_Prefill_Select();
