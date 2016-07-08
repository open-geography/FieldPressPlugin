<?php

$field_thumbnail = Field::get_field_thumbnail( get_the_ID() );
if ( ! $field_thumbnail ) {
	$extended_class = 'quick-field-info-extended';
}

?>

<?php

// Replaces thumbnail with media
echo do_shortcode( '[field_media list_page="yes"]' );
?>

<?php
// Flat hyperlinked list of instructors
echo do_shortcode( '[field_instructors style="list-flat" link="true"]' );
?>

<?php
// Field summary/excerpt
echo do_shortcode( '[field_summary length="50"]' );
?>

<div class="quick-field-info <?php echo( isset( $extended_class ) ? esc_attr( $extended_class ) : '' ); ?>">
	<?php echo do_shortcode( '[field_start label="" class="field-time"]' ); ?>
	<?php echo do_shortcode( '[field_language label="" class="field-lang"]' ); ?>
	<?php echo do_shortcode( '[field_cost label="" class="field-cost" show_icon="true"]' ); ?>
	<?php echo do_shortcode( '[field_join_button list_page="yes"]' ); ?>
</div>