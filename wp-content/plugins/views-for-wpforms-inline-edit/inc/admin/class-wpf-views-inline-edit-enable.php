<?php

class WPF_VIEWS_INLINE_EDIT_Enable {

	public function __construct() {
		if ( is_admin() ) {
			add_filter('wpforms_views_config',  array( $this, 'add_to_addon_list' ) );
		}
	}


	function add_to_addon_list( $view_config ){
		$view_config['addons'][] = 'views_inline_edit';
		return $view_config;
	}

}

new WPF_VIEWS_INLINE_EDIT_Enable();
