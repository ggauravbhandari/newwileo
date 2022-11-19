( function( jQuery ) {
	'use strict';

	jQuery( document ).ready( function( $ ) {
		var self = {};

		self.init = function() {
			$.fn.editableContainer.Inline.prototype.containerClass = 'wpf-editable-container  editable-container editable-inline';
			$.fn.editableContainer.Popup.prototype.containerClass = 'wpf-editable-container  editable-container editable-popup wpf-editable-popover ';
			//$.fn.editableContainer.Popup.prototype.innerCss = ".ui-tooltip-content .wpf-views-container-full"
			$.fn.editableform.buttons ='<button type="submit" class="editable-submit">Update</button><button type="button" class="editable-cancel">cancel</button>';
			  $.fn.editableform.template = '<div class="wpf-views-container-full"><form class="form-inline editableform wpforms-form">'+
				'<div class="control-group">' +
				'<div><div class="editable-input"></div><div class="editable-buttons"></div></div>'+
				'<div class="editable-error-block"></div>' +
				'</div>' +
				'</form></div>';

			self.setInlineEditableFields();
			self.initInlineEditable();
			self.toggleInlineEdit();
		}
		self.setInlineEditableFields = function() {
		$( '.wpf-inline-edit-view [id^=wpf-views-inline-editable-]' ).each( function( i, val ) {
			var editableOptions;
			var $field = $( this );
			var form_id = $field.data( 'formid' ), field_id = $field.data( 'fieldid' );
			var view_id = $field.data( 'viewid' );
			var entry_id = $field.data( 'entryid' );
			var field_type = $field.data( 'type' );
			var form_field_type = $field.data( 'form-field-type' );
			editableOptions = {
				pk: entry_id,
				url: wpf_inline_edit.url,
				success: function( response ) {
					// console.log(response.errors);
					if ( response.errors ) {
						return self.getWPErrorMessage( response.errors );
					}
				},
				savenochange: true,
			}

			editableOptions.params =  {
					wpf_inline_edit_field: 'true',
					action: 'wpforms_views_inline_edit',
					nonce:wpf_inline_edit.nonce,
					type: field_type,
					form_field_type:form_field_type,
					form_id: form_id,
					field_id: field_id,
					view_id: view_id,
				};
				var tplName = field_type;
				if( form_field_type == 'radio'){
			        tplName = form_field_type;
				}

				var template = wpf_inline_edit.templates[ tplName + '_' + form_id + '_' + field_id ];

				editableOptions.tpl = template;

			$( this ).editable(editableOptions);
		} );
	}

	self.initInlineEditable = function(){
		$( '.wpf-inline-edit-view' ).each( function() {
			$(this).find( '[id^=wpf-views-inline-editable-]' ).editable( 'disable' );
		})

	}

	self.toggleInlineEdit = function() {
		$( '.inline-edit-enable' ).on( 'click', function ( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			if($(this).data('status')==='disabled'){
				$( '.wpf-inline-edit-view').find( '[id^=wpf-views-inline-editable-]' ).editable( 'enable' );
				$(this).data('status', 'enabled');
				$(this).text('Disable Inline Edit');
			}else{
				$( '.wpf-inline-edit-view').find( '[id^=wpf-views-inline-editable-]' ).editable( 'disable' );
				$(this).data('status', 'disabled');
				$(this).text('Enable Inline Edit');
			}

		})
	}

			/**
		 * From an instance of WP_Error, get the first error message
		 * Like the equivalent WP_Error method, get the first item from the first error
		 *
		 * @return {string} WP_Error message
		 */
			 self.getWPErrorMessage = function( $wpError ) {
				for ( var error in $wpError ) {
					if ( $wpError.hasOwnProperty( error ) ) {
						return $wpError[ error ][ 0 ];
					}
				}
			};
			$( self.init );
	})

}( jQuery ) );