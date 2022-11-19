<?php
class WPF_Views_Inline_Edit_Field_Name extends  WPF_Views_Inline_Edit_Field{

	var $wpf_field_type = 'name';

	var $inline_edit_type = 'name';

	public function add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data ) {

		$wrapper_attributes['data-value'] = $this->_get_inline_edit_value( $field_id, $form_fields, $entry );
		$wrapper_attributes['data-fieldformat'] =$form_fields[$field_id]['format'];

		$template = $this->get_field_html_template( $field_id, $field_object, $form_fields, $form_data );

		WPF_Views_Inline_Edit_Field::add_field_template( $field_type, $template, $form_id, $field_id );

		return parent::add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object , $form_fields, $form_data );

	}

	protected function _get_inline_edit_value( $field_id, $form_fields, $entry ) {
		$entry_fields = json_decode( $entry->fields, true );

		if (   isset( $entry_fields[$field_id] ) ) {
			$entry_field =  $entry_fields[$field_id];
			switch ( $form_fields[$field_id]['format'] ) {
			case 'first-middle-last':
				$value = array( 'first'=>$entry_field['first'], 'middle' =>$entry_field['middle'], 'last' =>$entry_field['last'] );
				break;
			case 'first-last':
				$value = array( 'first'=>$entry_field['first'], 'last' =>$entry_field['last'] );
				break;
			default:
				$value = array( 'value'=>$entry_field['value'] );
				break;

			}
		}

		return empty( $value ) ? '' : json_encode( $value );
	}

}
new WPF_Views_Inline_Edit_Field_Name();
