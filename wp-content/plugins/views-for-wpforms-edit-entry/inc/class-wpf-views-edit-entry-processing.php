<?php

class WPF_Views_Edit_Entry_Processing {

	public function __construct() {

		add_action( 'wp_enqueue_scripts', [$this, 'edit_scripts'] );

		// Prefill Fields
		add_filter( 'wpforms_field_properties', array( $this, 'prefill_fields' ), 100, 3 );

		// Format Form data - make upload field not required.
		add_filter( 'wpforms_process_before_form_data', array( $this, 'format_form_data' ), 10, 2 );

		// Upload field
		add_action( 'wpforms_display_field_after', [$this, 'display_uploaded_files'], 2, 2 );

		// Add entry Id to hidden Field
		add_action( 'wpforms_display_submit_before', array( $this, 'add_entry_id_to_form' )  );
		// Disable Email Notification
		add_filter( 'wpforms_entry_email_process', array( $this, 'process_email' ), 50, 5 );
		add_action( 'wpforms_form_settings_notifications_single_after', array( $this, 'notification_settings' ), 10, 2 );

		// Update Entry
		add_filter( 'wpforms_entry_save', array( $this, 'update_entry' ), 10, 4 );
		// Update Post if reuired & using Post Submission Addon
		add_filter( 'wpforms_post_submissions_post_args', array( $this, 'update_post' ), 10, 3 );
		// Remove Adding Form Abandonemnt Entries if User is Editing Entries.
		add_action( 'wpforms_wp_footer', array( $this, 'dequeque_form_abandonment' ), 20 );

	}

	public function edit_scripts() {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_script( 'wpf_views_edit', WPF_VIEWS_EDIT_ENTRY_URL . '/assets/js/wpf-edit-entry.js', array( 'jquery' ) );
	}

	function prefill_fields( $properties, $field, $form_data ) {
		if ( $this->is_editing_entry() ) {
			$field_id = $field['id'];
			$field_type = $field['type'];
			$disallowed_fields = array( 'pagebreak', 'divider', 'html' );
			if ( in_array( $field_type, $disallowed_fields ) ) {
				// return if disallowed fields
				return $properties;
			}

			$entry = wpforms()->entry->get( sanitize_text_field( $_GET['wpfentry_id'] ) );
			if ( $this->user_has_permission( $entry->user_id ) ) {
				// Entry field values are in JSON, so we need to decode.
				$entry_fields = json_decode( $entry->fields, true );
				if ( ! isset( $entry_fields[$field_id] ) ) return $properties;

				$field_saved_value = $entry_fields[$field_id]['value'];

				// For fields that have dynamic choices we need to add extra logic.
				if ( ! empty( $field['dynamic_choices'] ) ) {
					$field_saved_value = $entry_fields[$field_id]['value_raw'];
					// In case of checkbox there can be multiple dynamic choices.
					$values_array = explode( ",", $field_saved_value );
					foreach ( $values_array as $value ) {
						$properties = $this->get_field_populated_single_property_value_dynamic_choices( $value, $properties, $field  );
					}

				} else {
					$properties = apply_filters( "wpf-views/edit-entry/prefill-{$field_type}-field-value", $properties, $field_saved_value, $field, $entry, $form_data );
				}

				if ( isset( $properties['inputs'][ 'primary' ]['attr']['value'] ) ) {
					$properties['inputs'][ 'primary' ]['attr']['value'] = $field_saved_value;
				}
			}
		}
		return $properties;
	}

	public function format_form_data( $form_data, $entry ) {
		if ( $this->is_editing_entry() ) {
			$saved_entry = wpforms()->entry->get( sanitize_text_field( $_GET['wpfentry_id'] ) );
			$entry_fields = json_decode( $saved_entry->fields, true );
			foreach ( $form_data['fields'] as $field_id => $field ) {

				if ( ( $field['type'] == 'file-upload' ) && ! empty( $field['required'] ) && empty( $entry[$field_id] ) ) {
					$files_to_be_deleted = array();
					$files_to_keep = array();
					$files_to_keep_links = array();

					if ( isset( $_POST['wpfviews']['upload-field'][$field_id] ) ) {
						foreach ( $_POST['wpfviews']['upload-field'][$field_id] as $attachment_key => $should_be_deleted ) {

							// get all file ids to be removed
							if ( ! empty( $should_be_deleted ) && $should_be_deleted == 'true' ) {
								$files_to_be_deleted[] = $attachment_key;
							}
						}
						// get field data from saved entry
						if ( isset( $entry_fields[$field_id] ) && ! empty( $entry_fields[$field_id]['value_raw'] ) ) {
							foreach ( $entry_fields[$field_id]['value_raw'] as $index => $file ) {

								if ( ! in_array( $index, $files_to_be_deleted ) ) {
									$files_to_keep[] = $file;
									$files_to_keep_links[] = $file['value'];
								}
							}
						}
					}
					if ( ! empty( $files_to_keep_links ) ) {
						// make field as not required
						$form_data['fields'][$field_id]['required'] = '0';
						// If max file uploads is less then or equal to saved fields don't allow user to upload more files
						// if(count($files_to_keep_links) >= $form_data['fields'][$field_id]['max_file_number']){
						//  $form_data['fields'][$field_id]['max_file_number'] = '0';
						// }
					}

				}
			}

		}
		return $form_data;
	}

