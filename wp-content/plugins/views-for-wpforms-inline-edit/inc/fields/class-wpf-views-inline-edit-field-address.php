<?php
class WPF_Views_Inline_Edit_Field_Address extends  WPF_Views_Inline_Edit_Field{

	var $wpf_field_type = 'address';

	var $inline_edit_type = 'address';


	public function add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data ) {

		$wrapper_attributes['data-value'] = $this->_get_inline_edit_value( $field_id, $form_fields, $entry );
		$wrapper_attributes['data-fieldscheme'] = $form_fields[$field_id]['scheme'];


		$template = $this->get_field_html_template( $field_id, $field_object, $form_fields, $form_data );

		WPF_Views_Inline_Edit_Field::add_field_template( $field_type, $template, $form_id, $field_id );

		return parent::add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object , $form_fields, $form_data );

	}


	protected function _get_inline_edit_value( $field_id, $form_fields, $entry ) {
		$entry_fields = json_decode( $entry->fields, true );

		if (   isset( $entry_fields[$field_id] ) ) {
			$entry_field =  $entry_fields[$field_id];
			// var_dump( $form_fields[$field_id] );
			// var_dump( $entry_field ); die;
				$value = array(
					'address1'=>$entry_field['address1'],
					'address2' =>$entry_field['address2'],
					'city' =>$entry_field['city'],
					'state' =>$entry_field['state'],
					'postal' =>$entry_field['postal'],
					'country' =>$entry_field['country']
				);

		}

		return empty( $value ) ? '' : json_encode( $value );
	}
}
new WPF_Views_Inline_Edit_Field_Address();