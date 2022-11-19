/**
 Name editable input.
 Internally value stored as {prefix: "Sir", first: "John", middle: "Millenium", last: "Doe", suffix: "II" }

 @class name
 @extends abstractinput
 @final
 @example https://github.com/vitalets/x-editable/tree/develop/dist/inputs-ext/address
 **/
( function ( $ ) {
	"use strict";

	var Name = function ( options ) {
		this.init( 'name', options, Name.defaults );
	};

	//inherit from Abstract input
	$.fn.editableutils.inherit( Name, $.fn.editabletypes.abstractinput );

	$.extend( Name.prototype, {
		/**
		 Renders input from tpl

		 @method render()
		 **/
		render: function () {

			this.$input = this.$tpl.find( 'input' );
			this.$fieldid = $( this.options.scope ).attr( 'data-fieldid' );
			this.$fieldformat = $( this.options.scope ).attr( 'data-fieldformat' );


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

			// In case of simple name field set value & return
			if( this.$fieldformat == 'simple'){
				$( element ).text( value );
				return ;
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

			if ( !value ) {
				return;
			}
			switch ( this.$fieldformat ) {
				case 'first-middle-last':
					this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][first]"]' ).val( value[ 'first' ] );
					this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][middle]"]' ).val( value[ 'middle' ] );
					this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][last]"]' ).val( value[ 'last' ] );
					break;
				case 'first-last':
					this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][first]"]' ).val( value[ 'first' ] );
					this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][last]"]' ).val( value[ 'last' ] );
					break;
				default:
					this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + ']"]' ).val( value[ 'value' ] );
					break;

			}



		},

		/**
		 Returns value of input.

		 @method input2value()
		 **/
		input2value: function () {

			var value = {};
			switch ( this.$fieldformat ) {
				case 'first-middle-last':
					value = {
						first: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][first]"]' ).val(),
						middle: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][middle]"]' ).val(),
						last: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][last]"]' ).val()
					}
					break;
				case 'first-last':
					value = {
						first: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][first]"]' ).val(),
						last: this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][last]"]' ).val()
					}
					break;
				default:
					value = this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + ']"]' ).val();
					break;

			}

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

	Name.defaults = $.extend( {}, $.fn.editabletypes.abstractinput.defaults );

	$.fn.editabletypes.name = Name;

}( window.jQuery ) );
