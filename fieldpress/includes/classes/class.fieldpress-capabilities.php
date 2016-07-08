<?php

/**
 * @copyright Incsub ( http://incsub.com/ )
 *
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 ( GPL-2.0 )
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA 02110-1301 USA
 *
 */

/**
 * Helper class for working with FieldPress capabilities.
 *
 * @since 1.0.0
 *
 * @return object
 */
class FieldPress_Capabilities {

	public static $capabilities = array(
		'instructor' => array(
			/* General */
			'fieldpress_dashboard_cap'								 => 1,
			'fieldpress_fields_cap'								 => 1,
			'fieldpress_instructors_cap'							 => 1,
			'fieldpress_students_cap'								 => 1,
			'fieldpress_assessment_cap'							 => 1,
			'fieldpress_reports_cap'								 => 1,
			'fieldpress_notifications_cap'							 => 1,
			'fieldpress_discussions_cap'							 => 1,
			'fieldpress_settings_cap'								 => 1,
			/* Field Trips */
			'fieldpress_create_field_cap'							 => 1,
			'fieldpress_update_field_cap'							 => 1,
			'fieldpress_update_my_field_cap'						 => 1,
			'fieldpress_update_all_fields_cap'					 => 0, // NOT IMPLEMENTED YET
			'fieldpress_delete_field_cap'							 => 0,
			'fieldpress_delete_my_field_cap'						 => 1,
			'fieldpress_delete_all_fields_cap'					 => 0, // NOT IMPLEMENTED YET
			'fieldpress_change_field_status_cap'					 => 0,
			'fieldpress_change_my_field_status_cap'				 => 1,
			'fieldpress_change_all_fields_status_cap'				 => 0, // NOT IMPLEMENTED YET
			/* Stops */
			'fieldpress_create_field_stop_cap'					 => 1,
			'fieldpress_view_all_stops_cap'						 => 0,
			'fieldpress_update_field_stop_cap'					 => 1,
			'fieldpress_update_my_field_stop_cap'					 => 1,
			'fieldpress_update_all_fields_stop_cap'				 => 0, // NOT IMPLEMENTED YET
			'fieldpress_delete_field_stops_cap'					 => 1,
			'fieldpress_delete_my_field_stops_cap'				 => 1,
			'fieldpress_delete_all_fields_stops_cap'				 => 0, // NOT IMPLEMENTED YET
			'fieldpress_change_field_stop_status_cap'				 => 1,
			'fieldpress_change_my_field_stop_status_cap'			 => 1,
			'fieldpress_change_all_fields_stop_status_cap'		 => 0, // NOT IMPLEMENTED YET
			/* Instructors */
			'fieldpress_assign_and_assign_instructor_field_cap'	 => 0,
			'fieldpress_assign_and_assign_instructor_my_field_cap' => 1,
			/* Classes */
			'fieldpress_add_new_classes_cap'						 => 0,
			'fieldpress_add_new_my_classes_cap'					 => 0,
			'fieldpress_delete_classes_cap'						 => 0,
			'fieldpress_delete_my_classes_cap'						 => 0,
			/* Students */
			'fieldpress_invite_students_cap'						 => 0,
			'fieldpress_invite_my_students_cap'					 => 1,
			'fieldpress_withdraw_students_cap'						 => 0,
			'fieldpress_withdraw_my_students_cap'					 => 1,
			'fieldpress_add_move_students_cap'						 => 0,
			'fieldpress_add_move_my_students_cap'					 => 1,
			'fieldpress_add_move_my_assigned_students_cap'			 => 1,
			//'fieldpress_change_students_group_class_cap' => 0,
			//'fieldpress_change_my_students_group_class_cap' => 0,
			'fieldpress_add_new_students_cap'						 => 1,
			'fieldpress_send_bulk_my_students_email_cap'			 => 0,
			'fieldpress_send_bulk_students_email_cap'				 => 1,
			'fieldpress_delete_students_cap'						 => 0,
			/* Groups */
			'fieldpress_settings_groups_page_cap'					 => 0,
			//'fieldpress_settings_shortcode_page_cap' => 0,
			/* Notifications */
			'fieldpress_create_notification_cap'					 => 1,
			'fieldpress_create_my_assigned_notification_cap'		 => 1,
			'fieldpress_create_my_notification_cap'				 => 1,
			'fieldpress_update_notification_cap'					 => 0,
			'fieldpress_update_my_notification_cap'				 => 1,
			'fieldpress_delete_notification_cap'					 => 0,
			'fieldpress_delete_my_notification_cap'				 => 1,
			'fieldpress_change_notification_status_cap'			 => 0,
			'fieldpress_change_my_notification_status_cap'			 => 1,
			/* Discussions */
			'fieldpress_create_discussion_cap'						 => 1,
			'fieldpress_create_my_assigned_discussion_cap'			 => 1,
			'fieldpress_create_my_discussion_cap'					 => 1,
			'fieldpress_update_discussion_cap'						 => 0,
			'fieldpress_update_my_discussion_cap'					 => 1,
			'fieldpress_delete_discussion_cap'						 => 0,
			'fieldpress_delete_my_discussion_cap'					 => 1,
			/* Certificates */
			'fieldpress_certificates_cap'							 => 0,
			'fieldpress_create_certificates_cap'					 => 0,
			'fieldpress_update_certificates_cap'					 => 0,
			'fieldpress_delete_certificates_cap'					 => 0,
			/* Field Trip Categories */
			'fieldpress_field_categories_manage_terms_cap'		 => 1,
			'fieldpress_field_categories_edit_terms_cap'			 => 1,
			'fieldpress_field_categories_delete_terms_cap'		 => 0,
			/* Posts and Pages */
			'edit_pages'											 => 0,
			'edit_published_pages'									 => 0,
			'edit_posts'											 => 0,
			'publish_pages'											 => 0,
			'publish_posts'											 => 0
		),
	);

