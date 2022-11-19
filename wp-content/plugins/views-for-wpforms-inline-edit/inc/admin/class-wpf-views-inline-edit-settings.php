<?php


class WPF_Views_Inline_Edit_Settings {

	public function __construct() {
		add_filter( 'wpforms_views_settings_defaults', array( $this, 'add_fields' ), 10 );

	}

	function add_fields( $fields ) {
		$fields['access'] ['inline_edit'] = array(
			'id'   => 'wpforms_views_inline_edit',
			'name' => esc_html__( 'Inline Edit Entries', 'wpforms-views' ),
			'type'      => 'select',
			'choicesjs' => true,
			'multiple' => true,
			'options'   =>wpforms_views_get_user_roles_options(),
			'selected' => wpforms_views_get_roles_with_capabilites( 'wpforms_views_inline_edit' )
		);
		$fields['license'] ['inline_edit_license'] = array(
			'id'   => 'inline_edit_license',
			'name'    => esc_html__( 'Inline Edit Entry License', 'wpforms-views' ),
			'type'    => 'license',
		);
		return $fields;
	}

}

new WPF_Views_Inline_Edit_Settings();
