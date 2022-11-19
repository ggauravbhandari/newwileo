<?php
class WPF_Views_Edit_Entry_Prefill_Address extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'address' ;


	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		$entry_fields = json_decode( $entry->fields, true );
		$field_id = $field['id'];
		$properties['inputs'][ 'address1' ]['attr']['value']  = $entry_fields[$field_id]['address1'];
		$properties['inputs'][ 'address2' ]['attr']['value']  = $entry_fields[$field_id]['address2'];
		$properties['inputs'][ 'city' ]['attr']['value']  = $entry_fields[$field_id]['city'];
		$properties['inputs'][ 'state' ]['attr']['value']  = $entry_fields[$field_id]['state'];
		$properties['inputs'][ 'postal' ]['attr']['value']  = $entry_fields[$field_id]['postal'];
		$properties['inputs'][ 'country' ]['attr']['value']  = $entry_fields[$field_id]['country'];

		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Address();