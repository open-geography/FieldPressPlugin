<?php
/**
 * The stops archive / grades template file
 *
 * @package FieldPress
 */
global $fieldpress;
$field_id = do_shortcode( '[get_parent_field_id]' );
$field_id = (int) $field_id;
$progress  = do_shortcode( '[field_progress field_id="' . $field_id . '"]' );

//redirect to the parent field page if not enrolled
$fieldpress->check_access( $field_id );

add_thickbox();
?>

<?php

if ( 100 == ( int ) $progress ) {
	$complete_message         = '<span class="stop-archive-field-complete cp-wrap"><i class="fa fa-check-circle"></i> ' . __( 'Field Trip Complete', 'cp' ) . '</span>';
	$workbook_field_progress = '';
} else {
	$complete_message         = '';
	$workbook_field_progress = '<span class="workbook-field-progress">' . __( 'Field progress: ', 'cp' ) . esc_html( $progress ) . '%' . '</span>';
}

?>

<?php echo do_shortcode( '[field_stop_archive_submenu]' ); ?>

	<h2 class="workbook-title">
		<?php echo __( 'Workbook', 'cp' );
		echo $workbook_field_progress;
		echo $complete_message; ?>
	</h2>

	<div class="clearfix"></div>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$criteria           = Stop::get_module_completion_data( get_the_ID() );
		$input_module_count = count( $criteria['all_input_ids'] );
		$has_assessable     = $input_module_count > 0 ? true : false;
		?>
		<div class="workbook_stops cp-wrap">
			<div class="stop_title">
				<h3><?php the_title(); ?>
					<span><?php _e( 'Stop progress: ', 'cp' ); ?> <?php echo do_shortcode( '[field_stop_progress field_id="' . $field_id . '" stop_id="' . get_the_ID() . '"]' ); ?>
						%</span>
				</h3>
			</div>
			<?php if ( $has_assessable ) { ?>
				<div class="accordion-inner">
					<?php
					echo do_shortcode( '[student_workbook_table]' );
					?>
				</div>
			<?php } else { ?>
				<div class="accordion-inner">
					<div class="zero-inputs"><?php _e( 'There are no activities to complete in this stop.', 'cp' ); ?></div>
				</div>
			<?php } ?>
		</div>
	<?php
	} // While
} else {
	?>
	<div class="zero-fields"><?php _e( '0 Stops in the field trip', 'cp' ); ?></div>
<?php
}
?>