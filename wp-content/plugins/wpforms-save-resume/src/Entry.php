<?php

namespace WPFormsSaveResume;

use WPForms\Helpers\Crypto;

/**
 * The Class for communicating with DB.
 *
 * @since 1.0.0
 */
class Entry {

	/**
	 * Fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $fields;

	/**
	 * Form ID.
	 *
	 * @since 1.0.0
	 *
	 * @var int Form ID.
	 */
	private $form_id;

	/**
	 * Form data.
	 *
	 * @since 1.0.0
	 *
	 * @var array Form data.
	 */
	private $form_data;

	/**
	 * Fields not allowed to be saved.
	 *
	 * @since 1.0.0
	 *
	 * @var string[] Fields.
	 */
	private $not_allowed_fields = [
		'file-upload',
		'signature',
		'password',
		'authorize_net',
		'stripe-credit-card',
		'square',
		'payment-total',
		'captcha',
	];

	/**
	 * Format and sanitize raw data.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $form_id Form ID.
	 * @param object $entry   Entry.
	 */
	public function prepare_data( $form_id, $entry ) {

		wpforms()->process->fields = [];
		$this->form_id             = $form_id;

		// If the honeypot was triggers we assume this is a spammer.
		if ( isset( $entry['hp'] ) && ! empty( $entry['hp'] ) ) {
			wp_send_json_error();
		}

		// Get the form settings for this form.
		$this->form_data = wpforms()->get( 'form' )->get( $this->form_id, [ 'content_only' => true ] );

		// Format fields.
		foreach ( $this->form_data['fields'] as $field ) {

			$field_submit = isset( $entry['fields'][ $field['id'] ] ) ? $entry['fields'][ $field['id'] ] : '';

			// Exclude fields which is not supported.
			if ( in_array( $field['type'], $this->not_allowed_fields, true ) ) {
				continue;
			}

			do_action( "wpforms_process_format_{$field['type']}", $field['id'], $field_submit, $this->form_data );
		}

		/**
		 * Filter post-process fields before saving.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $fields    Fields data array.
		 * @param object $entry     Entry.
		 * @param array  $form_data Form data.
		 */
		$this->fields = apply_filters( 'wpforms_process_filter_save_resume', wpforms()->process->fields, $entry, $this->form_data );

		/**
		 * Triggers when Partial fields are ready to be saved.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $fields    Fields data array.
		 * @param object $entry     Entry.
		 * @param array  $form_data Form data.
		 */
		do_action( 'wpforms_process_save_resume', $this->fields, $entry, $this->form_data );
	}

	/**
	 * Add new partial entry.
	 *
	 * @since 1.0.0
	 */
	public function add_entry() {

		$user_id = get_current_user_id();
		$user_ip = wpforms_get_ip();

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification
		$user_agent = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 256 ) : '';

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification
		$user_uuid = ! empty( $_COOKIE['_wpfuuid'] ) ? $_COOKIE['_wpfuuid'] : '';
		$date      = gmdate( 'Y-m-d H:i:s' );

		// If GDPR enhancements are enabled and user details are disabled
		// globally or in the form settings, discard the IP and UA.
		if ( ! wpforms_is_collecting_ip_allowed( $this->form_data ) ) {
			$user_agent = '';
			$user_ip    = '';
		}

		// Prepare the args to be saved.
		$data = [
			'form_id'    => absint( $this->form_id ),
			'user_id'    => absint( $user_id ),
			'status'     => 'partial',
			'fields'     => wp_json_encode( $this->fields ),
			'ip_address' => sanitize_text_field( $user_ip ),
			'user_agent' => sanitize_text_field( $user_agent ),
			'user_uuid'  => sanitize_text_field( $user_uuid ),
			'date'       => $date,
		];

		// Save.
		$entry    = wpforms()->get( 'entry' );
		$entry_id = $entry->add( $data );

		$verification_link = $this->generate_hash_url( $entry_id );

		wpforms()->get( 'entry_meta' )->add(
			[
				'entry_id' => $entry_id,
				'form_id'  => absint( $this->form_id ),
				'user_id'  => absint( $user_id ),
				'type'     => 'partial',
				'data'     => $verification_link,
			],
			'entry_meta'
		);