	/**
	 * Constructor
	 *
	 * @since 1.2.3.3
	 */
	function __construct() {

		add_action( 'set_user_role', array( &$this, 'assign_role_capabilities' ), 10, 3 );
		add_action( 'wp_login', array( &$this, 'restore_capabilities_on_login' ), 10, 2 );
	}

	/**
	 * Assign appropriate FieldPress capabilities for roles
	 *
	 * @since 1.2.3.3.
	 *
	 */
	public function assign_role_capabilities( $user_id, $role, $old_role ) {

		$capability_types = self::$capabilities[ 'instructor' ];

		if ( 'administrator' == $role ) {

			self::assign_admin_capabilities( $user_id );
		} else {

			$user				 = new Instructor( $user_id );
			$instructor_fields	 = $user->get_assigned_fields_ids();

			// Remove all FieldPress capabilities
			foreach ( $capability_types as $key => $value ) {
				$user->remove_cap( $key );
			}

			// If they are an instructor, give them their appropriate capabilities back
			if ( !empty( $instructor_fields ) ) {
				FieldPress::instance()->assign_instructor_capabilities( $user_id );
			}
		}
	}

	/**
	 * Make sure the admin has required capabilities
	 *
	 * @since 1.2.3.3.
	 *
	 */
	public function restore_capabilities_on_login( $user_login, $user ) {
		if ( user_can( $user, 'manage_options' ) && !user_can( $user, 'fieldpress_dashboard_cap' ) ) {
			self::assign_admin_capabilities( $user->ID );
		}
	}

