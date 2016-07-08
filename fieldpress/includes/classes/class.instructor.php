<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Instructor' ) ) {

	class Instructor extends WP_User {

		var $first_name = '';
		var $last_name = '';
		var $fields_number = 0;

		function __construct( $ID, $name = '' ) {
			if ( $ID != 0 ) {
				parent::__construct( $ID, $name );
			}

			/* Set meta vars */

			$this->first_name     = get_user_meta( $ID, 'first_name', true );
			$this->last_name      = get_user_meta( $ID, 'last_name', true );
			$this->fields_number = Instructor::get_fields_number( $ID );
		}

		function Instructor( $ID, $name = '' ) {
			$this->__construct( $ID, $name );
		}

		static function get_field_meta_keys( $user_id ) {
			$meta = get_user_meta( $user_id );
			$meta = array_filter( array_keys( $meta ), array( 'Instructor', 'filter_field_meta_array' ) );

			return $meta;
		}

		static function filter_field_meta_array( $var ) {
			global $wpdb;
			if ( preg_match( '/^field\_/', $var ) || preg_match( '/^' . $wpdb->prefix . 'field\_/', $var ) ||
			     ( is_multisite() && ( defined( 'BLOG_ID_CURRENT_SITE' ) && BLOG_ID_CURRENT_SITE == get_current_blog_id() ) && preg_match( '/^' . $wpdb->base_prefix . 'field\_/', $var ) )
			) {
				return $var;
			}
		}

		function get_assigned_fields_ids( $status = 'all' ) {
			global $wpdb;
			$assigned_fields = array();

			$fields = Instructor::get_field_meta_keys( $this->ID );

			foreach ( $fields as $field ) {
				$field_id = $field;

				// Dealing with multisite nuances
				if ( is_multisite() ) {
					// Primary blog?
					if ( defined( 'BLOG_ID_CURRENT_SITE' ) && BLOG_ID_CURRENT_SITE == get_current_blog_id() ) {
						$field_id = str_replace( $wpdb->base_prefix, '', $field_id );
					} else {
						$field_id = str_replace( $wpdb->prefix, '', $field_id );
					}
				}

				$field_id = (int) str_replace( 'field_', '', $field_id );

				if ( ! empty( $field_id ) ) {
					if ( $status !== 'all' ) {
						if ( get_post_status( $field_id ) == $status ) {
							$assigned_fields[] = $field_id;
						}
					} else {
						$assigned_fields[] = $field_id;
					}
				}
			}

			return $assigned_fields;
		}

		function get_accessable_fields() {

			$fields = $this->get_assigned_fields_ids();
			$new_field_array = array();

			foreach( $fields as $field ) {

				$can_update				 = FieldPress_Capabilities::can_update_field( $field->ID, $this->ID );
				$can_delete				 = FieldPress_Capabilities::can_delete_field( $field->ID, $this->ID );
				$can_publish			 = FieldPress_Capabilities::can_change_field_status( $field->ID, $this->ID );
				$can_view_stop			 = FieldPress_Capabilities::can_view_field_stops( $field->ID, $this->ID );
				$my_field				 = FieldPress_Capabilities::is_field_instructor( $field->ID, $this->ID );
				$creator				 = FieldPress_Capabilities::is_field_creator( $field->ID, $this->ID );

				if ( !$my_field && !$creator && !$can_update && !$can_delete && !$can_publish && !$can_view_stop ) {
					continue;
				} else {
					$new_field_array[] = $field;
				}
			}

			return $new_field_array;

		}

		function unassign_from_field( $field_id = 0 ) {
			$global_option = ! is_multisite();
			delete_user_option( $this->ID, 'field_' . $field_id, $global_option );
			delete_user_option( $this->ID, 'enrolled_field_date_' . $field_id, $global_option );
			delete_user_option( $this->ID, 'enrolled_field_class_' . $field_id, $global_option );
			delete_user_option( $this->ID, 'enrolled_field_group_' . $field_id, $global_option );

			// Legacy
			delete_user_meta( $this->ID, 'field_' . $field_id );
			delete_user_meta( $this->ID, 'enrolled_field_date_' . $field_id );
			delete_user_meta( $this->ID, 'enrolled_field_class_' . $field_id );
			delete_user_meta( $this->ID, 'enrolled_field_group_' . $field_id );
		}

		function unassign_from_all_fields() {
			$fields = $this->get_assigned_fields_ids();
			foreach ( $fields as $field_id ) {
				$this->unassign_from_field( $field_id );
			}
		}

		//Get number of instructor's assigned fields
		static function get_fields_number( $user_id = false ) {

			if ( ! $user_id ) {
				return 0;
			}

			$fields_count = count( Instructor::get_field_meta_keys( $user_id ) );

			return $fields_count;
		}

		function is_assigned_to_field( $field_id, $instructor_id ) {
			$instructor_field_id = get_user_option( 'field_' . $field_id, $instructor_id );
			if ( ! empty( $instructor_field_id ) ) {
				return true;
			} else {
				return false;
			}
		}

		function delete_instructor( $delete_user = true ) {
			/* if ( $delete_user ) {
			  wp_delete_user( $this->ID ); //without reassign
			  }else{//just delete the meta which says that user is an instructor */
			$global_option = ! is_multisite();
			delete_user_option( $this->ID, 'role_ins', 'instructor', $global_option );
			// Legacy
			delete_user_meta( $this->ID, 'role_ins', 'instructor' );
			$this->unassign_from_all_fields();
			FieldPress::instance()->drop_instructor_capabilities( $this->ID );
			//}
		}

		public static function instructor_by_hash( $hash ) {
			global $wpdb;
			$sql     = $wpdb->prepare( "SELECT user_id FROM " . $wpdb->prefix . "usermeta WHERE meta_key = %s", $hash );
			$user_id = $wpdb->get_var( $sql );

			if ( ! empty( $user_id ) ) {
				return ( new Instructor( $user_id ) );
			} else {
				return false;
			}
		}

		public static function instructor_by_login( $login ) {
			$user = get_user_by( 'login', $login );
			if ( ! empty( $user ) ) {
				// relying on core's caching here
				return ( new Instructor( $user->ID ) );
			} else {
				return false;
			}
		}

		public static function create_hash( $user_id ) {
			$user          = get_user_by( 'id', $user_id );
			$hash          = md5( $user->user_login );
			$global_option = ! is_multisite();
			/*
			 * Just in case someone is actually using this hash for something,
			 * we'll populate it with current value. Will be an empty array if
			 * nothing exists. We're only interested in the key anyway.
			 */
			update_user_option( $user->ID, $hash, get_user_option( $hash, $user->ID ), $global_option );
		}

	}

}
?>