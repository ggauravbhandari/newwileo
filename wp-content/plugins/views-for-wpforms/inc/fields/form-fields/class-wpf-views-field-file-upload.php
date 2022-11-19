<?php
class WPF_Views_Field_File_Upload extends WPF_Views_Field {

	public $field_type = 'file-upload' ;

	public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings, $view_Obj ) {
		$_view_field = $_view_settings->fields->{$_view_field_id};
		$_view_fieldSettings = $_view_field->fieldSettings;

		$entry_fields = json_decode( $entry->fields, true );
		$field_id = $_view_field->formFieldId;

		// Process modern uploader.
		if ( ! empty( $entry_fields[$field_id ]['value_raw']  ) ) {
			$field_value_array = array();
			foreach  ( $entry_fields[$field_id ]['value_raw'] as $file ) {
				if ( empty( $file['value'] ) || empty( $file['file_original'] ) ) {
					return '';
				}
				$field_value_array[] = self::get_file_html( $_view_fieldSettings, $file );

			}
			if ( ! empty( $field_value_array ) ) {
				$field_value = implode( '<br>', $field_value_array );
			}

		}else {
			// Process classic uploader.
			$field_value = self::get_file_html( $_view_fieldSettings, $entry_fields[$field_id ] );
		}

		return $field_value;
	}

	public static function get_file_html( $_view_fieldSettings, $upload_field ) {

		if ( isset( $_view_fieldSettings->displayFileType ) && $_view_fieldSettings->displayFileType == 'Image' ) {
			$html = '<img class="wpforms-view-img" src="' . wp_strip_all_tags( $upload_field['value'] ) . '">';

			if ( isset( $_view_fieldSettings->onClickAction ) && $_view_fieldSettings->onClickAction == 'newTab' ) {
				$html = sprintf(
					'<a href="%s" rel="noopener" target="_blank">%s</a>',
					esc_url( $upload_field['value'] ),
					$html
				);
			}

		} else {

			$html = sprintf(
				'<a href="%s" rel="noopener" target="_blank">%s</a>',
				esc_url( $upload_field['value'] ),
				esc_html( $upload_field['file_original'] )
			);
		}
		return $html;
	}

}
new WPF_Views_Field_File_Upload();
