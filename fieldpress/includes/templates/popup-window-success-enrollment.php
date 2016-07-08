<div class="cp_popup_inner">
	<div class="cp_popup_title cp_popup_congratulations_title"><?php _e( 'Congratulations', 'cp' ); ?></div>
	<?php
	global $fieldpress;
	$field_id      = (int) $args['field_id'];
	$field         = new Field( $field_id );
	$dashboard_link = '<a href="' . esc_url( $fieldpress->get_student_dashboard_slug( true ) ) . '">' . __( 'Dashboard', 'cp' ) . '</a>';
	$field_link    = '<a href="' . esc_url( get_permalink( $field_id ) ) . '">' . $field->details->post_title . '</a>';
	?>
	<div class="cp_popup_success_message">
		<div class="congratulations-image">
			<img src="<?php echo esc_url( FieldPress::instance()->plugin_url . 'images/congrats-tick.png' ); ?>" alt="<?php esc_attr_e( 'Congratulations image', 'cp' ); ?>">
		</div
		<p><?php echo sprintf( __( 'You have successfully enrolled in %s', 'cp' ), $field_link ); ?><br/>
			<?php
			_e( 'You will receive an e-mail confirmation shortly.', 'cp' );
			?></p>

		<p><?php echo sprintf( __( 'Your field trip will be available at any time in your %s', 'cp' ), $dashboard_link ); ?></p>
	</div>

	<?php
	if ( ( $field->details->field_start_date !== '' && $field->details->field_end_date !== '' ) || $field->details->open_ended_field == 'on' ) {//Field is currently active
		//if ( ( strtotime( $field->details->field_start_date ) <= time() && strtotime( $field->details->field_end_date ) >= time() ) || $field->details->open_ended_field == 'on' ) {//Field is currently active
		if ( ( strtotime( $field->details->field_end_date ) >= time() ) || $field->details->open_ended_field == 'on' ) {//Field is currently active
			?>
			<div class="cp_popup_button_container">
				<button class="apply-button enroll-success" data-link="<?php echo esc_url( trailingslashit( get_permalink( $field_id ) ) ) . trailingslashit( $fieldpress->get_stops_slug() ); ?>"><?php _e( 'Start Field Trip Now', 'cp' ); ?></button>
			</div>
		<?php
		}
	} ?>
</div>
<script type="text/javascript">
	fieldpress_apply_data_link_click();
</script>