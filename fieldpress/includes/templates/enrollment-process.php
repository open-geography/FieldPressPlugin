<?php

$student = new Student( get_current_user_id() );

$field_price = 0;

if ( is_user_logged_in() ) {

	if ( isset( $_POST['field_id'] ) && is_numeric( $_POST['field_id'] ) ) {

		check_admin_referer( 'enrollment_process' );

		$field_id   = (int) $_POST['field_id'];
		$field      = new Field( $field_id );
		$pass_errors = 0;

		global $fieldpress;

		$is_paid = get_post_meta( $field_id, 'paid_field', true ) == 'on' ? true : false;

		if ( $is_paid && isset( $field->details->marketpress_product ) && $field->details->marketpress_product != '' && $fieldpress->marketpress_active ) {
			$field_price = 1; //forces user to purchase field / show purchase form
			$field->is_user_purchased_field( $field->details->marketpress_product, get_current_user_ID() );
		}

		if ( $field->details->enroll_type == 'passcode' ) {
			if ( $_POST['passcode'] != $field->details->passcode ) {
				$pass_errors ++;
			}
		}

		if ( ! $student->user_enrolled_in_field( $field_id ) ) {
			if ( $pass_errors == 0 ) {
				if ( $field_price == 0 ) {//Field is FREE
					//Enroll student in
					if ( $student->enroll_in_field( $field_id ) ) {
						printf( __( 'Congratulations, you have successfully enrolled in "%s" field! Check your %s for more info.', 'cp' ), '<strong>' . $field->details->post_title . '</strong>', '<a href="' . $this->get_student_dashboard_slug( true ) . '">' . __( 'Dashboard', 'cp' ) . '</a>' );

					} else {
						_e( 'Something went wrong during the enrollment process. Please try again later.', 'cp' );
					}
				} else {
					if ( $field->is_user_purchased_field( $field->details->marketpress_product, get_current_user_ID() ) ) {
						//Enroll student in
						if ( $student->enroll_in_field( $field_id ) ) {
							printf( __( 'Congratulations, you have successfully enrolled in "%s" field! Check your %s for more info.', 'cp' ), '<strong>' . $field->details->post_title . '</strong>', '<a href="' . $this->get_student_dashboard_slug( true ) . '">' . __( 'Dashboard', 'cp' ) . '</a>' );
						} else {
							_e( 'Something went wrong during the enrollment process. Please try again later.', 'cp' );
						}
					} else {
						$field->show_purchase_form( $field->details->marketpress_product );
					}
				}
			} else {
				printf( __( 'Passcode is not valid. Please %s and try again.', 'cp' ), '<a href="' . esc_url( $field->get_permalink() ) . '">' . __( 'go back', 'cp' ) . '</a>' );

			}
		} else {
			// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
			// _e( 'You have already enrolled in the field trip.', 'cp' ); //can't enroll more than once to the same field at the time
			wp_redirect( trailingslashit( $field->get_permalink() ) . 'stops' );
			exit;
		}
	} else {
		_e( 'Please select a field first you want to enroll in.', 'cp' );
	}
} else {
	_e( 'You must be logged in in order to complete the action', 'cp' );
}
?>