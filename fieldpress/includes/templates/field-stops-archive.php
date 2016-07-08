<?php

//$field_id			 = do_shortcode( '[get_parent_field_id]' );
//$field_id			 = (int) $field_id;
FieldPress::instance()->check_access( $field_id );
$progress = do_shortcode( '[field_progress field_id="' . $field_id . '"]' );

do_shortcode( '[field_stops_loop]' ); //required for getting stop results

?>

<?php
echo do_shortcode( '[field_stop_archive_submenu]' );
$complete_message = '';
if ( 100 == (int) $progress ) {
	$complete_message = '<div class="stop-archive-field-complete cp-wrap"><i class="fa fa-check-circle"></i> ' . __( 'Field Trip Complete', 'cp' ) . '</div>';
}

?>
<h2><?php _e( 'Field Trip Stops', 'cp' );
	echo ' ' . $complete_message; ?></h2>
<div class="stops-archive">
	<ul class="stops-archive-list">
		<?php
		$stops = Field::get_stops_with_modules( $field_id );
		if ( ! empty( $stops ) && count( $stops ) > 0 ) {

			foreach ( $stops as $stop_id => $stop ) {
				$draft      = $stop['post']->post_status !== 'publish';
				$show_draft = $draft && cp_can_see_stop_draft();

				if ( $draft && ! $show_draft ) {
					continue;
				}

				$additional_class    = '';
				$additional_li_class = '';

				$additional_content = '';
				if ( ! Stop::is_stop_available( $stop_id ) ) {
					$additional_class    = 'locked-stop';
					$additional_li_class = 'li-locked-stop';
					$additional_content  = '<div class="' . esc_attr( $additional_class ) . '"></div>';
				}

				$stop_progress = do_shortcode( '[field_stop_percent field_id="' . $field_id . '" stop_id="' . $stop_id . '" format="true" style="flat"]' );

				?>
				<li class="stop stop-<?php echo $stop_id; ?> <?php echo esc_attr( $additional_li_class ); ?>">
					<?php echo $additional_content; ?>
					<div class="stop-archive-single">
						<?php echo do_shortcode( '[field_stop_title stop_id="' . $stop_id . '" link="yes" last_page="no"]' ); ?>
						<?php echo $stop_progress; ?>
						<?php echo do_shortcode( '[module_status format="true" field_id="' . $field_id . '" stop_id="' . $stop_id . '"]' ); ?>
					</div>
				</li>
				<?php
			}
		} else {
			?>
			<p class="zero-field-stops"><?php _e( "0 stops in the field trip currently. Please check back later.", "cp" ); ?></p>
		<?php
		}
		?>
	</ul>
</div>