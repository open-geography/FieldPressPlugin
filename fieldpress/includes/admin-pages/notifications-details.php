<?php
global $action, $page;
global $page, $user_id, $cp_admin_notice;
global $fieldpress;

$notification_id = '';
if ( isset( $_GET['notification_id'] ) ) {
	$notification         = new Notification( $_GET['notification_id'] );
	$notification_details = $notification->get_notification();
	$notification_id      = ( int ) $_GET['notification_id'];
} else {
	$notification    = new Notification();
	$notification_id = 0;
}

wp_reset_vars( array( 'action', 'page' ) );

if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'add' || $_POST['action'] == 'update' ) ) {

	check_admin_referer( 'notifications_details' );

	$new_post_id = $notification->update_notification();

	if ( $_POST['action'] == 'update' ) {
		wp_redirect( admin_url( 'admin.php?page=' . $page . '&ms=nu' ) );
		exit;
	}

	if ( $new_post_id !== 0 ) {
		ob_start();
		// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
		//wp_redirect( admin_url( 'admin.php?page=' . $page . '&notification_id=' . $new_post_id . '&action=edit' ) );
		if ( $_POST['action'] == 'add' ) {
			wp_redirect( admin_url( 'admin.php?page=' . $page . '&ms=add' ) );
			exit;
		}
		exit;
	} else {
		//an error occured
	}
}

if ( isset( $_GET['notification_id'] ) ) {
	$meta_field_id = $notification->details->field_id;
} else {
	$meta_field_id = '';
}
?>

