<?php
if ( class_exists( 'WPForms_Views_License' ) ) {
	class WPForms_Views_DataTables_License extends WPForms_Views_License{
		public $id = 'datatables_license';
		public $item_id = 2015;
		public $version = WPFORMS_VIEWS_DATATABLES_VERSION;
		public $plugin_file = WPFORMS_VIEWS_DATATABLES_PLUGIN_FILE;
		public $plugin_folder_name = 'views-for-wpforms-datatables';
		public $plugin_file_name = 'wpforms-views-datatables.php';
	}

	new WPForms_Views_DataTables_License();
}