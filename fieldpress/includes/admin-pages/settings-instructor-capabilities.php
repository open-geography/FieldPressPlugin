<?php
global $wp_roles;

if ( isset( $_POST[ 'submit' ] ) && current_user_can( 'manage_options' ) && isset( $_POST[ 'instructor_capability' ] ) ) {

	/* Set capabilities for each instructor user */
	$wp_user_search = new Instructor_Search();
	// $wp_user_search = new Instructor_Search( $usersearch, $page_num );

	foreach ( $wp_user_search->get_results() as $user ) {

		FieldPress_Capabilities::grant_private_caps( $user->ID );

		// Don't remove capabilities from administrators
		/* if( user_can( $user->ID, 'manage_options' ) ){
		  continue;
		  } */

		$role				 = new WP_User( $user->ID );
		$user_capabilities	 = $role->wp_capabilities;

		// More than the hidden field needs to be present to add roles.
		if ( isset( $_POST[ 'instructor_capability' ] ) && 1 < count( $_POST[ 'instructor_capability' ] ) ) {
			foreach ( $user_capabilities as $key => $old_cap ) {
				// Make sure to only remove FieldPress instructor capabilities
				if ( !in_array( $key, $_POST[ 'instructor_capability' ] ) &&
				in_array( $key, array_keys( FieldPress_Capabilities::$capabilities[ 'instructor' ] ) )
				) {//making the operation less expensive
					if ( !user_can( $user->ID, 'manage_options' ) ) {
						$role->remove_cap( $key );
					}
				}
			}

			foreach ( $_POST[ 'instructor_capability' ] as $new_cap ) {
				$role->add_cap( $new_cap );
			}
		} else {//all unchecked, remove all instructor capabilities
			foreach ( $user_capabilities as $key => $old_cap ) {
				if ( in_array( $key, array_keys( FieldPress_Capabilities::$capabilities[ 'instructor' ] ) ) ) {
					if ( !user_can( $user->ID, 'manage_options' ) ) {
						$role->remove_cap( $key );
					}
				}
			}
		}
		unset( $_POST[ 'instructor_capability' ]['update_options'] );
		update_option( 'fieldpress_instructor_capabilities', $_POST[ 'instructor_capability' ] );
	}
}

// The default capabilities for an instructor
$default_capabilities	 = array_keys( FieldPress_Capabilities::$capabilities[ 'instructor' ], 1 );
$instructor_capabilities = get_option( 'fieldpress_instructor_capabilities', $default_capabilities );

$capability_boxes = array(
	'instructor_capabilities_general'			 => __( 'General', 'cp' ),
	'instructor_capabilities_fields'			 => __( 'Field Trips', 'cp' ),
	'instructor_capabilities_field_categories'	 => __( 'Field Trip Categories', 'cp' ),
	'instructor_capabilities_stops'				 => __( 'Stops', 'cp' ),
	'instructor_capabilities_instructors'		 => __( 'Instructors', 'cp' ),
	//'instructor_capabilities_classes' => __( 'Classes', 'cp' ),
	'instructor_capabilities_students'			 => __( 'Students', 'cp' ),
	'instructor_capabilities_notifications'		 => __( 'Notifications', 'cp' ),
	'instructor_capabilities_discussions'		 => __( 'Discussions', 'cp' ),
	'instructor_capabilities_posts_and_pages'	 => __( 'Posts and Pages', 'cp' )
//'instructor_capabilities_groups' => __( 'Settings Pages', 'cp' ),
);

$instructor_capabilities_general = array(
	'fieldpress_dashboard_cap'		 => __( 'Access to plugin menu', 'cp' ),
	'fieldpress_fields_cap'		 => __( 'Access to the field trips menu item', 'cp' ),
	'fieldpress_instructors_cap'	 => __( 'Access to the Intructors menu item', 'cp' ),
	'fieldpress_students_cap'		 => __( 'Access to the Students menu item', 'cp' ),
	'fieldpress_assessment_cap'	 => __( 'Assessment', 'cp' ),
	'fieldpress_reports_cap'		 => __( 'Reports', 'cp' ),
	'fieldpress_notifications_cap'	 => __( 'Notifications', 'cp' ),
	'fieldpress_discussions_cap'	 => __( 'Discussions', 'cp' ),
	'fieldpress_settings_cap'		 => __( 'Access to the Settings menu item', 'cp' ),
);

