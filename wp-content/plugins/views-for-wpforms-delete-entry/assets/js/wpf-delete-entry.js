( function ( $ ) {
	$( function () {
		$( document ).on( 'click', '.views-delete-entry', function ( e ) {
			e.preventDefault();
			var conf = confirm( 'Are you sure you want to delete this entry?' )
			if ( conf ) {
				var entryId = $(this).data('wpfentry_id');
				data = {
					action: 'wpf_views_delete_entry',
					entryId: entryId
				};
				$.post( wpf_views_delete.ajaxurl, data, function ( resp ) {
				//console.log(resp);
				if(resp){
					location.reload();
				}
				} )

			}
		} )


	} )
} )( jQuery )