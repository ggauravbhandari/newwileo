<?php
class WPF_Views_Edit_Entry_Prefill_Date_Time extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'date-time' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		$entry_fields = json_decode( $entry->fields, true );
		$field_id = $field['id'];
		// Date Time Fields
		$field_format = ! empty( $field['format'] ) ? $field['format'] : 'date-time';
		$display_format = $field['date_type'];
		if ( $field_format == 'date' ) {
			$properties['inputs'][ 'date' ]['attr']['value']  =  $field_saved_value;
		}elseif ( $field_format == 'time' ) {
			$properties['inputs'][ 'time' ]['attr']['value']  =  $field_saved_value;
		}else {
			$properties['inputs'][ 'date' ]['attr']['value']  =  $entry_fields[$field_id]['date'];
			$properties['inputs'][ 'time' ]['attr']['value']  =  $entry_fields[$field_id]['time'];
		}
		// If date is shown as dropdown
		if (  $display_format === 'dropdown' ) {
			$properties['inputs']['date']['default'] = [
				'd' => gmdate( 'd', $entry_fields[$field_id]['unix'] ),
				'm' => gmdate( 'm', $entry_fields[$field_id]['unix'] ),
				'y' => gmdate( 'Y', $entry_fields[$field_id]['unix'] ),
			];

		}

		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Date_Time();
