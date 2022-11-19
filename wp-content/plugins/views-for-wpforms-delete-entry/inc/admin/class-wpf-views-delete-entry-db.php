<?php
if ( class_exists( 'WPForms_DB' ) ) {
	class WPF_Views_Delete_Entry_Db extends WPForms_DB {
		private static $instance;


		public function __construct() {

			global $wpdb;

			$this->table_name  = $wpdb->prefix . 'wpforms_entries';
			$this->primary_key = 'entry_id';
			$this->type        = 'entries';

		}


		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WPF_Views_Delete_Entry_Db ) ) {
				self::$instance = new WPF_Views_Delete_Entry_Db();

			}

			return self::$instance;
		}

		/**
		 * Get table columns.
		 *
		 * @since 1.0.0
		 * @since 1.5.7 Added an `Entry Notes` column.
		 */
		public function get_columns() {

			return array(
				'entry_id'      => '%d',
				'notes_count'   => '%d',
				'form_id'       => '%d',
				'post_id'       => '%d',
				'user_id'       => '%d',
				'status'        => '%s',
				'type'          => '%s',
				'viewed'        => '%d',
				'starred'       => '%d',
				'fields'        => '%s',
				'meta'          => '%s',
				'date'          => '%s',
				'date_modified' => '%s',
				'ip_address'    => '%s',
				'user_agent'    => '%s',
				'user_uuid'     => '%s',
			);
		}

		public function delete( $entry_id = 0 ) {

			$entry = wpforms()->entry->get( sanitize_text_field( $entry_id ) );
			if ( ! ( get_current_user_id() == $entry->user_id ) && ! ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_delete_entries' )  ) ) {
				return false;
			}

			\WPForms_Field_File_Upload::delete_uploaded_files_from_entry( $entry_id );

			$entry  = parent::delete( $entry_id );
			$meta   = wpforms()->entry_meta->delete_by( 'entry_id', $entry_id );
			$fields = wpforms()->entry_fields->delete_by( 'entry_id', $entry_id );

			WPForms\Pro\Admin\DashboardWidget::clear_widget_cache();
			WPForms\Pro\Admin\Entries\DefaultScreen::clear_widget_cache();

			return $entry && $meta && $fields;
		}

	}

	function WPF_Views_Delete_Entry_Db() {
		return WPF_Views_Delete_Entry_Db::instance();
	}
}