	public function display_uploaded_files( $field, $form_data  ) {
		if ( $this->is_editing_entry() ) {

			if ( $field['type'] === 'file-upload' ) {
				$html = '';
				$entry = wpforms()->entry->get( sanitize_text_field( $_GET['wpfentry_id'] ) );
				$entry_fields = json_decode( $entry->fields, true );
				$entry_field = $entry_fields[$field['id']];
				$is_media_file = isset( $field['media_library'] );

				if ( \WPForms_Field_File_Upload::is_modern_upload( $entry_field ) ) {
					if ( $entry_field['value_raw'] ) {
						foreach ( $entry_field['value_raw'] as $key => $field_data ) {
							$html .= $this->get_file_item_html( $field_data, $is_media_file, $key );
						}
					}
				} else {

					$html .= $this->get_file_item_html( $entry_field, $is_media_file );
				}


				echo $html;

			}
		}
	}


	/**
	 * Get HTML for the file item.
	 *
	 * @since 1.6.6
	 *
	 *
	 * @param array   $field_data    Field data.
	 * @param bool    $is_media_file Is WP media.
	 * @param int     $key           Key for multiple items.
	 * @return string
	 */
	private function get_file_item_html( $field_data, $is_media_file, $key = 0 ) {

		$html = '<div class="file-entry wpf-views-file-display-cont">';
		$field_object = $this->get_entries_edit_field_object( 'file-upload' );
		$html .= $field_object->file_icon_html( $field_data );

		$html .= sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
			esc_url( $field_data['value'] ),
			esc_html( $field_data['file_user_name'] )
		);

		$html .= sprintf(
			'<input type="hidden" name="wpfviews[upload-field][%d][%d]" value="false"/>',
			esc_attr( $field_data['id'] ),
			esc_attr( $key )
		);

		$html .= $this->remove_button_html( );


		$html .= '</div>';

