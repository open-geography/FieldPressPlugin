<?php
global $action, $page;
wp_reset_vars( array( 'action', 'page' ) );

$wp_field_search = new Field_Search();

if ( $wp_field_search->is_light ) {
	if ( $wp_field_search->get_count_of_all_fields() >= 10 && ! isset( $_GET['field_id'] ) ) {
		wp_redirect( admin_url( 'admin.php?page=fields' ) );
		exit;
	}
}

$stop_id   = '';
$field_id = '';

if ( isset( $_GET['field_id'] ) && is_numeric( $_GET['field_id'] ) ) {
	$field_id = ( int ) $_GET['field_id'];
}

if ( isset( $_GET['stop_id'] ) && is_numeric( $_GET['stop_id'] ) ) {
	$stop_id = ( int ) $_GET['stop_id'];
}

$field = new Field( $field_id );

if ( empty( $field ) ) {
	$field = new StdClass;
} else {
	$field_object = $field->get_field();
}

$stops          = $field->get_stops();
$students_count = $field->get_number_of_students();
?>

<div class="wrap nosubsub field-details cp-wrap">
	<div class="icon32" id="icon-themes"><br></div>
	<?php
	$tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : '';
	if ( empty( $tab ) ) {
		$tab = 'overview';
	}
	?>

	<h2><?php
		if ( $field_id == '' ) {
			_e( 'New Field Trip', 'cp' );
		}
		if ( $field_id != '' ) {
			_e( 'Field Trip', 'cp' );
		}

		if ( ! isset( $_GET['field_id'] ) ) {
			$field          = new StdClass;
			$field->details = null;
		}

		if ( $field_id != '' ) {
			if ( $tab != 'overview' ) {
				echo ' &raquo; ' . $field->details->post_title . ' &raquo; ' . esc_html( ucfirst( $tab ) );
			} else {
				echo ' &raquo; ' . $field->details->post_title;
			}
		}
		?>
	</h2>

	<?php
	$message['ca']  = __( 'Field Trip added successfully!', 'cp' );
	$message['cu']  = __( 'Field Trip updated successfully.', 'cp' );
	$message['usc'] = __( 'Stop status changed successfully', 'cp' );
	$message['ud']  = __( 'Stop deleted successfully', 'cp' );
	$message['ua']  = __( 'New Stop added successfully!', 'cp' );
	$message['uu']  = __( 'Stop updated successfully.', 'cp' );
	$message['as']  = __( 'Student added to the group successfully.', 'cp' );
	$message['ac']  = __( 'New group has been added successfully.', 'cp' );
	$message['dc']  = __( 'Selected group has been deleted successfully.', 'cp' );
	$message['us']  = __( 'Selected student has been withdrawed successfully from the field trip.', 'cp' );
	$message['usl'] = __( 'Selected students has been withdrawed successfully from the field trip.', 'cp' );
	$message['is']  = __( 'Invitation sent sucessfully.', 'cp' );
	$message['ia']  = __( 'Successfully added as instructor.', 'cp' );

	$error_message['wrong_email'] = __( 'Please enter valid e-mail address', 'cp' );

	if ( isset( $_GET['stop_id'] ) && isset( $_GET['new_status'] ) ) {
		$_GET['ms'] = 'usc';
	}

	if ( isset( $_GET['stop_id'] ) && isset( $_GET['action'] ) && $_GET['action'] == 'delete_stop' ) {
		$_GET['ms'] = 'ud';
	}


	$ms = null;
	if ( isset( $_GET['ms'] ) ) {
		$ms = $_GET['ms'];
	}

	$ems = null;
	if ( isset( $_GET['ems'] ) ) {
		$ems = $_GET['ems'];
	}

	if ( isset( $ms ) ) {
		?>
		<div id="message" class="updated fade"><p><?php echo $message[ $ms ]; ?></p></div>
	<?php
	}

	if ( isset( $ems ) ) {
		?>
		<div id="message" class="error fade"><p><?php echo $error_message[ $ems ]; ?></p></div>
	<?php
	}
	?>

	<?php
	$menus             = array();
	$menus['overview'] = __( 'Field Trip Overview', 'cp' );
	$menus['stops']    = __( 'Stops', 'cp' ) . ( count( $stops ) >= 1 ? ' ( ' . count( $stops ) . ' )' : '' );
	$menus['students'] = __( 'Students', 'cp' ) . ( $students_count >= 1 ? ' ( ' . $students_count . ' )' : '' );
	$menus             = apply_filters( 'fieldpress_field_new_menus', $menus );
	?>

	<h3 class="nav-tab-wrapper">
		<?php
		foreach ( $menus as $key => $menu ) {
			if ( $key == 'overview' || ( $key != 'overview' && $field_id != '' ) ) {
				?>
				<a class="nav-tab<?php
				if ( $tab == $key ) {
					echo ' nav-tab-active';
				}
				?>" href="<?php echo esc_attr( admin_url( 'admin.php?page=' . $page . '&amp;tab=' . $key . '&amp;field_id=' . $field_id ) ); ?>"><?php echo $menu; ?></a>
			<?php
			}
		}


		/* if ( $field_id != '' ) {
			 $field = new Field( $field_id );
			 if ( $field->can_show_permalink() ) {
			 ?>
			 <a class="nav-tab view-field-link" href="<?php echo get_permalink( $field_id ); ?>" target="_new"><?php _e( 'View Field Trip', 'cp' ); ?></a>
			 <?php
			 }
			 } */
		?>

		<?php
		/* if ( $stop_id != '' ) {
		  $stop = new Field( $stop_id );
		  if ( $stop->can_show_permalink() ) {
		  ?>
		  <a class="nav-tab view-field-link" href="<?php echo get_permalink( $stop_id ); ?>" target="_new"><?php _e( 'View Stop', 'cp' );?></a>
		  <?php
		  }
		  } */
		?>
		<?php if ( isset( $field_id ) && $field_id !== '' ) { ?>

			<div class="field-state">
				<?php
				$can_publish = FieldPress_Capabilities::can_change_field_status( $field_id );
				$data_nonce  = wp_create_nonce( 'toggle-' . $field_id );
				?>
				<div id="field_state_id" data-id="<?php echo $field_id ?>" data-nonce="<?php echo $data_nonce; ?>"></div>
				<span class="publish-field-message"><?php _e( 'Publish Field Trip', 'cp' ); ?></span>
				<span class="draft <?php echo ( $field_object->post_status == 'unpublished' ) ? 'on' : '' ?>"><i class="fa fa-ban"></i></span>

				<div class="control <?php echo $can_publish ? '' : 'disabled'; ?> <?php echo ( $field_object->post_status == 'unpublished' ) ? '' : 'on' ?>">
					<div class="toggle"></div>
				</div>
				<span class="live <?php echo ( $field_object->post_status == 'unpublished' ) ? '' : 'on' ?>"><i class="fa fa-check"></i></span>
			</div>
		<?php } ?>
	</h3>

	<?php
	switch ( $tab ) {

		case 'overview':
			$this->show_fields_details_overview();
			break;

		case 'stops':
			$this->show_fields_details_stops();
			break;

		case 'students':
			$this->show_fields_details_students();
			break;

		default:
			do_action( 'fieldpress_fields_details_menu_' . $tab );
			break;
	}
	?>

</div>