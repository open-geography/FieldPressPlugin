<form id="popup_login_form">
	<div class="cp_popup_title"><?php _e( 'Login', 'cp' ); ?></div>

	<input type="hidden" name="signup-next-step" value="enrollment"/>

	<p class="cp_popup_required"><?php _e( 'Login with your existing username and password', 'cp' ); ?></p>

	<label class="cp_popup_col_1">
		<input type="text" class="required" id="cp_popup_username" value="" placeholder="<?php _e( 'Username', 'cp' ); ?>">
	</label>

	<label class="cp_popup_col_1">
		<input type="password" class="required" id="cp_popup_password" value="" placeholder="<?php _e( 'Password', 'cp' ); ?>">
	</label>

	<?php
	$field_id = isset( $_POST['field_id'] ) ? (int) $_POST['field_id'] : ' ';
	$field    = new Field( $field_id );
	if ( $field->details->enroll_type == 'passcode' ) {
		?>
		<label class="cp_popup_col_1">
			<input type="text" class="required" name="passcode" id="cp_popup_passcode" placeholder="<?php _e( 'Field Passcode', 'cp' ); ?>"/>
		</label>
	<?php } ?>

	<input type="hidden" value="<?php esc_attr_e( isset( $_POST['field_id'] ) ? (int) $_POST['field_id'] : ' ', 'cp' ); ?>" name="field_id"/>

	<div class="cp_popup_buttons login_buttons">
		<?php wp_nonce_field( 'popup_login_nonce', 'submit_login_data' ); ?>
		<div class="validation_errors"></div>

		<input type="hidden" name="data-field-id" id="data-field-id" value="<?php esc_attr_e( isset( $_POST['field_id'] ) ? (int) $_POST['field_id'] : ' ', 'cp' ); ?>"/>

		<label class="cp_popup_col_2">
			<a href="" class="cp_signup_step" data-field-id="<?php esc_attr_e( isset( $_POST['field_id'] ) ? (int) $_POST['field_id'] : ' ', 'cp' ); ?>"><?php _e( 'Create an Account', 'cp' ); ?></a>
		</label>

		<label class="cp_popup_col_2 second-child">
			<button class="apply-button login" data-field-id="<?php esc_attr_e( isset( $_POST['field_id'] ) ? (int) $_POST['field_id'] : ' ', 'cp' ); ?>"><?php _e( 'Login', 'cp' ); ?></button>
		</label>
	</div>
</form>