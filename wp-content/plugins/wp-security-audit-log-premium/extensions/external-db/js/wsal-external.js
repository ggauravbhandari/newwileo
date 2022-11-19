jQuery( document ).ready( function() {
	var meta_index = 0;
	var occurrence_index = 0;

	jQuery( '#wsal-migrate' ).click( function() {
		var button = this;
		jQuery( button ).addClass( 'disabled' );
		jQuery( button ).val( 'Migrating, Please Wait..' );
		jQuery( '#ajax-response' ).removeClass( 'hidden' );
		MigrateMeta();
	});

	jQuery( '#wsal-migrate-back' ).click( function() {
		var button = this;
		jQuery( button ).addClass( 'disabled' );
		jQuery( button ).val( 'Migrating, Please Wait..' );
		jQuery( '#ajax-response' ).removeClass( 'hidden' );
		MigrateBackMeta();
	});

	function MigrateMeta() {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'MigrateMeta',
				index: meta_index
			},
			success: function( response ) {
				if ( 'undefined' != typeof response.empty ) {
					jQuery( '#ajax-response' ).addClass( 'hidden' );
					alert( externalData.noEventsToMigrate );
					return;
				}

				meta_index = response.index;

				if ( ! response.complete ) {
					MigrateMeta();
				} else {
					MigrateOccurrence();
				}
			}
		});
	}

	function MigrateOccurrence() {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'MigrateOccurrence',
				index: occurrence_index
			},
			success: function( response ) {
				if ( 'undefined' != typeof response.empty ) {
					jQuery( '#ajax-response' ).addClass( 'hidden' );
					alert( externalData.noEventsToMigrate );
					return;
				}

				occurrence_index = response.index;

				if ( ! response.complete ) {
					var responseStr = externalData.eventsMigrated;
					responseStr = responseStr.replace( /%d/g, response.count );
					jQuery( '#ajax-response-counter' ).html( responseStr );
					MigrateOccurrence();
				} else {
					afterCompleted( '#wsal-migrate', externalData.migrationPassed );
					return;
				}
			}
		});
	}

	function MigrateBackMeta() {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'MigrateBackMeta',
				index: meta_index
			},
			success: function( response ) {
				if ( 'undefined' != typeof response.empty ) {
					jQuery( '#ajax-response' ).addClass( 'hidden' );
					alert( externalData.noEventsToMigrate );
					return;
				}

				meta_index = response.index;

				if ( ! response.complete ) {
					MigrateBackMeta();
				} else {
					MigrateBackOccurrence();
				}
			}
		});
	}

	function MigrateBackOccurrence() {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'MigrateBackOccurrence',
				index: occurrence_index
			},
			success: function( response ) {
				if ( 'undefined' != typeof response.empty ) {
					jQuery( '#ajax-response' ).addClass( 'hidden' );
					alert( externalData.noEventsToMigrate );
					return;
				}

				occurrence_index = response.index;

				if ( ! response.complete ) {
					var responseStr = externalData.eventsMigrated;
					responseStr = responseStr.replace( /%d/g, response.count );
					jQuery( '#ajax-response-counter' ).html( responseStr );
					MigrateBackOccurrence();
				} else {
					afterCompleted( '#wsal-migrate-back', externalData.reverseMigrationPassed );
					return;
				}
			}
		});
	}

	function afterCompleted( button, msg ) {
		jQuery( button ).val( 'Migration complete' );
		jQuery( '#ajax-response' ).addClass( 'hidden' );
		alert( msg );
	}

	jQuery( '#wsal-mirroring' ).click( function() {
		var button = this;
		jQuery( button ).val( 'Mirroring...' );
		jQuery( button ).attr( 'disabled', 'disabled' );
		MirroringNow( button );
	});

	function MirroringNow( button ) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			data: {
				action: 'MirroringNow'
			},
			success: function() {
				setTimeout( function() {
					jQuery( button ).val( 'Mirroring Complete!' );
				}, 1000 );
			}
		});
	}

	jQuery( '#wsal-archiving' ).click( function() {
		var button = this;
		jQuery( button ).val( 'Archiving...' );
		jQuery( button ).attr( 'disabled', 'disabled' );
		ArchivingNow( button );
	});

	function ArchivingNow( button ) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			data: {
				action: 'ArchivingNow'
			},
			success: function() {
				setTimeout( function() {
					jQuery( button ).val( 'Archiving Complete!' );
				}, 1000 );
			}
		});
	}

	// Empty buffer button.
	jQuery( '#wsal-empty-buffer' ).click( function( event ) {
		var wsal_empty_butter_btn = jQuery( this );
		event.preventDefault();
		wsal_empty_butter_btn.attr( 'disabled', 'disabled' );

		// Ajax request to remove array of files from file exception list.
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'wsal_empty_buffer',
				nonce: wsal_empty_butter_btn.data( 'empty-buffer-nonce' )
			},
			success: function( data ) {
				if ( data.success ) {
					wsal_empty_butter_btn.val( 'Sent!' );
				} else {
					console.log( data.message );
				}
			},
			error: function( xhr, textStatus, error ) {
				console.log( xhr.statusText );
				console.log( textStatus );
				console.log( error );
			}
		});
	});

	// Test connection button.
	jQuery( '#adapter-test, #mirror-test, #archive-test' ).click( function() {

		// event.preventDefault();

		jQuery( this ).val( 'Testing...' );
		jQuery( this ).attr( 'disabled', true );
		var testType = jQuery( this ).data( 'connection' );
		var nonce = jQuery( '#' + testType + '-test-nonce' ).val();

		wsalTestConnection( this, testType, nonce );
	});

	/**
	 * Test connection with external DBs.
	 *
	 * @param {element} btn   – Button element.
	 * @param {string}  type  – Type of connection to test.
	 * @param {string}  nonce – Connection nonce.
	 */
	function wsalTestConnection( btn, type, nonce ) {

		// Make sure the arguments are not empty.
		if ( ! type.length || ! nonce.length ) {
			return;
		}

		// Ajax request to test connection.
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'wsal_test_connection',
				nonce: nonce,
				connectionType: type
			},
			success: function( data ) {
				if ( data.success ) {
					if ( data.customMessage ) {
						jQuery( btn ).val( data.customMessage );
					} else {
						jQuery( btn ).val( 'Connected!' );
					}
				} else {
					jQuery( btn ).val( 'Connection Failed!' );
					console.log( data.message );
				}
			},
			error: function( xhr, textStatus, error ) {
				jQuery( btn ).val( 'Connection Failed!' );
				console.log( xhr.statusText );
				console.log( textStatus );
				console.log( error );
			}
		});
	}

	/**
	 * Reset Archiving Settings
	 *
	 * @since 3.3
	 */
	jQuery( '#wsal-reset-archiving' ).click( function( event ) {
		var resetBtn = jQuery( this );
		resetBtn.val( externalData.resetProgress );
		event.preventDefault();

		// Ajax request to reset archiving settings.
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			dataType: 'json',
			data: {
				action: 'wsal_reset_archiving',
				wpnonce: jQuery( '#wsal_archive_db' ).val()
			},
			success: function( data ) {
				if ( data.success ) {
					location.reload();
				} else {
					resetBtn.val( externalData.resetFailed );
					console.log( data.message );
				}
			},
			error: function( xhr, textStatus, error ) {
				resetBtn.val( externalData.resetFailed );
				console.log( xhr.statusText );
				console.log( textStatus );
				console.log( error );
			}
		});
	});
});