<div class="wrap nosubsub cp-wrap">
	<div class="icon32" id="icon-themes"><br></div>

	<h2><?php _e( 'Notification', 'cp' ); ?><?php if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_create_notification_cap' ) || current_user_can( 'fieldpress_create_my_notification_cap' ) || current_user_can( 'fieldpress_create_my_assigned_notification_cap' ) ) { ?>
			<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=notifications&action=add_new' ); ?>"><?php _e( 'Add New', 'cp' ); ?></a><?php } ?>
	</h2>

	<?php
	$message['ca'] = __( 'New Notification added successfully!', 'cp' );
	$message['cu'] = __( 'Notification updated successfully.', 'cp' );
	?>

	<div class='wrap nofieldsub'>
		<form action='<?php echo esc_attr( admin_url( 'admin.php?page=' . $page . ( ( $notification_id !== 0 ) ? '&notification_id=' . $notification_id : '' ) . '&action=' . $action . ( ( $notification_id !== 0 ) ? '&ms=cu' : '&ms=ca' ) ) ); ?>' name='notification-add' method='post'>

			<div class='field-liquid-left'>

				<div id='field-full'>

					<?php wp_nonce_field( 'notifications_details' ); ?>

					<?php if ( isset( $notification_id ) && $notification_id > 0 ) { ?>
						<input type="hidden" name="notification_id" value="<?php echo esc_attr( $notification_id ); ?>"/>
						<input type="hidden" name="action" value="update"/>
					<?php } else { ?>
						<input type="hidden" name="action" value="add"/>
					<?php } ?>

					<div id='edit-sub' class='field-holder-wrap'>
						<div class='field-holder'>
							<div class='field-details'>
								<label for='notification_name'><?php _e( 'Notify Students in selected field trips', 'cp' ); ?></label>

								<p><?php _e( 'Notifications are shown to end users in their Notifications menu item', 'cp' ); ?></p>

								<div class="full">
									<label><?php _e( 'Field Trip', 'cp' ); ?></label>
									<select name="meta_field_id" class="chosen-select">
										<?php if ( current_user_can( 'fieldpress_create_notification_cap' ) || current_user_can( 'fieldpress_update_notification_cap' ) ) { ?>
											<option value="" <?php selected( $meta_field_id, '' ); ?>><?php _e( 'All Field Trips', 'cp' ); ?></option>
										<?php } ?>
										<?php

										$args = array(
											'post_type'      => 'field',
											'post_status'    => 'any',
											'posts_per_page' => - 1
										);

										$fields                  = get_posts( $args );
										$available_field_options = 0;
										//fieldpress_create_my_assigned_notification_cap
										foreach ( $fields as $field ) {

											//if ( $notification_id == 0 ) {

											$instructor         = new Instructor( get_current_user_id() );
											$instructor_fields = $instructor->get_assigned_fields_ids();

											$my_field = in_array( $field->ID, $instructor_fields );
											$my_field = FieldPress_Capabilities::is_field_instructor( $field->ID );
											//}

											if ( $notification_id == 0 ) {
												if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_create_notification_cap' ) || ( current_user_can( 'fieldpress_create_my_notification_cap' ) && $field->post_author == get_current_user_ID() ) || ( current_user_can( 'fieldpress_create_my_assigned_notification_cap' ) && $my_field ) ) {
													?>
													<option value="<?php echo $field->ID; ?>" <?php selected( $meta_field_id, $field->ID ); ?>><?php echo $field->post_title; ?></option>
													<?php
													$available_field_options ++;
												}
											} else {//check for update capabilities
												if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_update_notification_cap' ) || ( current_user_can( 'fieldpress_update_my_notification_cap' ) && $notification_details->post_author == get_current_user_ID() ) /* || (current_user_can('fieldpress_create_my_assigned_notification_cap') && $my_field) */ ) {
													?>
													<option value="<?php echo $field->ID; ?>" <?php selected( $meta_field_id, $field->ID ); ?>><?php echo $field->post_title; ?></option>
													<?php
													$available_field_options ++;
												}
											}
										}
										?>
									</select>
									<?php
									if ( $available_field_options == 0 ) {
										?>
										<p><?php _e( "No field trips available for selection." ); ?></p>
									<?php
									}
									?>

								</div>
								<br clear="all"/>

								<label for='notification_name'><?php _e( 'Notification Title', 'cp' ); ?></label>
								<input class='wide' type='text' name='notification_name' id='notification_name' value='<?php
								if ( isset( $_GET['notification_id'] ) ) {
									echo esc_attr( stripslashes( $notification->details->post_title ) );
								}
								?>'/>

								<br/><br/>
								<label for='field_name'><?php _e( 'Notification Content', 'cp' ); ?></label>
								<?php

								$editor_name    = "notification_description";
								$editor_id      = "notification_description";
								$editor_content = htmlspecialchars_decode( isset( $notification->details->post_content ) ? $notification->details->post_content : '' );

								$args = array(
									"textarea_name" => $editor_name,
									"editor_class"  => 'cp-editor',
									"textarea_rows" => 10,
								);

								// Filter $args before showing editor
								$args = apply_filters( 'fieldpress_element_editor_args', $args, $editor_name, $editor_id );

								wp_editor( $editor_content, $editor_id, $args );
								?>
								<br/>

								<br clear="all"/>


								<div class="buttons">
									<?php
									if ( current_user_can( 'manage_options' ) || ( $notification_id == 0 && current_user_can( 'fieldpress_create_notification_cap' ) ) || ( $notification_id != 0 && current_user_can( 'fieldpress_update_notification_cap' ) ) || ( $notification_id != 0 && current_user_can( 'fieldpress_update_my_notification_cap' ) && $notification_details->post_author == get_current_user_id() ) || ( current_user_can( 'fieldpress_create_my_notification_cap' ) && $available_field_options > 0 ) ) {//do not show anything
										?>
										<input type="submit" value="<?php ( $notification_id == 0 ? _e( 'Create', 'cp' ) : _e( 'Update', 'cp' ) ); ?>" class="button-primary"/>
									<?php
									} else {
										_e( 'You do not have required permissions for this action', 'cp' );
									}
									?>
								</div>

								<br clear="all"/>

							</div>


						</div>
					</div>

				</div>
			</div>
			<!-- field-liquid-left -->
		</form>

	</div>
	<!-- wrap -->
</div>