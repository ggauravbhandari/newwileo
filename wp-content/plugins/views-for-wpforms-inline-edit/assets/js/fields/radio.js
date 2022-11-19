/**
Radio buttons list (modified from checklist)
Internally value stored as javascript array of values.
@class radiolist
@extends list
@final
@example
<a href="#" id="options" data-type="radiolist" data-pk="1" data-url="/post" data-original-title="Select option"></a>
<script>
$(function() {
  $('#options').editable({
	value   : [2, 3],
	name    : 'myradio',
	source  : [
	  {value: 1, text: 'option1'},
	  {value: 2, text: 'option2'},
	  {value: 3, text: 'option3'}
	]
  });
});
</script>
**/
( function ( $ ) {
	var Radiolist = function ( options ) {
		this.init( 'radiolist', options, Radiolist.defaults );
	};
	$.fn.editableutils.inherit( Radiolist, $.fn.editabletypes.checklist );

	$.extend( Radiolist.prototype, {
		renderList: function () {
			this.$input = this.$tpl.find( 'input' );
			this.$fieldid = $( this.options.scope ).attr( 'data-fieldid' );
			this.$fieldformat = $( this.options.scope ).attr( 'data-fieldformat' );
		},

		input2value: function () {
			return this.$input.filter( ':checked' ).val();
		},
		str2value: function ( str ) {
			return str || null;
		},
		value2input: function ( value ) {
			//this.$input.filter( '[name="wpforms[fields][' + this.$fieldid + '][first]"]' ).val( value[ 'first' ] );
			this.$input.val( [ value ] );
		},
		value2str: function ( value ) {
			return value || '';
		},
		//collect text of checked boxes
		value2htmlFinal: function ( value, element ) {
			checked = $.fn.editableutils.itemsByValue( value, this.sourceData );
			if ( checked.length ) {
				$( element ).html( $.fn.editableutils.escape( value ) );
			} else {
				$( element ).empty();
			}
		},


	} );

	Radiolist.defaults = $.extend( {}, $.fn.editabletypes.list.defaults);

	$.fn.editabletypes.radiolist = Radiolist;

}( window.jQuery ) );