		return [
			'hash'     => $verification_link,
			'entry_id' => $entry_id,
		];
	}

	/**
	 * Update entry.
	 *
	 * @since 1.0.0
	 *
	 * @param int $entry_id Entry ID.
	 *
	 * @return array
	 */
	public function update_entry( $entry_id ) {

		// Prepare the args to be updated.
		$data = [
			'viewed'        => 0,
			'fields'        => wp_json_encode( $this->fields ),
			'date_modified' => gmdate( 'Y-m-d H:i:s' ),
		];

		$entry             = wpforms()->get( 'entry' );
		$entry_meta        = wpforms()->get( 'entry_meta' );
		$verification_link = $this->generate_hash_url( $entry_id );

		$entry->update( $entry_id, $data, '', '', [ 'cap' => false ] );

		$entry_meta->update(
			$entry_id,
			[ 'data' => $verification_link ],
			'entry_id'
		);

		return [
			'hash'     => $verification_link,
			'entry_id' => $entry_id,
		];
	}

	/**
	 * Load entry data to fields properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $properties Properties.
	 * @param array $field      Field.
	 * @param array $data       Entry data.
	 *
	 * @return array
	 */
	public function get_entry( $properties, $field, $data ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity

		if ( in_array( $field['type'], $this->not_allowed_fields, true ) ) {
			return $properties;
		}

		$id    = (int) ! empty( $field['id'] ) ? $field['id'] : 0;
		$input = 'primary';

		// Radio, select, checkbox, gdpr-checkbox.
		if ( isset( $field['choices'] ) ) {
			$value_key = in_array( $field['type'], [ 'payment-checkbox', 'payment-select', 'payment-multiple' ], true ) ? 'value_choice' : 'value_raw';

			if ( ! isset( $data[ $id ][ $value_key ] ) ) {
				return $properties;
			}

			$delimiter = ! empty( $field['dynamic_choices'] ) ? ',' : "\n";
			$value     = explode( $delimiter, $data[ $id ][ $value_key ] );

			foreach ( $value as $single_value ) {
				$properties = ! empty( $field['dynamic_choices'] ) ? $this->get_dynamic_value_choices( trim( $single_value ), $properties ) : $this->get_value_choices( trim( $single_value ), $properties, $field );
			}

			return $properties;
		}

		if ( $field['type'] === 'net_promoter_score' ) {

			$get_value = stripslashes( sanitize_text_field( $data[ $id ]['value'] ) );

			if ( ! empty( $properties['inputs'][ $get_value ] ) ) {
				$properties['inputs'][ $get_value ]['attr']['checked'] = true;
			}

			return $properties;
		}

		if ( $field['type'] === 'rating' ) {
			return $this->get_rating_field_value( $data[ $id ]['value'], $input, $properties );
		}

		if ( $field['type'] === 'likert_scale' ) {

			if ( ! empty( $data[ $id ]['value_raw'] ) ) {

				$properties = $this->get_likert_scale_value( $data[ $id ]['value_raw'], $properties );
			}

			return $properties;
		}

		if ( in_array( $field['type'], [ 'richtext', 'textarea' ], true ) ) {

			$properties['inputs'][ $input ]['attr']['value'] = stripslashes( $data[ $id ]['value'] );

			return $properties;
		}

		// Common fields type which are processing the same.
		$inputs = [
			'address1',
			'address2',
			'city',
			'state',
			'postal',
			'country',
			'primary',
			'secondary',
			'date',
			'time',
			'first',
			'middle',
			'last',
		];

		foreach ( $inputs as $input ) {

			$value = isset( $data[ $id ][ $input ] ) ? $input : 'value';
			$properties['inputs'][ $input ]['attr']['value'] = stripslashes( sanitize_text_field( $data[ $id ][ $value ] ) );

			if (
				$input === 'date' &&
				! empty( $field['date_type'] ) &&
				$field['date_type'] === 'dropdown' &&
				! empty( $data[ $id ]['unix'] )
			) {
				$properties['inputs'][ $input ]['default'] = [
					'd' => gmdate( 'd', $data[ $id ]['unix'] ),
					'm' => gmdate( 'm', $data[ $id ]['unix'] ),
					'y' => gmdate( 'Y', $data[ $id ]['unix'] ),
				];
			}
		}

		return $properties;
	}

	/**
	 * Get choices values for Multiple choices, select, radio fields.
	 *
	 * @since 1.0.0
	 *
	 * @param string $get_value  Value.
	 * @param array  $properties Properties.
	 * @param array  $field      Field.
	 *
	 * @return array
	 */
	protected function get_value_choices( $get_value, $properties, $field ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity

		$default_key = null;

		// For fields that have normal choices we need to add extra logic.
		foreach ( $field['choices'] as $choice_key => $choice_arr ) {
			$choice_value_key = isset( $field['show_values'] ) ? 'value' : 'label';

			if (
				( isset( $choice_arr[ $choice_value_key ] ) &&
				  strtoupper( sanitize_text_field( $choice_arr[ $choice_value_key ] ) ) === strtoupper( $get_value )
				) ||
				(
					empty( $choice_arr[ $choice_value_key ] ) &&
					/* translators: %d - choice number. */
					$get_value === sprintf( esc_html__( 'Choice %d', 'wpforms-save-resume' ), (int) $choice_key )
				)
			) {
				$default_key = $choice_key;

				// Stop iterating over choices.
				break;
			}
		}

		// Redefine default choice only if population value has changed anything.
		if ( $default_key !== null ) {
			foreach ( $field['choices'] as $choice_key => $choice_arr ) {
				if ( $choice_key === $default_key ) {
					$properties['inputs'][ $choice_key ]['default']              = true;
					$properties['inputs'][ $choice_key ]['container']['class'][] = 'wpforms-selected';

					break;
				}
			}
		}

		return $properties;
	}

	/**
	 * Get choices values for Dynamic fields.
	 *
	 * @since 1.0.1
	 *
	 * @param string $get_value  Value.
	 * @param array  $properties Properties.
	 *
	 * @return array
	 */
	private function get_dynamic_value_choices( $get_value, $properties ) {

		$default_key = null;

		foreach ( $properties['inputs'] as $input_key => $input_arr ) {
			// Dynamic choices support only integers in its values.
			if ( absint( $get_value ) === $input_arr['attr']['value'] ) {
				$default_key = $input_key;

				// Stop iterating over choices.
				break;
			}
		}

		// Redefine default choice only if population value has changed anything.
		if ( $default_key !== null ) {
			$properties['inputs'][ $default_key ]['default']              = true;
			$properties['inputs'][ $default_key ]['container']['class'][] = 'wpforms-selected';
		}

		return $properties;
	}

	/**
	 * Get Likert scale values.
	 *
	 * @since 1.0.0
	 *
	 * @param array $raw_value  Value.
	 * @param array $properties Properties.
	 *
	 * @return array
	 */
	protected function get_likert_scale_value( $raw_value, $properties ) {

		$inputs = [];

		foreach ( $raw_value as $row => $column_array ) {
			foreach ( (array) $column_array as $column ) {
				$inputs[] = 'r' . (int) $row . '_c' . (int) $column;
			}
		}

		if ( empty( $inputs ) ) {
			return $properties;
		}

		foreach ( $inputs as $key ) {
			if ( isset( $properties['inputs'][ $key ] ) ) {
				$properties['inputs'][ $key ]['attr']['checked'] = true;
			}
		}

		return $properties;
	}

	/**
	 * Get Rating field value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $raw_value  Value.
	 * @param string $input      Input.
	 * @param array  $properties Properties.
	 *
	 * @return array
	 */
	protected function get_rating_field_value( $raw_value, $input, $properties ) {

		if ( ! is_string( $raw_value ) ) {
			return $properties;
		}

		$properties['inputs'][ $input ]['rating']['default'] = (int) $raw_value;

		return $properties;
	}

	/**
	 * Check if entry already exists.
	 *
	 * @since 1.0.0
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return array|object|null|void
	 */
	public static function check_if_exists( $form_id ) {

		global $wpdb;

		$user_uuid  = ! empty( $_COOKIE['_wpfuuid'] ) ? $_COOKIE['_wpfuuid'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$table_name = wpforms()->get( 'entry' )->table_name;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->get_row(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT entry_id FROM $table_name WHERE `form_id` = %d AND `user_uuid` = %s AND `status` = 'partial' LIMIT 1;",
				absint( $form_id ),
				preg_replace( '/[^a-z0-9_\s-]+/i', '', $user_uuid )
			)
		);
	}

	/**
	 * Generate the hash from entry_id and append it to the current URL.
	 *
	 * @since 1.0.0
	 *
	 * @param int $entry_id Entry ID.
	 *
	 * @return string
	 */
	private function generate_hash_url( $entry_id ) {

		$hash       = Crypto::encrypt( (string) $entry_id );
		$refer_link = isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : home_url();
		$refer_link = remove_query_arg( 'wpforms_resume_entry' , $refer_link );

		// Clean URL if Dynamic form population is enabled.
		if ( ! empty( $this->form_data['settings']['dynamic_population'] ) ) {
			$refer_link = $this->clean_query_from_wpf( 'wpf' . $this->form_id, $refer_link );
		}

		return add_query_arg( 'wpforms_resume_entry', $hash, $refer_link );
	}

	/**
	 * Remove an item from a query string by wpf+form_id pattern.
	 *
	 * @since 1.0.0
	 *
	 * @param string       $key   Query key or keys to remove.
	 * @param false|string $query Optional. When false uses the current URL. Default false.
	 *
	 * @return string New URL query string.
	 */
	private function clean_query_from_wpf( $key, $query = false ) {

		if ( $query === false ) {
			return $query;
		}

		$query_vars = wp_parse_url( $query, PHP_URL_QUERY );

		parse_str( $query_vars, $keys );

		foreach ( $keys as $k => $v ) {

			// Check if query key starts with given param.
			if ( strpos( $k, $key ) === 0 ) {
				$query = add_query_arg( $k, false, $query );
			}
		}

		return $query;
	}

	/**
	 * Get link to the partial entry.
	 *
	 * @since 1.0.0
	 *
	 * @param int $entry_id Entry ID.
	 *
	 * @return string
	 */
	public static function get_hash_url_by_entry( $entry_id ) {

		if ( empty( $entry_id ) ) {
			return '';
		}

		$saved_entry = wpforms()->get( 'entry_meta' )->get_meta(
			[
				'entry_id' => $entry_id,
				'type'     => 'partial',
				'number'   => 1,
			]
		);

		return $saved_entry[0]->data;
	}

	/**
	 * Get entry_id from provided hash code.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hash Encrypted entry_id.
	 *
	 * @return int Entry ID or 0 when decrypting failed.
	 */
	public static function get_entry_by_hash( $hash ) {

		if ( ! is_string( $hash ) ) {
			return 0;
		}

		$hash = str_replace( ' ', '+', $hash );

		return (int) Crypto::decrypt( $hash );
	}
}
