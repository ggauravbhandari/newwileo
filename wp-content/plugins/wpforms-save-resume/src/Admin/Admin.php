<?php

namespace WPFormsSaveResume\Admin;

/**
 * The Admin.
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		add_filter( 'wpforms_entries_table_counts', [ $this, 'entries_table_counts' ], 10, 2 );
		add_filter( 'wpforms_entries_table_views', [ $this, 'entries_table_views' ], 10, 3 );
		add_filter( 'wpforms_entry_details_sidebar_details_status', [ $this, 'entries_details_sidebar_status' ], 10, 3 );
		add_filter( 'wpforms_entry_details_sidebar_actions_link', [ $this, 'entries_details_sidebar_actions' ], 10, 3 );
		add_filter( 'wpforms_entries_table_column_status', [ $this, 'entries_table_column_status' ], 10, 2 );

		// Save and Resume button styles for the Gutenberg/Block editor.
		add_action( 'enqueue_block_editor_assets', [ $this, 'gutenberg_enqueues' ] );
	}

	/**
	 * Enable the displaying status for forms which have Partial entries.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $show      Whether to show the Status column or not.
	 * @param object $entry     Entry information.
	 * @param array  $form_data Form data.
	 *
	 * @return bool
	 */
	public function entries_details_sidebar_status( $show, $entry, $form_data ) {

		if ( wpforms_save_resume()->is_enabled( $form_data ) ) {
			return true;
		}

		return $show;
	}

	/**
	 * For partial entries remove the link to resend email notifications.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $links     List of links in sidebar.
	 * @param object $entry     Entry information.
	 * @param array  $form_data Form data.
	 *
	 * @return array
	 */
	public function entries_details_sidebar_actions( $links, $entry, $form_data ) {

		if ( wpforms_save_resume()->is_enabled( $form_data ) ) {
			unset( $links['notifications'] );
		}

		return $links;
	}

	/**
	 * Enable the Status column for forms that have Partial entries.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $show      Whether to show the Status column or not.
	 * @param array $form_data Form data.
	 *
	 * @return bool
	 */
	public function entries_table_column_status( $show, $form_data ) {

		if ( wpforms_save_resume()->is_enabled( $form_data ) ) {
			return true;
		}

		return $show;
	}

	/**
	 * Get counts for partial entries.
	 *
	 * @since 1.0.0
	 *
	 * @param array $counts    Entries count list.
	 * @param array $form_data Form data.
	 *
	 * @return array
	 */
	public function entries_table_counts( $counts, $form_data ) {

		if ( wpforms_save_resume()->is_enabled( $form_data ) ) {
			$counts['partial'] = wpforms()->get( 'entry' )->get_entries(
				[
					'form_id' => absint( $form_data['id'] ),
					'status'  => 'partial',
				],
				true
			);
		}

		return $counts;
	}

	/**
	 * Create view for partial entries.
	 *
	 * @since 1.0.0
	 *
	 * @param array $views     Filters for entries various states.
	 * @param array $form_data Form data.
	 * @param array $counts    Entries count list.
	 *
	 * @return array
	 */
	public function entries_table_views( $views, $form_data, $counts ) {

		if ( wpforms_save_resume()->is_enabled( $form_data ) ) {

			$base = add_query_arg(
				[
					'page'    => 'wpforms-entries',
					'view'    => 'list',
					'form_id' => absint( $form_data['id'] ),
				],
				admin_url( 'admin.php' )
			);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : '';
			$partial = '&nbsp;<span class="count">(<span class="partial-num">' . $counts['partial'] . '</span>)</span>';

			$views['partial'] = sprintf(
				'<a href="%1$s" class="%2$s">%3$s</a>',
				esc_url( add_query_arg( 'status', 'partial', $base ) ),
				$current === 'partial' ? ' current' : '',
				esc_html__( 'Partial', 'wpforms-save-resume' ) . $partial
			);
		}

		return $views;
	}

	/**
	 * Load styles for the Gutenberg editor.
	 *
	 * @since 1.0.0
	 */
	public function gutenberg_enqueues() {

		// Add inline CSS without the need to enquire handler.
		wp_register_style( 'wpforms-save-resume-admin', false );
		wp_enqueue_style( 'wpforms-save-resume-admin' );

		$custom_css = 'div.wpforms-container-full, div.wpforms-container-full .wpforms-form .wpforms-save-resume-button { font-family: sans-serif; font-size: 14px; line-height: 17px; text-decoration: underline; color: #777; cursor: pointer; margin: 0 20px; }';

		wp_add_inline_style( 'wpforms-save-resume-admin', $custom_css );
	}
}