		return $html;
	}

	/**
	 * Get remove button html.
	 *
	 * @since 1.6.6
	 *
	 * @return string
	 */
	private function remove_button_html() {

		return '<a class="wpf-delete-file button-link-delete" href=""><span class="dashicons dashicons-trash wpforms-trash-icon"></span></a>';
	}


	public function update_entry( $bool, $fields, $entry, $form_data ) {
		if ( isset( $_POST['wpfentry_id'] ) && ! empty( $_POST['wpfentry_id'] ) ) {
			$entry_id = sanitize_text_field( $_POST['wpfentry_id'] );
			$form   = wpforms()->form->get( $form_data['id'] );

			$entry = wpforms()->entry->get( $entry_id );
			$entry_fields = json_decode( $entry->fields, true );

			$fields = $this->update_conditional_logic_hidden_fields_values( $fields, $entry_fields );

			$fields = $this->process_upload_fields( $fields, $entry_fields );

			// Update entry fields in entry fields table.
			$updated_fields = $this->process_update_fields_data( $entry_id, $fields, $form_data );

			$entry_data = [
				'fields'        => wp_json_encode( $fields ),
				'date_modified' => current_time( 'Y-m-d H:i:s' ),
			];

			$test = WPF_Views_Edit_Entry_Db()->update( $entry_id, $entry_data, '', 'edit_entry' );

			do_action( 'wpf-views/edit-entry/entry-updated', $entry_id, $entry_data, $form_data );
			// Add record to entry meta.
			wpforms()->entry_meta->add(
				[
					'entry_id' => (int) $entry_id,
					'form_id'  => (int) $form_data['id'],
					'user_id'  => get_current_user_id(),
					'type'     => 'log',
					'data'     => wpautop( sprintf( '<em>%s</em>', esc_html__( 'WPForms Views Entry edited.', 'wpforms' ) ) ),
				],
				'entry_meta'
			);

			return false;
		}

		return $bool;

	}
	/**
	 * Update hidden fields from conditional logic as WPForms send empty value got these fields.
	 * We don't want to set empty these fields values on entry udpate
	 */
	private function update_conditional_logic_hidden_fields_values( $fields, $entry_fields ) {
		foreach ( $fields as $field_id => $field ) {
			if ( ( isset( $field['visible'] ) && $field['visible'] === false ) && isset( $entry_fields[$field_id] ) ) {
				$fields[$field_id] = $entry_fields[$field_id];
				$fields[$field_id]['visible'] = false;
			}
		}
		return $fields;
	}

	private function process_upload_fields( $fields, $entry_fields ) {
		// check for uploaded fields
		if ( isset( $_POST['wpfviews']['upload-field'] ) ) {
			foreach ( $_POST['wpfviews']['upload-field'] as $field_id => $attachment_keys ) {
				$files_to_be_deleted = array();
				// get all file ids to be removed
				foreach ( $attachment_keys as $attachment_key => $should_be_deleted ) {

					if ( ! empty( $should_be_deleted ) && $should_be_deleted == 'true' ) {
						$files_to_be_deleted[] = $attachment_key;
					}
				}
				$files_to_keep = array();
				$files_to_keep_links = array();
				// get field data fro =m saved entry
				if ( isset( $entry_fields[$field_id] ) && ! empty( $entry_fields[$field_id]['value_raw'] ) ) {
					foreach ( $entry_fields[$field_id]['value_raw'] as $index => $file ) {

						if ( ! in_array( $index, $files_to_be_deleted ) ) {
							$files_to_keep[] = $file;
							$files_to_keep_links[] = $file['value'];
						}
					}
				}

				// Add old files data to $fields sent by wpforms
				if ( ! empty( $files_to_keep ) ) {
					$form_submitted_value = $fields[$field_id]['value'];
					$form_submitted_value_raw = $fields[$field_id]['value_raw'];
					$files_to_keep_value = implode( '/n', $files_to_keep_links );
					$fields[$field_id]['value'] = $form_submitted_value . $files_to_keep_value;
					if ( is_array( $form_submitted_value_raw ) ) {
						$fields[$field_id]['value_raw'] = array_merge( $form_submitted_value_raw, $files_to_keep );
					}else {
						$fields[$field_id]['value_raw'] = $files_to_keep;
					}
				}

			}
		}
		return $fields;
	}

	private function process_update_fields_data( $entry_id, $fields, $form_data ) {

		$updated_fields = [];


		if ( ! is_array( $fields ) ) {
			return $updated_fields;
		}

		// Get already saved fields data from DB.
		$entry_fields_obj = wpforms()->entry_fields;
		$dbdata_result    = $entry_fields_obj->get_fields( [ 'entry_id' => $entry_id , 'number'=> 999999 ]  );
		$dbdata_fields    = [];
		if ( ! empty( $dbdata_result ) ) {
			$dbdata_fields = array_combine( wp_list_pluck( $dbdata_result, 'field_id' ), $dbdata_result );
			$dbdata_fields = array_map( 'get_object_vars', $dbdata_fields );
		}
		$date_modified = current_time( 'Y-m-d H:i:s' );

		foreach ( $fields as $field ) {
			$save_field          = apply_filters( 'wpforms_entry_save_fields', $field, $form_data, $entry_id );
			$field_id            = $save_field['id'];
			$field_type          = empty( $save_field['type'] ) ? '' : $save_field['type'];
			$save_field['value'] = empty( $save_field['value'] ) ? '' : (string) $save_field['value'];
			$dbdata_value_exist  = isset( $dbdata_fields[ $field_id ]['value'] );

			// Process the field only if value was changed or not existed in DB at all. Also check if field is editable.
			// echo 'hrere';var_dump(empty($field['visible'])); die;
			if ( ( $dbdata_value_exist &&
					isset( $save_field['value'] ) &&
					(string) $dbdata_fields[ $field_id ]['value'] === $save_field['value'] )
			) {
				continue;
			}

			if ( $dbdata_value_exist ) {

				// Update field data in DB.
				$entry_fields_obj->update(
					(int) $dbdata_fields[ $field_id ]['id'],
					[
						'value' => $save_field['value'],
						'date'  => $date_modified,
					],
					'id',
					'edit_entry'
				);
			} else {
				// Add field data to DB.
				$entry_fields_obj->add(
					[
						'entry_id' => $entry_id,
						'form_id'  => (int) $form_data['id'],
						'field_id' => (int) $field_id,
						'value'    => $save_field['value'],
						'date'     => $date_modified,
					]
				);
			}
			$updated_fields[ $field_id ] = $field;
		}

		return $updated_fields;
	}




	function update_post( $post_args, $form_data, $fields ) {
		if ( isset( $_POST['wpfentry_id'] ) && ! empty( $_POST['wpfentry_id'] ) ) {
			$entry = wpforms()->entry->get( absint( $_POST['wpfentry_id'] ) );
			if ( ! empty( $entry->post_id ) ) {
				$post_args['ID'] = $entry->post_id;
				$post_args['post_status'] = get_post_status ( $entry->post_id );
				$post_args['comment_status'] = get_post_field( 'comment_status', $entry->post_id );

			}
		}
		return $post_args;
	}


	function add_entry_id_to_form() {
		if ( $this->is_editing_entry() ) {
			echo '<input type="hidden" name="wpfentry_id" value="' . sanitize_text_field( $_GET['wpfentry_id'] ) . '">';
		}
	}


	public function dequeque_form_abandonment() {
		if ( $this->is_editing_entry() ) {
			wp_dequeue_script( 'wpforms-form-abandonment' );
		}
	}


	function is_editing_entry() {
		if ( isset( $_GET['edit_wpfentry'] ) &&  ! empty( $_GET['wpfentry_id'] ) ) {
			$entry = wpforms()->entry->get( sanitize_text_field( $_GET['wpfentry_id'] ) );
			if ( $this->user_has_permission( $entry->user_id ) ) {
				return true;
			}
		}
		return false;
	}
	function user_has_permission( $user_id ) {
		$logged_in_user_id = get_current_user_id();
		if ( ( ! empty( $logged_in_user_id ) && ( $logged_in_user_id == $user_id ) ) || ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_edit_entries' )  )  ) {
			return true;
		}else {
			return false;
		}
	}

	private function get_entries_edit_field_object( $type ) {
		$field_object = apply_filters( "wpforms_fields_get_field_object_{$type}", null );
		return $field_object;
	}


	/**
	 * Add select to form notification settings.
	 *
	 * @since 1.0.0
	 *
	 * @param \WPForms_Builder_Panel_Settings $settings WPForms_Builder_Panel_Settings class instance.
	 * @param int                             $id       Subsection ID.
	 */
	public function notification_settings( $settings, $id ) {

		wpforms_panel_field(
			'checkbox',
			'notifications',
			'edit_entry',
			$settings->form_data,
			esc_html__( 'Send this notification only when editing entries', 'wpforms-views' ),
			array(
				'parent'     => 'settings',
				'subsection' => $id,
				'tooltip'    => wp_kses(
					__( 'When enabled this notification will <em>only</em> be sent when editing entries from frontend.', 'wpforms-views' ),
					array(
						'em'     => array(),
						'strong' => array(),
					)
				),
			)
		);
	}


	public function process_email( $process, $fields, $form_data, $notification_id, $context ) {

		if ( ! $process ) {
			return false;
		}

		// check if editing entry
		if ( isset( $_POST['wpfentry_id'] ) && ! empty( $_POST['wpfentry_id'] ) ) {

			// check if notification is enabled for editing entry
			if (  empty( $form_data['settings']['notifications'][ $notification_id ]['edit_entry'] ) ) {
				return false;
			}
		}else {
			// check if this notification is enabled only for editing entry
			if ( ! empty( $form_data['settings']['notifications'][ $notification_id ]['edit_entry'] ) ) {
				return false;
			}
		}
		return $process;
	}


	/**
	 * Get the value, that is used to prefill via dynamic or fallback population.
	 * Based on field data and current properties.
	 * Dynamic choices section.
	 *
	 * @since 1.6.0
	 *
	 *
	 * @param string  $get_value  Value from a GET param, always a string, sanitized, stripped slashes.
	 * @param array   $properties Field properties.
	 * @return array Modified field properties.
	 */
	protected function get_field_populated_single_property_value_dynamic_choices( $get_value, $properties, $field  ) {

		$default_key = null;

		foreach ( $properties['inputs'] as $input_key => $input_arr ) {
			// Dynamic choices support only integers in its values.
			if ( absint( $get_value ) === $input_arr['attr']['value'] ) {
				$default_key = $input_key;
				// Stop iterating over choices.
				break;
			}
		}

		// Redefine default choice only if dynamic value has changed anything.
		if ( null !== $default_key ) {
			foreach ( $properties['inputs'] as $input_key => $choice_arr ) {
				if ( $input_key === $default_key ) {
					$properties['inputs'][ $input_key ]['default']              = true;
					$properties['inputs'][ $input_key ]['container']['class'][] = 'wpforms-selected';
					// Stop iterating over choices.
					break;
				}
			}
		}

		return $properties;
	}
}
new WPF_Views_Edit_Entry_Processing();