$instructor_capabilities_fields = array(
	'fieldpress_create_field_cap'				 => __( 'Create new field trip', 'cp' ),
	'fieldpress_update_field_cap'				 => __( 'Update any assigned field trip', 'cp' ),
	'fieldpress_update_my_field_cap'			 => __( 'Update field trip made by the instructor only', 'cp' ),
	// 'fieldpress_update_all_fields_cap' => __( 'Update ANY field trip', 'cp' ),
	'fieldpress_delete_field_cap'				 => __( 'Delete any assigned field trip', 'cp' ),
	'fieldpress_delete_my_field_cap'			 => __( 'Delete field trips made by the instructor only', 'cp' ),
	// 'fieldpress_delete_all_fields_cap' => __( 'Delete ANY field trip', 'cp' ),
	'fieldpress_change_field_status_cap'		 => __( 'Change status of any assigned field trip', 'cp' ),
	'fieldpress_change_my_field_status_cap'	 => __( 'Change status of field trips made by the instructor only', 'cp' ),
 // 'fieldpress_change_all_fields_status_cap' => __( 'Change status of ALL field trip', 'cp' ),
);

$instructor_capabilities_field_categories = array(
	'fieldpress_field_categories_manage_terms_cap' => __( 'Manage Categories', 'cp' ),
	'fieldpress_field_categories_edit_terms_cap'	 => __( 'Edit Categories', 'cp' ),
	'fieldpress_field_categories_delete_terms_cap' => __( 'Delete Categories', 'cp' ),
);

$instructor_capabilities_stops = array(
	'fieldpress_create_field_stop_cap'			 => __( 'Create new Field Trip Stops', 'cp' ),
	'fieldpress_view_all_stops_cap'				 => __( 'View stops in every field trip ( can view from other Instructors as well )', 'cp' ),
	'fieldpress_update_field_stop_cap'			 => __( 'Update any stop (within assigned field trips)', 'cp' ),
	'fieldpress_update_my_field_stop_cap'			 => __( 'Update stops made by the instructor only', 'cp' ),
	// 'fieldpress_update_all_fields_stop_cap' => __( 'Update stops of ALL field trips', 'cp' ),
	'fieldpress_delete_field_stops_cap'			 => __( 'Delete any stop (within assigned field trips)', 'cp' ),
	'fieldpress_delete_my_field_stops_cap'		 => __( 'Delete Field Trip Stops made by the instructor only', 'cp' ),
	// 'fieldpress_delete_all_fields_stops_cap' => __( 'Delete stops of ALL field trips', 'cp' ),
	'fieldpress_change_field_stop_status_cap'		 => __( 'Change status of any stop (within assigned field trips)', 'cp' ),
	'fieldpress_change_my_field_stop_status_cap'	 => __( 'Change statuses of Field Trip Stops made by the instructor only', 'cp' ),
 // 'fieldpress_change_all_fields_stop_status_cap' => __( 'Change status of any stop of ALL field trips', 'cp' ),
);

$instructor_capabilities_instructors = array(
	'fieldpress_assign_and_assign_instructor_field_cap'	 => __( 'Assign instructors to any field trip', 'cp' ),
	'fieldpress_assign_and_assign_instructor_my_field_cap' => __( 'Assign instructors to field trips made by the instructor only', 'cp' )
);

$instructor_capabilities_classes = array(
	'fieldpress_add_new_classes_cap'	 => __( 'Add new field trip location to any field trip', 'cp' ),
	'fieldpress_add_new_my_classes_cap' => __( 'Add new field trip location to field trips made by the instructor only', 'cp' ),
	'fieldpress_delete_classes_cap'	 => __( 'Delete any field trip location', 'cp' ),
	'fieldpress_delete_my_classes_cap'	 => __( 'Delete field trip location from field trips made by the instructor only', 'cp' )
);

$instructor_capabilities_students = array(
	'fieldpress_invite_students_cap'				 => __( 'Invite students to any field trip', 'cp' ),
	'fieldpress_invite_my_students_cap'			 => __( 'Invite students to field trip made by the instructor only', 'cp' ),
	'fieldpress_withdraw_students_cap'				 => __( 'Withdraw students from any field trip', 'cp' ),
	'fieldpress_withdraw_my_students_cap'			 => __( 'Withdraw students from field trips made by the instructor only', 'cp' ),
	'fieldpress_add_move_students_cap'				 => __( 'Add students to any field trip', 'cp' ),
	'fieldpress_add_move_my_students_cap'			 => __( 'Add students to field trips made by the instructor only', 'cp' ),
	'fieldpress_add_move_my_assigned_students_cap'	 => __( 'Add students to field trips assigned to the instructor only', 'cp' ),
	//'fieldpress_change_students_group_class_cap' => __( "Change student's group", 'cp' ),
	//'fieldpress_change_my_students_group_class_cap' => __( "Change student's group within a class made by the instructor only", 'cp' ),
	'fieldpress_add_new_students_cap'				 => __( 'Add new users with Student role to the blog', 'cp' ),
	'fieldpress_send_bulk_my_students_email_cap'	 => __( "Send bulk e-mail to students", 'cp' ),
	'fieldpress_send_bulk_students_email_cap'		 => __( "Send bulk e-mail to students within a field trip made by the instructor only", 'cp' ),
	'fieldpress_delete_students_cap'				 => __( "Delete Students (deletes ALL associated field trip records)", 'cp' ),
);

