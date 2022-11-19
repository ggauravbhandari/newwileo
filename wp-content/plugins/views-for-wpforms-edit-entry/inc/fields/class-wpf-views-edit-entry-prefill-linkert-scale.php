<?php
class WPF_Views_Edit_Entry_Prefill_Likert_Scale extends WPF_Views_Edit_Entry_Prefill_Fields {

	public $field_type = 'likert_scale' ;

	function prefill_value_by_field(  $properties, $field_saved_value, $field, $entry, $form_data ) {
		$entry_fields = json_decode( $entry->fields, true );
		$field_id = $field['id'];
		if ( ! empty( $entry_fields[$field_id]['value_raw'] ) && is_array( $entry_fields[$field_id]['value_raw'] ) ) {
			foreach ( $entry_fields[$field_id]['value_raw'] as $row_id => $col_id ) {
				$input = 'r' . $row_id . '_' . 'c' . $col_id;
				$properties['inputs'][ $input ]['attr']['checked'] = '1';
			}
		}

		return  $properties;

	}

}
new WPF_Views_Edit_Entry_Prefill_Likert_Scale();
