<?php
class WPF_Views_Inline_Edit_Field_Date_Time extends  WPF_Views_Inline_Edit_Field{

	var $wpf_field_type = 'date-time';

	var $inline_edit_type = 'date';
	var $add_value_to_wrapper = true;

	public function add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data ) {
		//$wrapper_attributes['data-value'] = $this->_get_inline_edit_value( $field_id, $form_fields, $entry );
		//$wrapper_attributes['data-fieldformat'] =$form_fields[$field_id]['format'];
		if( $form_fields[$field_id]['format'] == 'time'){
			$this->inline_edit_type ='select';
		}
		var_dump($form_fields[$field_id]);
		$template = $this->get_field_html_template( $field_id, $field_object, $form_fields, $form_data );
		WPF_Views_Inline_Edit_Field::add_field_template( $field_type, $template, $form_id, $field_id );

		return parent::add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object , $form_fields, $form_data );

	}



}
new WPF_Views_Inline_Edit_Field_Date_Time();