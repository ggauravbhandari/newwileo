<?php

namespace WPFormsSaveResume;

use WPForms\Helpers\Transient;
use WPFormsSaveResume\Email\EmailNotification;

/**
 * The Frontend.
 *
 * @since 1.0.0
 */
class Frontend {

	/**
	 * Current form data.
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected $form_data;

	/**
	 * Entry object.
	 *
	 * @var object
	 *
	 * @since 1.0.0
	 */
	protected $entry;

	/**
	 * Unique user ID.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $user_uuid;

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->hooks();

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification
		$this->user_uuid = ! empty( $_COOKIE['_wpfuuid'] ) ? $_COOKIE['_wpfuuid'] : '';
	}

	/**
	 * Init method.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		// Ajax processing.
		add_action( 'wp_ajax_nopriv_wpforms_save_resume', [ $this, 'process_entry' ] );
		add_action( 'wp_ajax_wpforms_save_resume', [ $this, 'process_entry' ] );

		add_filter( 'wpforms_field_properties', [ $this, 'load_field_data' ], 10, 3 );

		// Front-end related hooks.
		add_action( 'wpforms_frontend_css', [ $this, 'enqueue_css' ] );
		add_action( 'wpforms_frontend_js', [ $this, 'enqueue_js' ] );
		add_filter( 'wpforms_frontend_output_container_before', [ $this, 'display_save_resume_container_open' ], 10, 1 );
		add_filter( 'wpforms_frontend_output_container_after', [ $this, 'display_disclaimer' ], 10, 1 );
		add_filter( 'wpforms_frontend_output_container_after', [ $this, 'display_confirmation' ], 10, 1 );
		add_filter( 'wpforms_frontend_output_container_after', [ $this, 'display_save_resume_container_close' ], 999, 1 );
		add_filter( 'wpforms_frontend_load', [ $this, 'display_form' ], 10, 2 );
		add_filter( 'wpforms_frontend_load', [ $this, 'display_expired_message' ], 10, 2 );

		add_action( 'wpforms_display_submit_after', [ $this, 'display_save_resume' ], 10, 1 );

		// Notifications.
		add_action( 'wp', [ $this, 'send_email' ] );

		// Conversational Forms integration.
		add_action( 'wpforms_conversational_forms_enqueue_styles', [ $this, 'enqueue_conversational_forms_styles' ] );
		add_filter( 'wpforms_conversational_forms_start_button_disabled', [ $this, 'is_locked_filter' ], 10 );

	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 *
	 * @param array $forms List of forms on the current page.
	 */
	public function enqueue_css( $forms ) {

		if ( ! $this->has_forms_with_save_resume( $forms ) ) {
			return;
		}

		$min = wpforms_get_min_suffix();

		wp_enqueue_style(
			'wpforms-save-resume',
			WPFORMS_SAVE_RESUME_URL . "assets/css/wpforms-save-resume{$min}.css",
			[],
			WPFORMS_SAVE_RESUME_VERSION
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 *
	 * @param array $forms List of forms on the current page.
	 */
	public function enqueue_js( $forms ) {

		if ( ! $this->has_forms_with_save_resume( $forms ) ) {
			return;
		}

		$min = wpforms_get_min_suffix();

		wp_enqueue_script(
			'wpforms-save-resume',
			WPFORMS_SAVE_RESUME_URL . "assets/js/wpforms-save-resume{$min}.js",
			[ 'wpforms', 'wpforms-validation' ],
			WPFORMS_SAVE_RESUME_VERSION,
			true
		);

		wp_localize_script(
			'wpforms-save-resume',
			'wpforms_save_resume',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	/**
	 * Enqueue styles for Conversational Forms compatibility.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_conversational_forms_styles() {

		$min = wpforms_get_min_suffix();

		wp_enqueue_style(
			'wpforms-save-resume',
			WPFORMS_SAVE_RESUME_URL . "assets/css/wpforms-save-resume-conversational-forms{$min}.css",
			[ 'wpforms-conversational-forms' ],
			WPFORMS_SAVE_RESUME_VERSION
		);
	}

	/**
	 * Whether any of the form has the Save and Resume functionality enabled.
	 *
	 * @since 1.0.0
	 *
	 * @param array $forms List of forms on the current page.
	 */
	private function has_forms_with_save_resume( $forms ) {

		$is_enabled = false;

		foreach ( (array) $forms as $form ) {
			if ( wpforms_save_resume()->is_enabled( $form ) ) {
				$is_enabled = true;

				break;
			}
		}

		return $is_enabled;
	}

	/**
	 * Display the link to Save and Resume page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Form data.
	 *
	 * @return int|void
	 */
	public function display_save_resume( $form_data ) {

		if ( ! wpforms_save_resume()->is_enabled( $form_data ) ) {
			return;
		}

		$this->form_data = $form_data;

		$link = ! empty( $this->form_data['settings']['save_resume_link_text'] ) ? $this->form_data['settings']['save_resume_link_text'] : Settings::get_default_link_text();

		return printf( '<a href="#" class="wpforms-save-resume-button"><span>%s</span></a>', esc_attr( $link ) );
	}

	/**
	 * Create new entry.
	 *
	 * @since 1.0.0
	 */
	public function process_entry() {

		// Make sure we have required data.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST['wpforms'] ) ) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$form_id = ! empty( $_POST['wpforms']['id'] ) ? absint( $_POST['wpforms']['id'] ) : 0;

		if ( $form_id === 0 ) {
			wp_send_json_error();
		}

		// Prepare entry data.
		$entry = new Entry();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput
		$entry->prepare_data( $form_id, stripslashes_deep( $_POST['wpforms'] ) );
		$exists = Entry::check_if_exists( $form_id );
		$data   = ! empty( $exists ) ? $entry->update_entry( $exists->entry_id ) : $entry->add_entry();

		wp_send_json_success( $data );
	}

	/**
	 * Load entry to the form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $properties Properties.
	 * @param array $field      Field.
	 * @param array $form_data  Form information.
	 *
	 * @return mixed
	 */
	public function load_field_data( $properties, $field, $form_data ) {

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! isset( $_GET['wpforms_resume_entry'] ) ) {
			return $properties;
		}

		$entry = wpforms()->get( 'entry' );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.NonceVerification
		$hash     = ! empty( $_GET['wpforms_resume_entry'] ) ? $_GET['wpforms_resume_entry'] : '';
		$entry_id = Entry::get_entry_by_hash( $hash );

		if ( $entry_id === 0 ) {
			return $properties;
		}

		$entry_data = $entry->get( $entry_id );

		if ( empty( $entry_data ) ) {
			return $properties;
		}

		// In case multiple forms are displayed on the same page.
		if ( (int) $entry_data->form_id !== (int) $form_data['id'] ) {
			return $properties;
		}

		$entry_data = wpforms_decode( $entry_data->fields );
		$id         = (int) ! empty( $field['id'] ) ? $field['id'] : 0;

		if ( ! isset( $entry_data[ $id ] ) ) {
			return $properties;
		}

		$entry = new Entry();

		return $entry->get_entry( $properties, $field, $entry_data );
	}

	/**
	 * Templates for confirmation block.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Form information.
	 */
	public function display_confirmation( $form_data ) {

		$confirmation = ! empty( $form_data['settings']['save_resume_confirmation_message'] ) ? $form_data['settings']['save_resume_confirmation_message'] : Settings::get_default_confirmation_message();
		$action       = remove_query_arg( 'wpforms-save-resume' );

		if ( empty( $form_data['settings']['save_resume_enable'] ) ) {
			return $form_data;
		}

		if (
			empty( $form_data['settings']['save_resume_enable_resume_link'] ) &&
			empty( $form_data['settings']['save_resume_enable_email_notification'] )
		) {
			return $form_data;
		}
		?>

		<div class="wpforms-save-resume-confirmation" style="display: none">
			<?php printf( '<div class="message">%s</div>', wp_kses_post( wpautop( $confirmation ) ) ); ?>

			<div class="wpforms-save-resume-actions">
				<?php if ( ! empty( $form_data['settings']['save_resume_enable_resume_link'] ) ) : ?>
					<div class="wpforms-field">
					<label class="wpforms-field-label wpforms-save-resume-label">
						<?php esc_html_e( 'Copy Link', 'wpforms-save-resume' ); ?>
					</label>
					<div class="wpforms-save-resume-shortcode-container">
						<input type="text" class="wpforms-save-resume-shortcode" value="" disabled />
						<span class="wpforms-save-resume-shortcode-copy" title="<?php esc_attr_e( 'Copy resume link to clipboard', 'wpforms-save-resume' ); ?>">
							<span class="copy-icon"></span>
						</span>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $form_data['settings']['save_resume_enable_email_notification'] ) ) : ?>
					<form class="wpforms-validate wpforms-form wpforms-save-resume-email-notification" method="post" action="<?php echo esc_url( $action ); ?>">
						<div class="wpforms-field wpforms-field-email">
							<label class="wpforms-field-label wpforms-save-resume-label">
								<?php esc_html_e( 'Email', 'wpforms-save-resume' ); ?>
								<span class="wpforms-required-label">*</span>
							</label>
							<input type="email" name="wpforms[save_resume_email]" required>
						</div>
						<div class="wpforms-submit-container">
							<?php wp_nonce_field( 'wpforms_save_resume_process_entries' ); ?>
							<input type="hidden" name="wpforms[form_id]" value="<?php echo esc_attr( $form_data['id'] ); ?>">
							<input type="hidden" name="wpforms[entry_id]" class="wpforms-save-resume-entry-id" value="">
							<button type="submit" name="wpforms[save-resume]" class="wpforms-submit" value="wpforms-submit">
								<?php esc_html_e( 'Send Link', 'wpforms-save-resume' ); ?>
							</button>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Templates for disclaimer block.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Form information.
	 */
	public function display_disclaimer( $form_data ) {

		if ( empty( $form_data['settings']['save_resume_enable'] ) ) {
			return $form_data;
		}

		if ( empty( $form_data['settings']['save_resume_disclaimer_enable'] ) ) {
			return $form_data;
		}

		$message = ! empty( $form_data['settings']['save_resume_disclaimer_message'] ) ? $form_data['settings']['save_resume_disclaimer_message'] : Settings::get_default_disclaimer_message();
		?>

		<div class="wpforms-save-resume-disclaimer" style="display: none">
			<?php printf( '<div class="message">%s</div>', wp_kses_post( wpautop( $message ) ) ); ?>

			<div class="wpforms-form">
				<button type="submit" class="wpforms-save-resume-disclaimer-continue wpforms-submit">
					<?php esc_html_e( 'Continue', 'wpforms-save-resume' ); ?>
				</button>
				<a href="#" class="wpforms-save-resume-disclaimer-back">
					<span><?php esc_html_e( 'Go Back', 'wpforms-save-resume' ); ?></span>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Append wrapper to main form container.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Form information.
	 */
	public function display_save_resume_container_open( $form_data ) {

		if ( empty( $form_data['settings']['save_resume_enable'] ) ) {
			return $form_data;
		}

		printf( '<div class="wpforms-container-save-resume">' );
	}

	/**
	 * Append wrapper closing tag to form container.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Form information.
	 */
	public function display_save_resume_container_close( $form_data ) {

		if ( empty( $form_data['settings']['save_resume_enable'] ) ) {
			return $form_data;
		}

		printf( '</div>' );
	}

	/**
	 * Process email form submitting.
	 *
	 * @since 1.0.0
	 */
	public function send_email() {

		// Security check.
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'wpforms_save_resume_process_entries' ) ) {
			return;
		}

		if ( ! isset( $_POST['submit'] ) && empty( $_POST['wpforms']['save_resume_email'] ) ) {
			return;
		}

		$entry_id  = ! empty( $_POST['wpforms']['entry_id'] ) ? absint( $_POST['wpforms']['entry_id'] ) : '';
		$form_data = ! empty( $_POST['wpforms']['form_id'] ) ? wpforms()->get( 'form' )->get( absint( $_POST['wpforms']['form_id'] ), [ 'content_only' => true ] ) : '';
		$address   = sanitize_email( wp_unslash( $_POST['wpforms']['save_resume_email'] ) );

		if ( empty( $form_data ) || ! is_email( $address ) ) {
			return;
		}

		$message = ! empty( $form_data['settings']['save_resume_email_notification_message'] ) ? $form_data['settings']['save_resume_email_notification_message'] : Settings::get_default_email_notification();
		$email   = [
			'address' => $address,
			'subject' => Settings::get_email_subject(),
			'message' => apply_filters( 'wpforms_process_smart_tags', $message, $form_data, [], $entry_id ),
		];

		( new EmailNotification() )->send( $email );

		Transient::set( 'wpforms_save_resume-' . $this->user_uuid, '1', MINUTE_IN_SECONDS );

		$return_back_url = ! empty( $_REQUEST['_wp_http_referer'] ) ? esc_url_raw( wp_unslash( $_REQUEST['_wp_http_referer'] ) ) : home_url();

		if ( ! empty( $return_back_url ) ) {
			wp_safe_redirect( $return_back_url );
			exit;
		}
	}

	/**
	 * Append additional HTML to form if needed.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $load_form Indicates whether a form should be loaded.
	 * @param array $form_data Form data.
	 *
	 * @return mixed
	 */
	public function display_form( $load_form, $form_data ) {

		if ( ! empty( Transient::get( 'wpforms_save_resume-' . $this->user_uuid ) ) ) {
			// Hide form on success page.
			add_filter(
				'wpforms_frontend_container_class',
				static function( $classes, $form_data ) {
					$classes[] = 'wpforms-save-resume-hide';

					return $classes;
				},
				10,
				2
			);

			$message = ! empty( $form_data['settings']['save_resume_email_settings_message'] ) ? $form_data['settings']['save_resume_email_settings_message'] : Settings::get_default_email_sent_message();
			?>

			<div class="wpforms-save-resume-confirmation">
				<?php if ( $message ) : ?>
					<p><?php echo wp_kses_post( wpautop( $message ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php
			Transient::delete( 'wpforms_save_resume-' . $this->user_uuid );
		}

		return $load_form;
	}

	/**
	 * Load text message if the resume link was expired.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $load_form Indicates whether a form should be loaded.
	 * @param array $form_data Form data.
	 *
	 * @return bool
	 */
	public function display_expired_message( $load_form, $form_data ) {

		if ( ! wpforms_save_resume()->is_enabled( $form_data ) ) {
			return $load_form;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! isset( $_GET['wpforms_resume_entry'] ) ) {
			return $load_form;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.NonceVerification
		$hash     = ! empty( $_GET['wpforms_resume_entry'] ) ? $_GET['wpforms_resume_entry'] : '';
		$entry_id = Entry::get_entry_by_hash( $hash );

		$entry_data = $entry_id !== 0 ? wpforms()->get( 'entry' )->get( $entry_id ) : [];

		if ( ! empty( $entry_data ) ) {
			return $load_form;
		}

		$message = apply_filters( 'wpforms_save_resume_frontend_expired_message', Settings::get_expired_message(), $form_data );

		printf(
			'<div class="wpforms-save-resume-expired-message %s">%s</div>',
			wpforms_setting( 'disable-css', '1' ) === '1' ? 'wpforms-save-resume-expired-message-full' : '',
			wp_kses_post( wpautop( $message ) )
		);

		return $load_form;
	}

	/**
	 * Filter locked state.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_locked_filter() {

		return ! empty( Transient::get( 'wpforms_save_resume-' . $this->user_uuid ) );
	}
}
