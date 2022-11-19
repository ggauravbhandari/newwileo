<?php
class WPF_Views_Edit_Entry_Prefill_Rating extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type ='rating' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		$properties['inputs'][ 'primary' ]['rating']['default'] = (int) $field_saved_value;
		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Rating();