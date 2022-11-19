<?php

class WPF_Views_Single_Entry_View {
	public $entry;
	public $form_data;
	public function __construct() {
		add_filter( 'parse_query', array( $this, 'parse_query_fix_frontpage' ), 10 );
		add_action( 'init', array( $this, 'wpf_add_rewrite_endpoint'  ), 10 );

		add_filter( 'wpf_views_single_entry_content' , array( $this, 'single_entry_content'  ), 10, 3 );
	}

	function single_entry_content( $content, $entry_id, $view_settings ) {
		$this->form = wpforms()->form->get( absint( $view_settings->formId ) );
		// If the form doesn't exists, abort.
		if ( empty( $this->form ) ) {
			return $content;
		}

		// Pull and format the form data out of the form object.
		$this->form_data = ! empty( $this->form->post_content ) ? wpforms_decode( $this->form->post_content ) : '';

		$entry = wpforms()->entry->get( absint( $entry_id ) );
		$single_loop_rows = $view_settings->sections->singleloop->rows;
		if ( ! empty( $entry ) ) {
			$this->entry = $entry;
			$go_back_text = ! empty( $view_settings->viewSettings->singleEntry->backLinkLabel ) ? $view_settings->viewSettings->singleEntry->backLinkLabel : 'Go back';
			$content .= '<div class="single-entry-view-cont">';
			$content .= '<div class="single-entry-go-back-cont"><a href="' . get_the_permalink() . '">' . $go_back_text . '</a></div>';
			foreach ( $single_loop_rows as $row_id ) {
				$content .= $this->get_grid_row_html( $row_id, $view_settings );
			}
			$content .= '</div>';
		}
		return $content;
	}

	function get_grid_row_html( $row_id, $view_settings ) {
		$row_columns = $view_settings->rows->{$row_id}->cols;

		$row_content = '<div class="pure-g wpforms-view-row">';
		foreach ( $row_columns as $column_id ) {
			$row_content .= $this->get_grid_column_html( $column_id, $view_settings );
		}
		$row_content .= '</div>'; // row ends
		return $row_content;
	}

	function get_grid_column_html( $column_id, $view_settings ) {
		$column_size = $view_settings->columns->{$column_id}->size;
		$column_fields = $view_settings->columns->{$column_id}->fields;

		$column_content = '<div class=" wpforms-view-col pure-u-1 pure-u-md-' . $column_size . '">';


		foreach ( $column_fields as $field_id ) {

			$column_content .= $this->get_field_html( $field_id, $view_settings );

		}
		$column_content .= '</div>'; // column ends
		return $column_content;
	}

	function get_field_html( $field_id, $view_settings ) {
		$entry = $this->entry;
		$field = $view_settings->fields->{$field_id};
		$form_field_id = $field->formFieldId;
		$fieldSettings = $field->fieldSettings;
		$label = $fieldSettings->useCustomLabel ? $fieldSettings->label : $field->label;
		$class = $fieldSettings->customClass;
		$field_html = '';
		// Entry field values are in JSON, so we need to decode.
		$entry_fields = json_decode( $entry->fields, true );

		// Return if Hide Empty Fields is activated & field value is empty
		if ( $this->is_form_field( $entry_fields, $form_field_id ) && empty( $entry_fields[$form_field_id ]['value'] ) ) {
			if ( ! empty( $view_settings->viewSettings->multipleentries->hideEmptyFields ) ) {
				return '';
			}
		}

		$field_html .= '<div class="wpforms-view-field-cont  field-' . $form_field_id . ' ' . $class . '">';


		$form_field_type = isset( $entry_fields[$form_field_id ] ) ? $entry_fields[$form_field_id ]['type']: $form_field_id;
		if ( ! empty( $label ) ) {
			$field_html .= '<div class="wpforms-view-field-label">' . $label . '</div>';
		}
		$field_html .= '<div class="wpforms-view-field-value wpforms-view-field-type-' . $form_field_type . '-value">';

		$field_value = apply_filters( "wpf-views/field-value", '', $field_id, $entry, $view_settings, $this );

		$field_value = apply_filters( "wpf-views/{$form_field_type}-value", $field_value, $field_id, $entry, $view_settings, $this );

		$field_html .= $field_value;

		$field_html .= '</div>';


		$field_html .= '</div>';

		return $field_html;
	}
		function is_form_field( $entry_fields, $form_field_id ) {
		if ( ! empty( $entry_fields ) && is_array( $entry_fields ) && is_numeric( $form_field_id ) && isset( $entry_fields[$form_field_id ] ) ) {
			return true;
		}
		return false;
	}



