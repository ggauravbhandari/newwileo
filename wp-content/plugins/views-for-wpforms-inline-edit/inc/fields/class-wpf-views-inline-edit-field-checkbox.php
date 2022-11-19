<?php
class WPF_Views_Inline_Edit_Field_Checkbox extends  WPF_Views_Inline_Edit_Field{

	var $wpf_field_type = 'checkbox';

	var $inline_edit_type = 'checklist';


	public function add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data ) {

		$wrapper_attributes['data-value'] = $this->_get_inline_edit_value( $field_id, $form_fields, $entry );

		return parent::add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object , $form_fields, $form_data );

	}

	protected function _get_inline_edit_value( $field_id, $form_fields, $entry ) {
		$entry_fields = json_decode( $entry->fields, true );

		if (   isset( $entry_fields[$field_id] ) ) {
			$field_value_raw = $entry_fields[$field_id]['value_raw'];
			$field_value_raw = preg_split( '/\r\n|\r|\n/', $field_value_raw );
			$value = implode( ',', $field_value_raw );
		}

		return empty( $value ) ? '' : $value;
	}
}
new WPF_Views_Inline_Edit_Field_Checkbox();