	/**
	 * Can the user create a field?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function assign_admin_capabilities( $user_id ) {

		$user				 = new WP_User( $user_id );
		$capability_types	 = self::$capabilities[ 'instructor' ];

		foreach ( $capability_types as $key => $value ) {
			$user->add_cap( $key );
		}
	}

	/**
	 * Can the user create a field?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_create_field( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		return ( user_can( $user_id, 'fieldpress_create_field_cap' ) ) || user_can( $user_id, 'manage_options' );
	}

	/**
	 * Can the user update this field trip?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_update_field( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$field_creator	 = self::is_field_creator( $field_id, $user_id );
		$my_field		 = self::is_field_instructor( $field_id, $user_id );

		// For new fields
		if ( ( empty( $field_id ) || 0 == $field_id ) && ( user_can( $user_id, 'fieldpress_update_my_field_cap' ) || user_can( $user_id, 'fieldpress_update_field_cap' ) || user_can( $user_id, 'fieldpress_update_all_fields_cap' ) || user_can( $user_id, 'manage_options' ) ) ) {
			return true;
		}

		// return ($my_field && user_can( $user_id, 'fieldpress_update_my_field_cap' ) ) || user_can( $user_id, 'fieldpress_update_field_cap' ) ? true : false;
		return ( $my_field && ( ( $field_creator && user_can( $user_id, 'fieldpress_update_my_field_cap' ) ) || user_can( $user_id, 'fieldpress_update_field_cap' ) ) ) || user_can( $user_id, 'fieldpress_update_all_fields_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user delete this field trip?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_delete_field( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$field_creator	 = self::is_field_creator( $field_id, $user_id );
		$my_field		 = self::is_field_instructor( $field_id, $user_id );

		// return ($my_field && user_can( $user_id, 'fieldpress_delete_my_field_cap' ) ) || user_can( $user_id, 'fieldpress_delete_field_cap' ) ? true : false;
		return ( $my_field && ( ( $field_creator && user_can( $user_id, 'fieldpress_delete_my_field_cap' ) ) || user_can( $user_id, 'fieldpress_delete_field_cap' ) ) ) || user_can( $user_id, 'fieldpress_delete_all_fields_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user change the field trip status?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_change_field_status( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		// For new fields
		if ( ( empty( $field_id ) || 0 == $field_id ) && ( user_can( $user_id, 'fieldpress_change_my_field_status_cap' ) || user_can( $user_id, 'fieldpress_change_field_status_cap' ) || user_can( $user_id, 'fieldpress_change_all_fields_status_cap' ) || user_can( $user_id, 'manage_options' ) ) ) {
			return true;
		}

		$field_creator	 = self::is_field_creator( $field_id, $user_id );
		$my_field		 = self::is_field_instructor( $field_id, $user_id );

		return ( $my_field && ( ( $field_creator && user_can( $user_id, 'fieldpress_change_my_field_status_cap' ) ) || user_can( $user_id, 'fieldpress_change_field_status_cap' ) ) ) || user_can( $user_id, 'fieldpress_change_all_fields_status_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user create stops?
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return bool
	 */
	public static function can_create_stop( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		return user_can( $user_id, 'fieldpress_create_field_stop_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user create stops in this field trip?
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return bool
	 */
	public static function can_create_field_stop( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$can_update_field	 = self::can_update_field( $field_id, $user_id );
		$can_create_stops	 = self::can_create_stop( $user_id );

		return ( $can_update_field && $can_create_stops ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user view stops?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_view_field_stops( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$my_field = self::is_field_instructor( $field_id, $user_id );

		return ( $my_field || user_can( $user_id, 'fieldpress_view_all_stops_cap' ) ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user update the stops?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_update_field_stop( $field_id, $stop_id = '', $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$my_stop	 = self::is_stop_creator( $stop_id, $user_id );
		$my_field	 = self::is_field_instructor( $field_id, $user_id );

		// For new stop
		if ( ( empty( $stop_id ) || 0 == $stop_id ) && ( user_can( $user_id, 'fieldpress_update_my_field_stop_cap' ) || user_can( $user_id, 'fieldpress_update_field_stop_cap' ) || user_can( $user_id, 'fieldpress_update_all_fields_stop_cap' ) || user_can( $user_id, 'manage_options' ) ) ) {
			if ( $my_field ) {
				return true;
			}
		}

		return ( $my_field && ( ( $my_stop && user_can( $user_id, 'fieldpress_update_my_field_stop_cap' ) ) || user_can( $user_id, 'fieldpress_update_field_stop_cap' ) ) ) || user_can( $user_id, 'fieldpress_update_all_fields_stop_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user delete the stops?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_delete_field_stop( $field_id, $stop_id = '', $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$my_stop	 = self::is_stop_creator( $stop_id, $user_id );
		$my_field	 = self::is_field_instructor( $field_id, $user_id );

		return ( $my_field && ( ( $my_stop && user_can( $user_id, 'fieldpress_delete_my_field_stops_cap' ) ) || user_can( $user_id, 'fieldpress_delete_field_stops_cap' ) ) ) || user_can( $user_id, 'fieldpress_delete_all_fields_stops_cap' ) ? true : false;
	}

	/**
	 * Can the user change the stop state?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_change_field_stop_status( $field_id, $stop_id = '', $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$my_stop	 = self::is_stop_creator( $stop_id, $user_id );
		$my_field	 = self::is_field_instructor( $field_id, $user_id );

		// For new stop
		if ( ( empty( $stop_id ) || 0 == $stop_id ) && ( user_can( $user_id, 'fieldpress_change_my_field_stop_status_cap' ) || user_can( $user_id, 'fieldpress_change_field_stop_status_cap' ) || user_can( $user_id, 'fieldpress_change_all_fields_stop_status_cap' ) || user_can( $user_id, 'manage_options' ) ) ) {
			if ( $my_field ) {
				return true;
			}
		}

		return ( $my_field && ( ( $my_stop && user_can( $user_id, 'fieldpress_change_my_field_stop_status_cap' ) ) || user_can( $user_id, 'fieldpress_change_field_stop_status_cap' ) ) ) || user_can( $user_id, 'fieldpress_change_all_fields_stop_status_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user assign a Field Trip Instructor?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_assign_field_instructor( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		// For new fields
		if ( ( empty( $field_id ) || 0 == $field_id ) && ( user_can( $user_id, 'fieldpress_assign_and_assign_instructor_my_field_cap' ) || user_can( $user_id, 'fieldpress_assign_and_assign_instructor_field_cap' ) || user_can( $user_id, 'manage_options' ) ) ) {
			return true;
		}

		$my_field = self::is_field_instructor( $field_id, $user_id );

		return ( $my_field && user_can( $user_id, 'fieldpress_assign_and_assign_instructor_my_field_cap' ) ) || user_can( $user_id, 'fieldpress_assign_and_assign_instructor_field_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Can the user invite students?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function can_assign_field_student( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$my_field = self::is_field_instructor( $field_id, $user_id );

		return ( $my_field && user_can( $user_id, 'fieldpress_invite_my_students_cap' ) ) || user_can( $user_id, 'fieldpress_invite_students_cap' ) || user_can( $user_id, 'manage_options' ) ? true : false;
	}

	/**
	 * Is the user an instructor of this field trip?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_field_instructor( $field_id, $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$instructor			 = new Instructor( $user_id );
		$instructor_fields	 = $instructor->get_assigned_fields_ids();

		return in_array( $field_id, $instructor_fields );
	}

	/**
	 * Is the user the stop author?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_stop_creator( $stop_id = '', $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $stop_id ) ) {
			return false;
		} else {
			return $user_id == get_post_field( 'post_author', $stop_id ) ? true : false;
		}
	}

	/**
	 * Is the user the field trip author?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_field_creator( $field_id = '', $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $field_id ) ) {
			return false;
		} else {
			return $user_id == get_post_field( 'post_author', $field_id );
		}
	}

	public static function grant_private_caps( $user_id ) {
		$user				 = new WP_User( $user_id );
		$capability_types	 = array( 'field', 'stop', 'module', 'module_response', 'notification', 'discussion' );

		foreach ( $capability_types as $capability_type ) {
			$user->add_cap( "read_private_{$capability_type}s" );
		}
	}

	public static function drop_private_caps( $user_id = '', $role = '' ) {

		if ( empty( $user_id ) && empty( $role ) ) {
			return;
		}

		$user = false;
		if ( !empty( $user_id ) ) {
			$user = new WP_User( $user_id );
		}

		$capability_types = array( 'field', 'stop', 'module', 'module_response', 'notification', 'discussion' );

		foreach ( $capability_types as $capability_type ) {
			if ( !empty( $user ) ) {
				$user->remove_cap( "read_private_{$capability_type}s" );
			}
			if ( !empty( $role ) ) {
				$role->remove_cap( "read_private_{$capability_type}s" );
			}
		}
	}

	/**
	 * Is this FieldPress?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_pro() {

		return true;



	}

	/**
	 * Is this runnning on CampusPress or Edublogs?
	 *
	 * @since 1.2.1
	 *
	 * @return bool
	 */
	public static function is_campus() {
		$campus_conditions	 = array( 'is_campus', 'is_edublogs' );
		$is_campus			 = false;

		foreach ( $campus_conditions as $condition ) {
			$is_campus |= function_exists( $condition ) && call_user_func( $condition );
		}

		return $is_campus;
	}

}

// Creating a bit of a non-instance, but doing it so that we can get to user's hooks
$fieldpress_capabilities = new FieldPress_Capabilities();