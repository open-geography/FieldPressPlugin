<?php
/**
 * The stops archive template file
 *
 * @package FieldPress
 */
global $fieldpress;
$field_id = do_shortcode( '[get_parent_field_id]' );
$field_id = (int) $field_id;
$progress  = do_shortcode( '[field_progress field_id="' . $field_id . '"]' );
//redirect to the parent field page if not enrolled
$fieldpress->check_access( $field_id );

get_header();
?>
	<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<h1><?php echo get_the_title( $field_id ) ?></h1>
	<div class="instructors-content">
		<?php
		// Flat hyperlinked list of instructors
		echo do_shortcode( '[field_instructors style="list-flat" link="true" field_id="' . $field_id . '"]' );
		?>
	</div>

<?php
echo do_shortcode( '[field_stop_archive_submenu]' ) . '&nbsp;';
?>
<?php
if ( 100 == (int) $progress ) {
	echo sprintf( '<div class="stop-archive-field-complete">%s %s</div>', '<i class="fa fa-check-circle"></i>', __( 'Field Trip Complete', 'cp' ) );
}
?>

	<div class="clearfix"></div>
	<ul class="stops-archive-list">
	<?php
	$stops = Field::get_stops_with_modules( $field_id );

	if ( ! empty( $stops ) && count( $stops ) > 0 ) {

		foreach ( $stops as $stop_id => $stop ) {
			$post                = $stop['post'];
			$additional_class    = '';
			$additional_li_class = '';

			$is_stop_available = Stop::is_stop_available( $stop_id );

			if ( ! $is_stop_available ) {
				$additional_class    = 'locked-stop';
				$additional_li_class = 'li-locked-stop';
			}

			$stop_progress = do_shortcode( '[field_stop_percent field_id="' . $field_id . '" stop_id="' . $stop_id . '" format="true" style="extended"]' );

			?>
			<li class="<?php echo $additional_li_class; ?>">
				<div class='<?php echo $additional_class; ?>'></div>
				<div class="stop-archive-single">
					<?php echo $stop_progress; ?>
					<?php echo do_shortcode( '[field_stop_title stop_id="' . $stop_id . '" link="yes" last_page="yes"]' ); ?>
					<?php echo do_shortcode( '[module_status format="true" field_id="' . $field_id . '" stop_id="' . $stop_id . '"]' ); ?>
				</div>
			</li>
			<?php
		}
	} else {
		?>
		<h1 class="zero-field-stops"><?php _e( "0 stops in the field trip currently. Please check back later." ); ?></h1>
		<?php
	}

?>
</ul>
</main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar( 'footer' ); ?>
<?php get_footer(); ?>