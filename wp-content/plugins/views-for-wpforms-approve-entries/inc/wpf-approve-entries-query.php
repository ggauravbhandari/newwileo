<?php

class WPF_Approve_Entries_Query {

	public function __construct() {
		add_filter( 'wpforms_view_query_joins', array( $this, 'approved_entries_join' ), 10, 2 );
		add_filter( 'wpforms_view_query_where', array( $this, 'approved_entries_where' ), 10, 2 );

		add_action( 'wp_ajax_wpf_views_approve_entry', array( $this, 'approve_entry' ) );
	}

	function approved_entries_join( $join, $args ) {

		if ( ! empty( $args['view_settings'] ) ) {
			$approved = $args['view_settings']->viewSettings->multipleentries->approvedSubmissions;
			if (  $approved == '1' ) {
				$entry_meta_table = WPForms_Views_Common::get_entry_meta_table_name();
				$join[] = "LEFT JOIN `$entry_meta_table` AS `apprvtable` ON ( `apprvtable`.`entry_id` = `t1`.`entry_id` AND `apprvtable`.`type` = 'approve') ";

			}
		}
		return $join;
	}

	function approved_entries_where( $where, $args ) {

		if ( ! empty( $args['view_settings'] ) ) {
			$approved = $args['view_settings']->viewSettings->multipleentries->approvedSubmissions;
			if (  $approved == '1' ) {
				$where[] = "(`apprvtable`.`data` = '1')";
			}
		}
		return $where;
	}

	function approve_entry() {
		if ( WPForms_Views_Roles_Capabilities::current_user_can( 'wpforms_views_edit_entries' ) ) {

			$entry_id = absint( $_POST['entry_id'] );
			$approval_status = absint( $_POST['approval_status'] );
			global $wpdb;
			$entry_meta_table = WPForms_Views_Common::get_entry_meta_table_name();
			$results = $wpdb->get_results( "SELECT * FROM {$entry_meta_table} where `entry_id`={$entry_id} && `type`='approve'" );
			// approval status row exists
			if ( ! empty( $results ) && is_array( $results ) ) {
				$meta_id = $results[0]->id;
				wpforms()->entry_meta->update( $meta_id, array( 'data'=>$approval_status ) );
			}else {
				// Add new approval status
				wpforms()->entry_meta->add(
					array(
						'entry_id' => $entry_id,
						'form_id'  => absint( $_POST['form'] ),
						'user_id'  => get_current_user_id(),
						'type'     => 'approve',
						'data'     => $approval_status,
					),
					'entry_meta'
				);
			}

		}
			die;
	}


}

new WPF_Approve_Entries_Query();
