<?php
class WPF_Views_Edit_Entry_Prefill_Fields {


	public $field_type ;

	public function __construct() {
		$this->add_hooks();
	}


	/**
	 * Add the filter to display values
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	protected function add_hooks() {
		add_filter( "wpf-views/edit-entry/prefill-{$this->field_type}-field-value", array( $this, 'prefill_value_by_field', ), 10, 5 );
	}


	/**
	 * Get the value, that is used to prefill via dynamic or fallback population.
	 * Based on field data and current properties.
	 * Normal choices section.
	 *
	 * @since 1.6.0
	 *
	 *
	 * @param string  $get_value  Value from a GET param, always a string, sanitized.
	 * @param array   $properties Field properties.
	 * @param array   $field      Current field specific data.
	 * @return array Modified field properties.
	 */
	protected function get_field_populated_single_property_value_normal_choices( $get_value, $properties, $field ) {

		$default_key = null;

		// For fields that have normal choices we need to add extra logic.
		foreach ( $field['choices'] as $choice_key => $choice_arr ) {
			$choice_value_key = isset( $field['show_values'] ) ? 'value' : 'label';
			if (
				(
					isset( $choice_arr[ $choice_value_key ] ) &&
					strtoupper( sanitize_text_field( $choice_arr[ $choice_value_key ] ) ) === strtoupper( $get_value )
				) ||
				(
					empty( $choice_arr[ $choice_value_key ] ) &&
					/* translators: %d - choice number. */
					$get_value === sprintf( esc_html__( 'Choice %d', 'wpforms-lite' ), (int) $choice_key )
				)
			) {
				$default_key = $choice_key;
				// Stop iterating over choices.
				break;
			}
		}

		// Redefine default choice only if population value has changed anything.
		if ( null !== $default_key ) {
			$this->field_prefill_remove_choices_defaults( $field, $properties );
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
	 * As we are processing user submitted data - ignore all admin-defined defaults.
	 * Preprocess choices-related fields only.
	 *
	 * @since 1.5.0
	 *
	 * @param array $field      Field data and settings.
	 * @param array $properties Properties we are modifying.
	 */
	public function field_prefill_remove_choices_defaults( $field, &$properties ) {


		if (
			! empty( $field['dynamic_choices'] ) ||
			! empty( $field['choices'] )
		) {
			array_walk_recursive(
				$properties['inputs'],
				function ( &$value, $key ) {

					if ( 'default' === $key ) {
						$value = false;
					}
					if ( 'wpforms-selected' === $value ) {
						$value = '';
					}
				}
			);
		}
	}


}
