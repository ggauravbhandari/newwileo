<?php
if ( class_exists( 'WPForms_Views_License' ) ) {
	class WPF_Views_Inline_Edit_Entry_License extends WPForms_Views_License{
		public $id = 'inline_edit_license';
		public $item_id = 3853;
		public $version = WPF_VIEWS_INLINE_EDIT_VERSION;
		public $plugin_file = WPF_VIEWS_INLINE_EDIT_PLUGIN_FILE;
		public $plugin_folder_name = 'views-for-wpforms-inline-edit';
		public $plugin_file_name = 'wpf-views-inline-edit.php';
	}

	new WPF_Views_Inline_Edit_Entry_License();
}