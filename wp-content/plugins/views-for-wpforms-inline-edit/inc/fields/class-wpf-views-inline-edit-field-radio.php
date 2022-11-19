<?php
class WPF_Views_Inline_Edit_Field_Radio extends  WPF_Views_Inline_Edit_Field{

	var $wpf_field_type = 'radio';

	var $inline_edit_type = 'radiolist';

	var $add_value_to_wrapper = true;

	public function add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data ) {
		// var_dump($field_value); die;
		$template = $this->get_field_html_template( $field_id, $field_object, $form_fields, $form_data );

		WPF_Views_Inline_Edit_Field::add_field_template( $field_type, $template, $form_id, $field_id );

		return parent::add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object , $form_fields, $form_data );

	}


}
new WPF_Views_Inline_Edit_Field_Radio();