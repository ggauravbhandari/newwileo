<?php
class WPF_Views_Edit_Entry_Prefill_Name extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'name' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		if ( $field['format'] !== 'simple' ) {
			$entry_fields = json_decode( $entry->fields, true );
			$field_id = $field['id'];
			$properties['inputs'][ 'first' ]['attr']['value'] = $entry_fields[$field_id]['first'];
			$properties['inputs'][ 'middle' ]['attr']['value'] = $entry_fields[$field_id]['middle'];
			$properties['inputs'][ 'last' ]['attr']['value'] = $entry_fields[$field_id]['last'];
		}
		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Name();