$instructor_capabilities_groups = array(
	'fieldpress_settings_groups_page_cap' => __( 'View Groups tab within the Settings page', 'cp' ),
 //'fieldpress_settings_shortcode_page_cap' => __( 'View Shortcode within the Settings page', 'cp' )
);

$instructor_capabilities_notifications = array(
	'fieldpress_create_notification_cap'				 => __( 'Create new notifications', 'cp' ),
	'fieldpress_create_my_notification_cap'			 => __( 'Create new notifications for field trips created by the instructor only', 'cp' ),
	'fieldpress_create_my_assigned_notification_cap'	 => __( 'Create new notifications for field trips assigned to the instructor only', 'cp' ),
	'fieldpress_update_notification_cap'				 => __( 'Update every notification', 'cp' ),
	'fieldpress_update_my_notification_cap'			 => __( 'Update notifications made by the instructor only', 'cp' ),
	'fieldpress_delete_notification_cap'				 => __( 'Delete every notification', 'cp' ),
	'fieldpress_delete_my_notification_cap'			 => __( 'Delete notifications made by the instructor only', 'cp' ),
	'fieldpress_change_notification_status_cap'		 => __( 'Change status of every notification', 'cp' ),
	'fieldpress_change_my_notification_status_cap'		 => __( 'Change statuses of notifications made by the instructor only', 'cp' )
);

$instructor_capabilities_discussions = array(
	'fieldpress_create_discussion_cap'				 => __( 'Create new discussions', 'cp' ),
	'fieldpress_create_my_discussion_cap'			 => __( 'Create new discussions for field trips created by the instructor only', 'cp' ),
	'fieldpress_create_my_assigned_discussion_cap'	 => __( 'Create new discussions for field trips assigned to the instructor only', 'cp' ),
	'fieldpress_update_discussion_cap'				 => __( 'Update every discussions', 'cp' ),
	'fieldpress_update_my_discussion_cap'			 => __( 'Update discussions made by the instructor only', 'cp' ),
	'fieldpress_delete_discussion_cap'				 => __( 'Delete every discussions', 'cp' ),
	'fieldpress_delete_my_discussion_cap'			 => __( 'Delete discussions made by the instructor only', 'cp' ),
);

$instructor_capabilities_posts_and_pages = array(
	'edit_pages'			 => __( 'Edit Pages (required for MarketPress)', 'cp' ),
	'edit_published_pages'	 => __( 'Edit Published Pages', 'cp' ),
	'edit_posts'			 => __( 'Edit Posts', 'cp' ),
	'publish_pages'			 => __( 'Publish Pages', 'cp' ),
	'publish_posts'			 => __( 'Publish Posts', 'cp' )
);
?>
<div id="poststuff" class="metabox-holder m-settings cp-wrap">
	<form action='' method='post'>

		<?php
		wp_nonce_field( 'update-fieldpress-options' );
		?>
		<p class='description'><?php printf( __( 'Instructor capabilities define what the Instructors can or cannot do within the %s.', 'cp' ), $this->name ); ?></p>
		<?php
		foreach ( $capability_boxes as $box_key => $group_name ) {
			?>
			<div class="postbox">
				<h3 class="hndle" style='cursor:auto;'><span><?php echo $group_name; ?></span></h3>

				<div class="inside">

					<table class="form-table">
						<tbody id="items">
							<?php
							foreach ( ${$box_key} as $key => $value ) {
								?>
								<tr>
									<td width="50%"><?php echo $value; ?></td>
									<td><input type="checkbox" <?php
										// if ( array_key_exists( $key, $instructor_capabilities ) ) {
										if ( in_array( $key, $instructor_capabilities ) ) {
											echo 'checked';
										}
										?> name="instructor_capability[]" value="<?php echo $key; ?>"></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<!--/inside-->

			</div><!--/postbox-->
		<?php } ?>

		<input type="hidden" name="instructor_capability[update_options]" value="1" />
		<p class="save-changes">
			<?php submit_button( __( 'Save Changes', 'cp' ) ); ?>
		</p>

	</form>
</div>