<?php
class WPF_Views_Field_DateTime extends WPF_Views_Field {

	public $field_type = 'date-time' ;

	public function get_filter_field_value( $value,  $field_id, $view_id, $form_id ) {
		$value  = WPForms_Views_MergeTags()->replace( $value );
		$form = wpforms()->form->get( $form_id );
		$form_data = wpforms_decode( $form->post_content );

		$date_format = $form_data['fields'][$field_id]['date_format'];
		$time_format = $form_data['fields'][$field_id]['time_format'];

		if ( $date_format == 'd/m/Y' ) {
			$value = str_replace( '/', '-', $value );
		}
		switch ( $form_data['fields'][$field_id]['format'] ) {
		case'date-time':
			$value = date( $date_format . ' ' . $time_format, strtotime( $value ) );
			break;
		case 'date':
			$value = date( $date_format, strtotime( $value ) );
			break;
		case 'time':
			$value = date( $time_format, strtotime( $value ) );
			break;
		}

		return $value;
	}

}
new WPF_Views_Field_DateTime();
