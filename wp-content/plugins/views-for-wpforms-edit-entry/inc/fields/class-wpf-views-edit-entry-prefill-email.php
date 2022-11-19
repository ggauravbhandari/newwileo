<?php
class WPF_Views_Edit_Entry_Prefill_Email extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'email' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		if (  ! empty( $field['confirmation'] ) ) {
			$properties['inputs'][ 'secondary' ]['attr']['value'] = $field_saved_value;
		}

		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Email();
