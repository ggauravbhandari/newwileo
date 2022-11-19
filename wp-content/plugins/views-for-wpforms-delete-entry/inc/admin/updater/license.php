<?php
if ( class_exists( 'WPForms_Views_License' ) ) {
	class WPF_Views_Delete_Entry_License extends WPForms_Views_License{
		public $id = 'delete_entry_license';
		public $item_id = 4173;
		public $version = WPF_VIEWS_DELETE_ENTRY_VERSION;
		public $plugin_file = WPF_VIEWS_DELETE_ENTRY_PLUGIN_FILE;
		public $plugin_folder_name = 'views-for-wpforms-delete-entry';
		public $plugin_file_name = 'wpf-views-delete-entry.php';
	}

	new WPF_Views_Delete_Entry_License();
}