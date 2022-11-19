<?php
if ( class_exists( 'WPForms_Views_License' ) ) {
	class WPF_Views_Edit_Entry_License extends WPForms_Views_License{
		public $id = 'edit_entry_license';
		public $item_id = 2087;
		public $version = WPF_VIEWS_EDIT_ENTRY_VERSION;
		public $plugin_file = WPF_VIEWS_EDIT_PLUGIN_FILE;
		public $plugin_folder_name = 'views-for-wpforms-edit-entry';
		public $plugin_file_name = 'wpf-views-edit-entry.php';

	}

	new WPF_Views_Edit_Entry_License();
}