	function wpf_add_rewrite_endpoint() {
		global $wp_rewrite;

		$endpoint = 'entry';
		if ( in_array( array( EP_PERMALINK | EP_PERMALINK | EP_ROOT, $endpoint, $endpoint ), $wp_rewrite->endpoints ) ) {
			return;
		}
		add_rewrite_endpoint( $endpoint, EP_PAGES | EP_PERMALINK | EP_ROOT );
		$wp_rewrite->flush_rules();
	}


	/**
	 * Allow WPForms Views entry endpoints on the front page of a site
	 *
	 * @link  https://core.trac.wordpress.org/ticket/23867 Fixes this core issue
	 * @link https://wordpress.org/plugins/cpt-on-front-page/ Code is based on this
	 *
	 * @since 1.17.3
	 *
	 * @param WP_Query &$query (passed by reference)
	 *
	 * @return void
	 */
	public function parse_query_fix_frontpage( &$query ) {
		global $wp_rewrite;

		$is_front_page = ( $query->is_home || $query->is_page );
		$show_on_front = ( 'page' === get_option('show_on_front') );
		$front_page_id = get_option('page_on_front');

		if (  $is_front_page && $show_on_front && $front_page_id ) {

			// Force to be an array, potentially a query string ( entry=16 )
			$_query = wp_parse_args( $query->query );

			// pagename can be set and empty depending on matched rewrite rules. Ignore an empty pagename.
			if ( isset( $_query['pagename'] ) && '' === $_query['pagename'] ) {
				unset( $_query['pagename'] );
			}


			$ignore =  array( 'preview', 'page', 'paged', 'cpage' );
				$endpoints = self::get( $wp_rewrite, 'endpoints' );

			foreach ( (array) $endpoints as $endpoint ) {
				$ignore[] = $endpoint[1];
			}
			unset( $endpoints );

			// Modify the query if:
			// - We're on the "Page on front" page (which we are), and:
			// - The query is empty OR
			// - The query includes keys that are associated with registered endpoints. `entry`, for example.
			if ( empty( $_query ) || ! array_diff( array_keys( $_query ), $ignore ) ) {

				$qv =& $query->query_vars;

				// Prevent redirect when on the single entry endpoint
					$single_entry_id = get_query_var( 'entry' );
				if( $single_entry_id ) {
					add_filter( 'redirect_canonical', '__return_false' );
				}

				$query->is_page = true;
				$query->is_home = false;
				$qv['page_id']  = $front_page_id;

				// Correct <!--nextpage--> for page_on_front
				if ( ! empty( $qv['paged'] ) ) {
					$qv['page'] = $qv['paged'];
					unset( $qv['paged'] );
				}
			}

			// reset the is_singular flag after our updated code above
			$query->is_singular = $query->is_single || $query->is_page || $query->is_attachment;
		}
	}

		/**
	 * Grab a value from an array or an object or default.
	 *
	 * Supports nested arrays, objects via / key delimiters.
	 *
	 * @param array|object|mixed $array The array (or object). If not array or object, returns $default.
	 * @param string $key The key.
	 * @param mixed $default The default value. Default: null
	 *
	 * @return mixed  The value or $default if not found.
	 */
	public static function get( $array, $key, $default = null ) {
		if ( ! is_array( $array ) && ! is_object( $array ) ) {
			return $default;
		}

		/**
		 * Try direct key.
		 */
		if ( is_array( $array ) || $array instanceof \ArrayAccess ) {
			if ( isset( $array[ $key ] ) ) {
				return $array[ $key ];
			}
		} else if ( is_object( $array ) ) {
			if ( property_exists( $array, $key ) ) {
				return $array->$key;
			}
		}

		/**
		 * Try subkeys after split.
		 */
		if ( count( $parts = explode( '/', $key, 2 ) ) > 1 ) {
			return self::get( self::get( $array, $parts[0] ), $parts[1], $default );
		}

		return $default;
	}


}

new WPF_Views_Single_Entry_View();
