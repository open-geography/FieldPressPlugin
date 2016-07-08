<?php
// Avatar
echo do_shortcode( '[field_instructor_avatar instructor_id="' . $user->ID . '"]' );
// Bio
echo get_user_meta( $user->ID, 'description', true );
?>

	<h2 class="h2-instructor-bio"><?php _e( 'Field Trips', 'cp' ); ?></h2>

<?php
// Field List
echo do_shortcode( '[field_list instructor="' . $user->ID . '" class="field" left_class="enroll-box-left" right_class="enroll-box-right" field_class="enroll-box" title_link="yes" show_media="yes"]' );
?>