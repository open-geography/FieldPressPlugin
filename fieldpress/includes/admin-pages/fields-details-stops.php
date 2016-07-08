<?php
global $fieldpress;

$field_id = '';
$stop_id   = '';

if ( isset( $_GET['field_id'] ) && is_numeric( $_GET['field_id'] ) ) {
	$field_id = ( int ) $_GET['field_id'];
	$field    = new Field( $field_id );
	$stops     = $field->get_stops();
}

if ( ! empty( $field_id ) && ! FieldPress_Capabilities::can_view_field_stops( $_GET['field_id'] ) ) {
	die( __( 'You do not have required permissions to access this page.', 'cp' ) );
}

if ( isset( $_GET['stop_id'] ) ) {
	$stop_id = ( int ) $_GET['stop_id'];
	$stop    = new Stop( $stop_id );
}

if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete_stop' && isset( $_GET['stop_id'] ) && is_numeric( $_GET['stop_id'] ) ) {
	$stop        = new Stop( $stop_id );
	$stop_object = $stop->get_stop();
	if ( FieldPress_Capabilities::can_delete_field_stop( $field_id, $stop_id ) ) {
		$stop->delete_stop( $force_delete = true );
	}
	$stops = $field->get_stops();
}

if ( isset( $_GET['action'] ) && $_GET['action'] == 'change_status' && isset( $_GET['stop_id'] ) && is_numeric( $_GET['stop_id'] ) ) {
	$stop        = new Stop( $_GET['stop_id'] );
	$stop_object = $stop->get_stop();
	if ( FieldPress_Capabilities::can_change_field_stop_status( $field_id, $stop_id ) ) {
		$stop->change_status( $_GET['new_status'] );
	}
}

if ( isset( $_GET['action'] ) && $_GET['action'] == 'add_new_stop' || ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && isset( $_GET['stop_id'] ) ) ) {

	$fieldpress->stop_page_num        = ! empty( $_REQUEST['stop_page_num'] ) ? ( int ) $_REQUEST['stop_page_num'] : 1;
	$fieldpress->active_element       = isset( $_REQUEST['active_element'] ) ? $_REQUEST['active_element'] : ( $fieldpress->stop_page_num == 1 ? 0 : 1 );
	$fieldpress->preview_redirect_url = isset( $_REQUEST['preview_redirect_url'] ) ? $_REQUEST['preview_redirect_url'] : '';
	$this->show_stop_details( $fieldpress->stop_page_num, $fieldpress->active_element, $fieldpress->preview_redirect_url );
} else {
	$first_stop_id = isset( $stops[0]->ID ) ? $stops[0]->ID : '';
	// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
	if ( isset( $first_stop_id ) && is_numeric( $first_stop_id ) ) {
		wp_redirect( admin_url( "admin.php?page=field_details&tab=stops&field_id=" . $field_id . "&stop_id=" . $first_stop_id . "&action=edit" ) );
		exit;
	} else {
		wp_redirect( admin_url( "admin.php?page=field_details&tab=stops&field_id=" . $field_id . "&action=add_new_stop" ) );
		exit;
	}
	?>

	<ul id="sortable-stops">
		<?php
		$list_order = 1;
		foreach ( $stops as $stop ) {
			$stop_id = $stop['post']->ID;
			$stop_object = new Stop( $stop_id );
			$stop_object = $stop_object->get_stop();
			?>
			<li class="postbox ui-state-default clearfix">
				<div class="stop-order-number">
					<div class="numberCircle"><?php echo $list_order; ?></div>
				</div>
				<div class="stop-title">
					<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&stop_id=' . $stop_object->ID . '&action=edit' ) ?>"><?php echo $stop_object->post_title; ?></a>
				</div>
				<div class="stop-description"><?php echo cp_get_the_field_excerpt( $stop_object->ID, 28 ); ?></div>

				<?php if ( FieldPress_Capabilities::can_delete_field_stop( $field_id, $stop_object->ID ) ) { ?>
					<div class="stop-remove">
						<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&stop_id=' . $stop_object->ID . '&action=delete_stop' ); ?>" onClick="return removeStop();">
							<i class="fa fa-times-circle cp-move-icon remove-btn"></i>
						</a></div>
				<?php } ?>

				<div class="stop-buttons stop-control-buttons">
					<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&stop_id=' . $stop_object->ID . '&action=edit' ); ?>" class="button button-stops save-stop-button"><?php _e( 'Settings', 'cp' ); ?></a>
					<?php if ( FieldPress_Capabilities::can_change_field_stop_status( $field_id, $stop_object->ID ) ) { ?>
						<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&stop_id=' . $stop_object->ID . '&action=change_status&new_status=' . ( $stop_object->post_status == 'unpublished' ) ? 'publish' : 'private' ); ?>" class="button button-<?php echo ( $stop_object->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>"><?php echo ( $stop_object->post_status == 'unpublished' ) ? __( 'Publish', 'cp' ) : __( 'Unpublish', 'cp' ); ?></a>
					<?php } ?>
				</div>

				<input type="hidden" class="stop_order" value="<?php echo $list_order; ?>" name="stop_order_<?php echo $stop_object->ID; ?>"/>
				<input type="hidden" name="stop_id" class="stop_id" value="<?php echo $stop_object->ID; ?>"/>
			</li>
			<?php
			$list_order ++;
		}
		?>
	</ul>
	<?php if ( FieldPress_Capabilities::can_create_field_stop( $field_id ) ) { ?>
		<ul>
			<li class="postbox ui-state-fixed ui-state-highlight add-new-stop-box">
				<div class="add-new-stop-title">
					<span class="plusTitle"><a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&action=add_new_stop' ); ?>"><?php _e( 'Add new Stop', 'cp' ); ?></a></span>
				</div>
			</li>
		</ul>
	<?php } ?>

<?php } ?>