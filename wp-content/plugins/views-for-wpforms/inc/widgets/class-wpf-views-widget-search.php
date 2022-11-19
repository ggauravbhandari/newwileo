<?php
class WPF_Views_Widget_Search extends WPF_Views_Widget {

	public $widget_type = 'search' ;


	public function get_widget_html( $html, $_view_field, $_view_settings, $view_Obj ) {


		$search_form_layout = $_view_field->fieldSettings->search->layout;
		$clearButton = $_view_field->fieldSettings->search->clearButton;
		$html = '<form action="" method="get" class="wpforms-view-search-form ' . $search_form_layout . '">';
		if ( ! empty( $_view_field->fieldSettings->search->fields ) ) {
			$search_fields = $_view_field->fieldSettings->search->fields;
			$form_fields = wpforms_get_form_fields( $_view_settings->formId );
			foreach ( $search_fields as $search_field ) {
				$html .= '<div class="search-form-field">';
				$html .= '<div><label>' . $search_field->label . '</label></div>';

				if ( isset( $form_fields[$search_field->fieldId]['type'] ) ) {
					$field_type = $form_fields[$search_field->fieldId]['type'] ;
				}else {
					$field_type = $search_field->fieldId;
				}
				//echo $field_type . '=====' . $field_type . '<br/>';
				// echo '<pre>';
				// print_r( $form_fields );


				switch ( $field_type ) {
				case 'textbox':
				case 'text':
				case 'email':
				case 'address':
				case 'name':
				case 'phone':
				case 'hidden':
				case 'number':
				case 'starrating':
				case 'submission_id':
				case 'entryId':
				case 'textarea':
				case 'all_fields':
					$value = '';
					// check if user has searched already then prefill search field
					if ( isset( $_GET['search_fields'] ) && ! empty( $_GET['search_fields'][$search_field->fieldId] ) ) {
						$value = $_GET['search_fields'][$search_field->fieldId];
					}
					$value = apply_filters( 'wpf_views_search_field_value', $value, $search_field, $_view_settings, $view_Obj );
					$html .= '<input type="text" value="' . esc_attr( $value ) . '" name="search_fields[' . $search_field->fieldId . ']" />';
					break;

				case 'checkbox':
				case 'radio':
				case 'multiselect':
				case 'select':
				case 'state':
					$options = $form_fields[$search_field->fieldId]['choices'];
					$value = '';
					if ( isset( $_GET['search_fields'] ) && ! empty( $_GET['search_fields'][$search_field->fieldId] ) ) {
						$value = $_GET['search_fields'][$search_field->fieldId];
					}
					$value = apply_filters( 'wpf_views_search_field_value', $value, $search_field, $_view_settings, $view_Obj );
					$html .= '<select name="search_fields[' . $search_field->fieldId . ']" >';
					$html .= '<option value="">' . __( 'All', 'wpforms-views' ) . '</option>';
					foreach ( $options as $option ) {
						$selected = selected( $option['label'], $value, false );
						$html .= '<option ' . $selected . ' value="' . $option['label'] . '">' . $option['label'] . '</option>';
					}
					$html .= '</select>';
					//echo '<pre>';print_r( $model->get_settings() );
					break;
				case 'entryDate':
				case 'date-time':
					$value = '';
					// check if user has searched already then prefill search field
					if ( isset( $_GET['search_fields'] ) && ! empty( $_GET['view_id'] ) &&  ( sanitize_text_field( $_GET['view_id'] ) == $view_Obj->view_id ) && ! empty( $_GET['search_fields'][$search_field->fieldId] ) ) {
						$value = $_GET['search_fields'][$search_field->fieldId];
					}
					$input_class = 'view-search-date-single';
					if ( $search_field->inputType === 'dateRange' ) {
						$input_class = 'view-search-date-range';
					}
					$html .= '<input type="text" class="' . $input_class . '" value="' . esc_attr( $value ) . '" name="search_fields[' . $search_field->fieldId . ']" />';
					break;
				}
				$html .= '</div>';
			}

		}
		$html .= '<input type="hidden" name="view_id" value="' . $view_Obj->view_id . '">';
		$html .= '<input type="submit" value="' . __( 'Search', 'wpforms-views' ) . '">';

		if ( ! empty( $clearButton ) ) {
			$html .= '<input type="button" value="' . __( 'Clear', 'wpforms-views' ) . '"  onClick="wpf_views_clearForm(this.form);return false;" class="view-clr-btn">';
		}
		$html .= '</form>';
		return $html;
	}
}
new WPF_Views_Widget_Search();
