/**
 Address editable input.

 @class name
 @extends abstractinput
 @final
 @example https://github.com/vitalets/x-editable/tree/develop/dist/inputs-ext/address
 **/
( function ( $ ) {
	"use strict";

	var Address = function ( options ) {
		this.init( 'address', options, Address.defaults );
		this.selectField = null;
	};

	//inherit from Abstract input
	$.fn.editableutils.inherit( Address, $.fn.editabletypes.abstractinput );

	$.extend( Address.prototype, {
		/**
		 Renders input from tpl

		 @method render()
		 **/
		render: function () {
			this.$input = this.$tpl.find( 'input' );
			this.selectField = this.$tpl.find( 'select' );
			this.$fieldid = $( this.options.scope ).attr( 'data-fieldid' );
			this.$fieldscheme = $( this.options.scope ).attr( 'data-fieldscheme' );

			this.$input.on( 'keydown.editable', function ( e ) {
				if ( e.which === 13 ) {
					$( this ).closest( 'form' ).submit();
				}
			} );
		},

		/**
		 Default method to show value in element. Can be overwritten by display option.

		 @method value2html(value, element)
		 **/
		value2html: function ( value, element ) {
			if ( !value ) {
				$( element ).empty();
				return;
			}


			var value_array = $.map( value, function ( val, index ) {
				return [ val ];
			} );

			if ( 0 === value_array.length ) {
				$( element ).empty();
				return;
			}
			var html = value_array.join( ' ' );

			$( element ).text( html );
		},

		/**
		 Gets value from element's html

		 @method html2value(html)
		 **/
		html2value: function ( html ) {

			return null;
		},

		/**
		 Converts value to string.
		 It is used in internal comparing (not for sending to server).

		 @method value2str(value)
		 **/
		value2str: function ( value ) {
			var str = '';
			if ( value ) {
				for ( var k in value ) {
					str = str + k + ':' + value[ k ] + ';';
				}
			}
			return str;
		},

		/*
		 Converts string to value. Used for reading value from 'data-value' attribute.

		 @method str2value(str)
		 */
		str2value: function ( str ) {
			/*
			 this is mainly for parsing value defined in data-value attribute.
			 If you will always set value by javascript, no need to overwrite it
			 */
			return str;
		},

		/**
		 Sets value of input.

		 @method value2input(value)
		 @param {mixed} value
		 **/
		value2input: function ( value ) {
			console.log(value);
			if ( !value ) {
				return;
			}
			this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][address1]"]' ).val( value[ 'address1' ] );
			this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][address2]"]' ).val( value[ 'address2' ] );
			this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][city]"]' ).val( value[ 'city' ] );
			this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][postal]"]' ).val( value[ 'postal' ] );

			/*
			If field scheme is US then
			 - State = Dropdown field
			 - Country = not displayed
			Else If field scheme is international
			- State = Input Field
			- Country = Dropdown Field
			*/

			if( this.$fieldscheme  == 'us'){
				this.selectField.filter( '[name="wpforms[fields][' + this.$fieldid + '][state]"]' ).val( value[ 'state' ] );
			}else{
				this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][state]"]' ).val( value[ 'state' ] );
				this.selectField.filter( '[name="wpforms[fields][' + this.$fieldid + '][country]"]' ).val( value[ 'country' ] );
			}

		},

		/**
		 Returns value of input.

		 @method input2value()
		 **/
		input2value: function () {

			var value = {
				address1: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][address1]"]' ).val(),
				address2: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][address2]"]' ).val(  ),
				city: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][city]"]' ).val(  ),
				postal: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][postal]"]' ).val( )
			}
			if( this.$fieldscheme  == 'us'){
				value.state = this.selectField.filter( '[name="wpforms[fields][' + this.$fieldid + '][state]"]' ).val();
				value.country = 'US';
			}else{
				value.state = this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][state]"]' ).val();
				value.country = this.selectField.filter( '[name="wpforms[fields][' + this.$fieldid + '][country]"]' ).val();
			}
			console.log(value);
			return value;
		},

		/**
		 Activates input: sets focus on the first field.

		 @method activate()
		 **/
		activate: function () {
			this.$input.filter( ':first-child' ).focus();
		},

		/**
		 Attaches handler to submit form in case of 'showbuttons=false' mode

		 @method autosubmit()
		 **/
		autosubmit: function () {
			this.$tpl.find( ':input' ).keydown( function ( e ) {
				if ( e.which === 13 ) {
					$( this ).closest( 'form' ).submit();
				}
			} );
		}
	} );

	Address.defaults = $.extend( {}, $.fn.editabletypes.abstractinput.defaults );

	$.fn.editabletypes.address = Address;

}( window.jQuery ) );
