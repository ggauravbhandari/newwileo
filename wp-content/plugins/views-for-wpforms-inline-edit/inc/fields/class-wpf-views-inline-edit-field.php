<?php
class WPF_Views_Inline_Edit_Field {

	public $wpf_field_type;

	public $inline_edit_type = 'text';

	public $add_value_to_wrapper = false;

	private static $_field_templates = array();


	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add the filter to add the attributes
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	protected function add_hooks() {
		add_filter( "wpf-views/{$this->wpf_field_type}-value", array( $this, 'add_inline_edit_wrapper' ), 15, 5 );

		add_filter( "wpf-views-inline-edit/{$this->wpf_field_type}-wrapper-attributes", array(
				$this,
				'add_inline_edit_attributes',
			), 10, 9 );

	}

	public static function get_field_templates() {
		return self::$_field_templates;
	}

	public static function add_field_template( $type, $template = '', $form_id = null, $field_id = null ) {

		$template_name = $form_id && ( ! is_null( $field_id ) && ($field_id != '') ) ? "{$type}_{$form_id}_{$field_id}" : $type;
		if ( empty( self::$_field_templates[ $template_name ] ) ) {

			self::$_field_templates[ $template_name ] = $template;
		}
	}
	public function add_inline_edit_attributes( $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data ) {

		if ( $this->add_value_to_wrapper ) {
			if ( ! empty( $form_fields[$field_id]['dynamic_choices'] ) ) {
				$entry_fields = json_decode( $entry->fields, true );
				$wrapper_attributes['data-value'] = $entry_fields[$field_id]['value_raw'];
			}else {

				$wrapper_attributes['data-value'] = $field_value;

			}

		}

		$wrapper_attributes['data-type'] = $this->inline_edit_type;

		// Only try to enqueue if script is registered, preventing possible PHP warnings
		if ( wp_script_is( 'wpf-inline-edit-' . $this->inline_edit_type, 'registered' ) ) {
			wp_enqueue_script( 'wpf-inline-edit-' . $this->inline_edit_type );
		}

		return $wrapper_attributes;
	}

	public function get_field_html_template( $field_id, $field_object, $form_fields, $form_data ) {

		$form_fields[$field_id]['properties'] = wpforms()->frontend->get_field_properties( $form_fields[$field_id], $form_data );
		$form_fields[$field_id]['size'] = 'full';
		ob_start();
		wpforms()->frontend->field_container_open( $form_fields[$field_id], $form_data );

		$field_object->field_display( $form_fields[$field_id], $form_fields[$field_id], $form_data );
		echo '</div>';

		$template = ob_get_contents();
		ob_end_clean();
		return $template;
	}

	public function add_inline_edit_wrapper( $field_value, $_field_id, $entry, $view_settings, $obj ) {
		//	var_dump($field_value) ;//return $field_value;
		$field = $view_settings->fields->{$_field_id};
		$field_id = $field->formFieldId;
		if( empty($obj->form_fields)) return $field_value;
		$form_fields = $obj->form_fields;

		// Return if not Form field or field id now doesn't exist in form fields
		if ( ! is_numeric( $field_id ) || empty( $form_fields[$field_id] ) ) {
			return $field_value;
		}

		$inlineEdit = isset( $view_settings->viewSettings->multipleentries->inlineEdit )?$view_settings->viewSettings->multipleentries->inlineEdit:false;

		$field_type = $form_fields[$field_id]['type'];
		if ( ! empty( $inlineEdit ) && in_array( $field_type, $this->allowed_fields() ) && $this->user_has_permission( $entry->user_id ) ) {

			$view_id = $obj->view_id;
			$form_id = $view_settings->formId;
			$form_data = $obj->form_data;
			$field_object = $this->get_entries_edit_field_object( $form_fields[$field_id]['type'] );
			$source = '';

			switch ( $field_type ) {
			case 'checkbox':
				$source = $this->get_formatted_source( $form_fields[$field_id], $form_id, $form_data  );
				break;
			case 'radio':
				$source = $this->get_formatted_source( $form_fields[$field_id], $form_id, $form_data  );
				break;
			case 'select':
				$source = $this->get_formatted_source( $form_fields[$field_id], $form_id, $form_data  );
				break;
			}

			$wrapper_attributes = array(
				'id'           => str_replace( '.', '-', "wpf-views-inline-editable-{$entry->entry_id}-{$form_id}-{$field_id}" ),
				'class'        => 'wpf-views-inline-editable-value',
				'data-formid'  => $form_id,
				'data-entryid' => $entry->entry_id,
				'data-fieldid' => $field_id,
				'data-viewid' => $view_id,
				'data-form-field-type' =>$field_type
			);
			if ( ! empty( $source ) ) {
				$wrapper_attributes['data-source'] = $source;
			}

			$wrapper_attributes = apply_filters( "wpf-views-inline-edit/{$field_type}-wrapper-attributes", $wrapper_attributes, $field_value, $field_type, $field_id, $entry, $form_id, $field_object, $form_fields, $form_data );
			$tag_name = 'div';

			$atts_output = '';
			foreach ( $wrapper_attributes as $att => $att_value ) {
				$atts_output .= esc_attr( $att ) . '="' . esc_attr( $att_value ) . '" ';
			}
			return sprintf( '<%s %s>%s</%1$s>', $tag_name, $atts_output, $field_value );
		}

		return $field_value;
	}
	private function get_entries_edit_field_object( $type ) {
		$field_object = apply_filters( "wpforms_fields_get_field_object_{$type}", null );
		return $field_object;
	}

	private function user_has_permission( $user_id ) {
		$logged_in_user_id = get_current_user_id();
		if ( ( ! empty( $logged_in_user_id ) && ( $logged_in_user_id == $user_id ) ) || (WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_inline_edit') )   ) {
		//if ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_inline_edit')   ) {
			return true;
		}else {
			return false;
		}
	}
	private function allowed_fields() {
		$fields = array(
			'address',
			'email',
			'checkbox',
			'name',
			'number',
			'phone',
			'radio',
			'select',
			'text',
			'textarea',
			'url'
		);
		return $fields;
	}


	public function get_formatted_source( $field, $form_id, $form_data  ) {
		$source = array();
		// Field with Dynamic Choices
		if ( ! empty( $field['dynamic_choices'] ) ) {
			$dynamic  = wpforms_get_field_dynamic_choices( $field, $form_id, $form_data );
			if ( ! empty( $dynamic ) ) {
				foreach ( $dynamic as $choice ) {
					$source[] = array( 'text'=>$choice['label'], 'value' => $choice['value'] );
				}
				return json_encode( $source );
			}

		}else {
			$choice_value_key = isset( $field['show_values'] ) ? 'value' : 'label';
			foreach (  $field['choices'] as $choice ) {
				$source[] = array( 'text'=>$choice['label'], 'value' => $choice[$choice_value_key] );
			}
			return json_encode( $source );
		}
	}



}

new WPF_Views_Inline_Edit_Field();
