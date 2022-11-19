<?php

use WPForms\Pro\Forms\Fields\Base\EntriesEdit;

class WPF_VIEWS_INLINE_EDIT_Ajax {


	protected static $instance = null;

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	private function __construct() {
		$this->add_hooks();
	}


	private function add_hooks() {
		//wpforms_views_inline_edit
		add_action( 'wp_ajax_wpforms_views_inline_edit', array( $this, 'process_inline_edit_callbacks' ), 100 );

	}

	public function process_inline_edit_callbacks() {
		if ( isset( $_POST['wpf_inline_edit_field'] ) ) {
			$this->edit_wpf_field();
		}
	}

	private function edit_wpf_field() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpf_inline_edit' ) ) {
			exit( false );
		}

		// Doesn't have minimum version of WordPress
		if ( ! function_exists( 'wp_send_json' ) ) {
			exit( false );
		}
		// TODO: Check all field types

		$entry_id   = sanitize_key( $_POST['pk'] );
		$type       = sanitize_key( $_POST['form_field_type'] );
		$form_id    = sanitize_key( $_POST['form_id'] );
		$field_id   = sanitize_key( $_POST['field_id'] );
		$view_id    = sanitize_key( $_POST['view_id'] );
		if ( ! empty( $_POST['value'] ) ) {
			if ( ! is_array( $_POST['value'] ) ) {
				if ( $type == 'textarea' ) {
					$post_value = sanitize_textarea_field( $_POST['value'] );
				}else {
					$post_value = sanitize_text_field( $_POST['value'] );
				}
			}else {
				$post_value = $_POST['value'];
			}
		}else {
			$post_value = '';
		}
		//var_dump($_POST); die;

		$form   = wpforms()->form->get( $form_id );
		$entry = wpforms()->entry->get( $entry_id );

		if ( $this->user_has_permission( $entry->user_id ) ) {
			$form_data = apply_filters( 'wpforms_pro_admin_entries_edit_process_before_form_data', wpforms_decode( $form->post_content ), $entry );
			//Existing entry fields data.
			$entry_fields = apply_filters( 'wpforms_pro_admin_entries_edit_existing_entry_fields', wpforms_decode( $entry->fields ), $entry, $form_data );
			// //$field_data   = ! empty( $entry_fields[ $field_id ] ) ? $entry_fields[ $field_id ] : $this->get_empty_entry_field_data( $field_properties );

			$field_obj = $this->get_entries_edit_field_object( $type );
			$result = $field_obj->validate( $field_id, $post_value, $form_data );

			// var_dump(wpforms()->process->errors);die;
			if ( ! empty( wpforms()->process->errors ) ) {
				$error = $this->get_error_mesage( wpforms()->process->errors, $type );
				wp_send_json( $error );

			}else {
				$field_obj->format( $field_id, $post_value, $form_data );
				$entry_fields[$field_id] = wpforms()->process->fields[ $field_id ];
				// Update entry fields in entry fields table.
				$updated_fields = $this->process_update_fields_data( $entry_id, $entry_fields, $form_data );

				// update Entry
				$entry_data = [
					'fields'        => wp_json_encode( $entry_fields ),
					'date_modified' => current_time( 'Y-m-d H:i:s' ),
				];

				$result = WPF_Views_Inline_Edit_Db()->update( $entry_id, $entry_data, '', 'inline_edit_entry' );
				do_action( 'wpf-views/inline-edit/entry-updated', $entry_id, $entry_data, $form_data );

				wp_send_json( $result );

			}
		}else {
			wp_send_json( new WP_Error( 'insufficient_privileges', __( 'You are not allowed to edit this entry.', 'wpf-inline-edit' ) ) );
		}
		die;

	}


	private function process_update_fields_data( $entry_id, $fields, $form_data ) {

		$updated_fields = [];

		if ( ! is_array( $fields ) ) {
			return $updated_fields;
		}

		// Get saved fields data from DB.
		$entry_fields_obj = wpforms()->entry_fields;
		$dbdata_result    = $entry_fields_obj->get_fields( [ 'entry_id' => $entry_id ] );
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
			if (
				$dbdata_value_exist &&
				isset( $save_field['value'] ) &&
				(string) $dbdata_fields[ $field_id ]['value'] === $save_field['value']
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

	private function get_entries_edit_field_object( $type ) {
		$field_object = apply_filters( "wpforms_fields_get_field_object_{$type}", null );
		return $field_object;
	}

	function get_error_mesage( $errors, $field_type ) {
		foreach ( $errors as $error ) {
			$error_message = reset( $error );
		}
		$error_message = ( empty( $error_message ) ? __( 'Invalid value. Please try again.', 'wpf-views-inline-edit' ) : $error_message  );

		return new WP_Error( strtolower( $field_type ) . '_validation_failed', $error_message );

	}

	function user_has_permission( $user_id ) {
		$logged_in_user_id = get_current_user_id();
		if ( ( ! empty( $logged_in_user_id ) && ( $logged_in_user_id == $user_id ) ) ||  WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_inline_edit' )   ) {
			//if ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_inline_edit')   ) {
			return true;
		} else {
			return false;
		}
	}

}

WPF_VIEWS_INLINE_EDIT_Ajax::get_instance();