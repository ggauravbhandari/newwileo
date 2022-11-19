<?php

class WPF_Views_Inline_User_Registration_Integration {

	function __construct() {

		// Update user registration data after entry is edited
		add_action( 'wpf-views/inline-edit/entry-updated',  array( $this, 'update_user_meta' ), 10, 3 );

	}



	/**
	 * Update user meta after entry is updated
	 *
	 * @param [type]  $entry_id
	 * @param [type]  $entry_data
	 * @return void
	 */
	public function update_user_meta( $entry_id, $entry_data, $form_data ) {
		$form   = wpforms()->form->get( $form_data['id'] );
		// raw form data, used to check if user regsitration is enabled on this form.
		$form_data_raw = wpforms_decode( $form->post_content );
		$fields = json_decode( $entry_data['fields'], true );

		if ( ! empty( $form_data_raw['meta']['template'] ) && 'user_registration' === $form_data_raw['meta']['template'] ) {
			$user_data     = $this->get_user_data( $fields, $form_data );
			$entry = wpforms()->entry->get( $entry_id );
			if ( ! empty( $entry->user_id ) ) {
				$user_data['ID'] = $entry->user_id;
				// update user.
				$user_id = wp_update_user( $user_data );
				// update user meta
				$this->update_custom_user_meta( $user_id, $fields, $form_data_raw );

			}
		}

	}


	/**
	 * Add custom user meta.
	 *
	 * @since 1.3.3
	 *
	 * @param int     $user_id   The user id.
	 * @param array   $fields    The fields that have been submitted.
	 * @param array   $form_data The information for the form.
	 */
	private function update_custom_user_meta( $user_id, $fields, $form_data ) {

		$form_settings = $form_data['settings'];
		if ( empty( $form_settings['registration_meta'] ) ) {
			return;
		}

		foreach ( $form_settings['registration_meta'] as $key => $id ) {

			if ( empty( $key ) || ( empty( $id ) && '0' !== $id ) ) {
				continue;
			}

			if ( ! empty( $fields[ $id ]['value'] ) ) {

				$value = apply_filters( 'wpforms_user_registration_process_meta', $fields[ $id ]['value'], $key, $id, $fields, $form_data );

				update_user_meta( $user_id, $key, $value );
			}
		}
	}



	/**
	 * Get required user fields.
	 *
	 * @since 1.3.3
	 *
	 *
	 * @param array   $fields    The fields that have been submitted.
	 * @param array   $form_data The information for the form.
	 * @return array
	 */
	private function get_required_user_fields( $fields, $form_data ) {

		$form_settings   = $form_data['settings'];
		$required        = [ 'email' ];
		$required_fields = [];

		foreach ( $fields as $field ) {
			$nickname = wpforms_get_form_field_meta( $field['id'], 'nickname', $form_data );
			if ( ! empty( $nickname ) && in_array( $nickname, $required, true ) ) {
				$required_fields[ $nickname ] = $field['value'];
			}
		}

		return $required_fields;
	}


	/**
	 * Get optional user fields.
	 *
	 * @since 1.3.3
	 *
	 *
	 * @param array   $fields    The fields that have been submitted.
	 * @param array   $form_data The information for the form.
	 * @return array
	 */
	private function get_optional_user_fields( $fields, $form_data ) {

		$optional        = [ 'name', 'bio', 'website' ];
		$form_settings   = $form_data['settings'];
		$optional_fields = [];

		foreach ( $optional as $opt ) {
			$key = 'registration_' . $opt;
			$id  = ! empty( $form_settings[ $key ] ) ? absint( $form_settings[ $key ] ) : '';
			if ( ! empty( $fields[ $id ]['value'] ) ) {
				if ( 'name' === $opt ) {
					$nkey                          = 'simple' === $form_data['fields'][ $id ]['format'] ? 'value' : 'first';
					$optional_fields['first_name'] = ! empty( $fields[ $id ][ $nkey ] ) ? $fields[ $id ][ $nkey ] : '';
					$optional_fields['last_name']  = ! empty( $fields[ $id ]['last'] ) ? $fields[ $id ]['last'] : '';
				} else {
					$optional_fields[ $opt ] = $fields[ $id ]['value'];
				}
			}
		}

		//$optional_fields['password'] = ! empty( $this->password ) ? $this->password : wp_generate_password( 18 );
		//$this->password              = '';

		// User role.
		$optional_fields['role'] = $this->get_user_role( $form_settings );

		return $optional_fields;
	}

	/**
	 * Get user role.
	 *
	 * @since 1.3.3
	 *
	 *
	 * @param array   $form_settings The form settings.
	 * @return mixed
	 */
	private function get_user_role( $form_settings ) {

		return ! empty( $form_settings['registration_role'] ) ? $form_settings['registration_role'] : get_option( 'default_role' );
	}

	/**
	 * Get user data.
	 *
	 * @since 1.3.3
	 *
	 *
	 * @param array   $fields    The fields that have been submitted.
	 * @param array   $form_data The information for the form.
	 * @return array
	 */
	private function get_user_data( $fields, $form_data ) {

		$reg_fields = array_merge( $this->get_required_user_fields( $fields, $form_data ), $this->get_optional_user_fields( $fields, $form_data ) );

		// Required user information.
		$user_data = array(
			//'user_login' => $reg_fields['username'],
			'user_email' => $reg_fields['email'],
			//'user_pass'  => $reg_fields['password'],
		);

		// Optional user information.
		if ( ! empty( $reg_fields['website'] ) ) {
			$user_data['user_url'] = $reg_fields['website'];
		}
		if ( ! empty( $reg_fields['first_name'] ) ) {
			$user_data['first_name'] = $reg_fields['first_name'];
		}
		if ( ! empty( $reg_fields['last_name'] ) ) {
			$user_data['last_name'] = $reg_fields['last_name'];
		}
		if ( ! empty( $reg_fields['bio'] ) ) {
			$user_data['description'] = $reg_fields['bio'];
		}

		return $user_data;
	}

}
new WPF_Views_Inline_User_Registration_Integration();
