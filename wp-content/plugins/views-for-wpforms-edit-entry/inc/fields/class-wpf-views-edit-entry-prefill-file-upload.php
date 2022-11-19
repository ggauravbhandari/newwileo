<?php
class WPF_Views_Edit_Entry_Prefill_File_Upload extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'file-upload' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		// remove required validation.
		$properties['inputs']['primary']['required'] = false;
		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_File_Upload();
