<?php
class WPF_Views_Field_Textarea extends WPF_Views_Field {

	public $field_type ='textarea' ;

		public function get_display_value( $field_value, $_view_field_id, $entry, $_view_settings,$view_Obj ) {
			return nl2br( $field_value );
		}

}
new WPF_Views_Field_Textarea();