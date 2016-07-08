<?php if ( is_user_logged_in() ) { ?>
	<?php

	global $fieldpress;
	$student         = new Student( get_current_user_id() );
	$student_fields = $student->get_enrolled_fields_ids();
	?>
	<div class="student-dashboard-wrapper">
		<?php

		// Instructor Field List
		$show = 'dates,class_size';

		$field_list = do_shortcode( '[field_list instructor="' . get_current_user_id() . '" instructor_msg="" status="all" title_tag="h1" title_class="h1-title" list_wrapper_before="" show_divider="yes"  left_class="enroll-box-left" right_class="enroll-box-right" field_class="enroll-box" title_link="no" show="' . $show . '" show_title="no" admin_links="true" show_button="no" show_media="no"]' );


		$show_random_fields = true;

		if ( ! empty( $field_list ) ) {
			echo '<div class="dashboard-managed-fields-list">';
			echo '<h1 class="title managed-fields-title">' . __( 'Field Trip You Manage:', 'cp' ) . '</h1>';
			echo '<div class="field-list field-list-managed field field-student-dashboard">';
			echo $field_list;
			echo '</div>';
			echo '</div>';
			echo '<div class="clearfix"></div>';
		}

		// Add some random fields.
		$field_list = do_shortcode( '[field_list student="' . $student->ID . '" student_msg="" field_status="incomplete" list_wrapper_before="" class="field field-student-dashboard" left_class="enroll-box-left" right_class="enroll-box-right" field_class="enroll-box" title_class="h1-title" title_link="no" show_media="no"]' );

		if ( empty( $field_list ) && $show_random_fields ) {

			//Random Field Trips
			echo '<div class="dashboard-random-fields-list">';
			echo '<h3 class="title suggested-fields">' . __( 'You are not enrolled in any field trips.', 'cp' ) . '</h3>';
			_e( 'Here are a few to help you get started:', 'cp' );
			echo '<hr />';
			echo '<div class="dashboard-random-fields">' . do_shortcode( '[field_random number="3" featured_title="" media_type="image"]' ) . '</div>';
			echo '</div>';
		} else {
			// Field List
			echo '<div class="dashboard-current-fields-list">';
			echo '<h1 class="title enrolled-fields-title current-fields-title">' . __( 'Your current field trips:', 'cp' ) . '</h1>';
			echo '<div class="field-list field-list-current field field-student-dashboard">';
			echo $field_list;
			echo '</div>';
			echo '</div>';
			echo '<div class="clearfix"></div>';
		}

		// Completed field trips
		$show        = 'dates,class_size';
		$field_list = do_shortcode( '[field_list student="' . $student->ID . '" student_msg="" field_status="completed" list_wrapper_before="" title_link="no" title_tag="h1" title_class="h1-title" show_divider="yes" left_class="enroll-box-left" right_class="enroll-box-right"]' );

		if ( ! empty( $field_list ) ) {
			// Field List
			echo '<div class="dashboard-completed-fields-list">';
			echo '<h1 class="title completed-fields-title">' . __( 'Completed field trips:', 'cp' ) . '</h1>';
			echo '<div class="field-list field-list-completed field field-student-dashboard">';
			echo $field_list;
			echo '</div>';
			echo '</div>';
			echo '<div class="clearfix"></div>';
		}
		?>
	</div>  <!-- student-dashboard-wrapper -->
<?php
} else {
	//ob_start();
	// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
	wp_redirect( get_option( 'use_custom_login_form', 1 ) ? FieldPress::instance()->get_signup_slug( true ) : wp_login_url() );
	exit;
}