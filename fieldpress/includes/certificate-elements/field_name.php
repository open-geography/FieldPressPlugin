<?php

if ( ! class_exists( 'cp_field_name_element' ) ) {
	class cp_field_name_element extends CP_Certificate_Template_Elements {

		var $element_name = 'cp_field_name_element';
		var $element_title = '';

		function on_creation() {
			$this->element_title = apply_filters( 'fieldpress_field_name_element_title', __( 'Field Trip Name', 'cp' ) );
		}

		function template_content( $field_id = false, $user_id = false, $preview = false ) {

		}

	}

	cp_register_template_element( 'cp_field_name_element', __( 'Field Trip Name', 'cp' ) );
}