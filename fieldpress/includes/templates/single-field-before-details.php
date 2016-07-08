<?php
// Show field media
echo do_shortcode( '[field_media]' );

// Show field summary/excerpt
echo do_shortcode( '[field_summary length="50"]' );
?>

	<div class="divider"></div>
	<div class="enroll-box">
		<div class="enroll-box-left">
			<div class="field-box">
				<?php echo do_shortcode( '[field_dates show_alt_display="yes"]' ); ?>
				<?php echo do_shortcode( '[field_enrollment_dates show_enrolled_display="no"]' ); ?>
				<?php echo do_shortcode( '[field_class_size]' ); ?>
				<?php echo do_shortcode( '[field_enrollment_type]' ); ?>
				<?php echo do_shortcode( '[field_language]' ); ?>
				<?php echo do_shortcode( '[field_cost]' ); ?>
			</div>
		</div>
		<div class="enroll-box-right">
			<div class="apply-box">
				<?php echo do_shortcode( '[field_join_button]' ); ?>
			</div>
		</div>
	</div>
	<div class="divider"></div>

<?php
//List of instructors
echo do_shortcode( '[field_instructors show_label="yes" label_element="h2" label_delimeter="" class="instructors-box"]' );
?>

<?php
// Field Trip Stop
echo do_shortcode( '[field_stop show_label="yes" label_element="h2" label_delimeter="" show_title="no" show_divider="yes"]' );
?>