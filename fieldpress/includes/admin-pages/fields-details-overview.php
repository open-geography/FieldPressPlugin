<?php
global $page, $user_id, $cp_admin_notice, $fieldpress, $mp;

add_editor_style( FieldPress::instance()->plugin_url . 'css/editor_style_fix.css' );

add_thickbox();

if ( isset( $_GET['field_id'] ) ) {
	$field         = new Field( (int) $_GET['field_id'] );
	$field_details = $field->get_field();
	$field_id      = (int) $_GET['field_id'];
} else {
	$field    = new Field();
	$field_id = 0;
}

if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'add' || $_POST['action'] == 'update' ) ) {

	check_admin_referer( 'field_details_overview' );

	/* if ( $_POST['meta_field_category'] != -1 ) {
	  $term = get_term_by( 'id', $_POST['meta_field_category'], 'field_category' );
	  wp_set_object_terms( $field_id, $term->slug, 'field_category', false );
	  } */

	// Field has a start date, but no end date
	if ( ! isset( $_POST['meta_open_ended_field'] ) ) {
		$_POST['meta_open_ended_field'] = 'off';
	}

	// Users can enroll anytime
	if ( ! isset( $_POST['meta_open_ended_enrollment'] ) ) {
		$_POST['meta_open_ended_enrollment'] = 'off';
	}

	// Limit field trip group size?
	if ( ! isset( $_POST['meta_limit_class_size'] ) ) {
		$_POST['meta_limit_class_size'] = 'off';
	}

	// Enable/disable Field Trip Stop preview options
	if ( ! isset( $_POST['meta_field_stop_options'] ) ) {
		$_POST['meta_field_stop_options'] = 'off';
	}

	// Enable/disable field time preview
	if ( ! isset( $_POST['meta_field_stop_time_display'] ) ) {
		$_POST['meta_field_stop_time_display'] = 'off';
	}

	if ( ! isset( $_POST['meta_allow_field_discussion'] ) ) {
		$_POST['meta_allow_field_discussion'] = 'off';
	}

	if ( ! isset( $_POST['meta_allow_field_grades_page'] ) ) {
		$_POST['meta_allow_field_grades_page'] = 'off';
	}

	if ( ! isset( $_POST['meta_allow_workbook_page'] ) ) {
		$_POST['meta_allow_workbook_page'] = 'off';
	}

	if ( ! isset( $_POST['meta_paid_field'] ) ) {
		$_POST['meta_paid_field'] = 'off';
	}

	if ( ! isset( $_POST['meta_auto_sku'] ) ) {
		$_POST['meta_auto_sku'] = 'off';
	}

	if ( isset( $_POST['submit-stop'] ) ) {
		/* Save / Save Draft */
		$new_post_id = $field->update_field();
	}

	if ( isset( $_POST['submit-stop-publish'] ) ) {
		/* Save & Publish */
		$new_post_id = $field->update_field();
		$field      = new Field( $new_post_id );
		$field->change_status( 'publish' );
	}

	if ( isset( $_POST['submit-stop-unpublish'] ) ) {
		/* Save & Unpublish */
		$new_post_id = $field->update_field();
		$field      = new Field( $new_post_id );
		$field->change_status( 'private' );
	}


	if ( $new_post_id != 0 ) {
		// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
		ob_start();
		if ( isset( $_GET['ms'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=' . $page . '&field_id=' . (int) $new_post_id . '&ms=' . $_GET['ms'] ) );
			exit;
		} else {
			wp_redirect( admin_url( 'admin.php?page=' . $page . '&field_id=' . (int) $new_post_id ) );
			exit;
		}
	} else {
		//an error occured
	}
}

if ( isset( $_GET['field_id'] ) ) {
	$class_size                    = $field->details->class_size;
	$enroll_type                   = $field->details->enroll_type;
	$passcode                      = $field->details->passcode;
	$prerequisite                  = $field->details->prerequisite;
	$field_start_date             = $field->details->field_start_date;
	$field_end_date               = $field->details->field_end_date;
	$field_start_time             = $field->details->field_start_time;
    $field_end_time               = $field->details->field_end_time;
	$enrollment_start_date         = $field->details->enrollment_start_date;
	$enrollment_end_date           = $field->details->enrollment_end_date;
	$open_ended_field             = $field->details->open_ended_field;
	$open_ended_enrollment         = $field->details->open_ended_enrollment;
	$limit_class_size              = $field->details->limit_class_size;
	$marketpress_product           = $field->details->marketpress_product;
	$woo_product                   = $field->details->woo_product;
	$allow_field_discussion       = $field->details->allow_field_discussion;
	$allow_field_grades_page      = $field->details->allow_field_grades_page;
	$allow_workbook_page           = $field->details->allow_workbook_page;
	$paid_field                   = ( $fieldpress->marketpress_active || cp_use_woo() ) ? $field->details->paid_field : false;
	$auto_sku                      = $field->details->auto_sku;
	$field_terms                  = wp_get_post_terms( (int) $_GET['field_id'], 'field_category' );
	$field_category               = is_array( $field_terms ) ? ( isset( $field_terms[0] ) ? $field_terms[0]->term_id : 0 ) : 0; //$field->details->field_category;
	$language                      = $field->details->field_language;
	$field_video_url              = $field->details->field_video_url;
	$field_setup_progress         = empty( $field->details->field_setup_progress ) ? array(
		'step-1' => 'incomplete',
		'step-2' => 'incomplete',
		'step-3' => 'incomplete',
		'step-4' => 'incomplete',
		'step-5' => 'incomplete',
		'step-6' => 'incomplete',
	) : maybe_unserialize( $field->details->field_setup_progress );
	$field_setup_marker           = empty( $field->details->field_setup_marker ) ? 'step-1' : $field->details->field_setup_marker;
	$field_stop_options      = $field->details->field_stop_options;
	$field_stop_time_display = $field->details->field_stop_time_display;

	$field_setup_complete = get_post_meta( (int) $_GET['field_id'], 'field_setup_complete', true );

	if ( ! empty( $field_setup_complete ) && 'yes' == $field_setup_complete ) {
		$field_setup_marker = '';
	}

	//$show_module = $field->details->show_module;
	//$preview_module = $field->details->preview_module;

	$show_stop    = $field->details->show_stop_boxes;
	$preview_stop = $field->details->preview_stop_boxes;

	$show_page    = $field->details->show_page_boxes;
	$preview_page = $field->details->preview_page_boxes;
} else {
	$class_size                    = 0;
	$enroll_type                   = '';
	$passcode                      = '';
	$prerequisite                  = '';
	$field_start_date             = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
	$field_end_date               = '';
	$field_end_time               = '';
	$field_end_time               = '';
	$enrollment_start_date         = '';
	$enrollment_end_date           = '';
	$open_ended_field             = 'off';
	$open_ended_enrollment         = 'off';
	$limit_class_size              = 'off';
	$marketpress_product           = '';
	$woo_product                   = '';
	$allow_field_discussion       = 'off';
	$allow_field_grades_page      = 'off';
	$allow_workbook_page           = 'off';
	$field_category               = 0;
	$language                      = __( 'English', 'cp' );
	$field_video_url              = '';
	$field_setup_progress         = array(
		'step-1' => 'incomplete',
		'step-2' => 'incomplete',
		'step-3' => 'incomplete',
		'step-4' => 'incomplete',
		'step-5' => 'incomplete',
		'step-6' => 'incomplete',
	);
	$field_setup_marker           = 'step-1';
	$field_stop_options      = 'off';
	$field_stop_time_display = 'off';
}

// Fix issue where previous versions caused nested serial objects when duplicating fields.
$field_setup_progress = cp_deep_unserialize( $field_setup_progress );


// Detect gateways for MarketPress
// MarketPress 2.x and MarketPress Lite
$mp_settings = get_option( 'mp_settings' );
$gateways    = ! empty( $mp_settings['gateways']['allowed'] ) ? true : false;

/**
 * Filter to enable or disable payable fields.
 *
 * @since 1.2.1
 */
$offer_paid = apply_filters( 'fieldpress_offer_paid_fields', true );
?>
<div class='wrap nofieldsub cp-wrap'>
	<form action='<?php esc_attr_e( admin_url( 'admin.php?page=' . $page . ( ( $field_id !== 0 ) ? '&field_id=' . $field_id : '' ) . ( ( $field_id !== 0 ) ? '&ms=cu' : '&ms=ca' ) ) ); ?>' name='field-add' id='field-add' method='post'>

		<?php
		$can_update = 0 == $field_id || FieldPress_Capabilities::can_update_field( $field_id );
		$data_nonce = wp_create_nonce( 'auto-update-' . $field_id );
		?>

		<input type='hidden' name='field-ajax-check' id="field-ajax-check" data-id="<?php echo $field_id; ?>" data-uid="<?php echo $can_update ? get_current_user_id() : ''; ?>" data-nonce="<?php echo $data_nonce; ?>" value=""/>

		<div class='field-liquid-left'>

			<div id='field'>
				<?php if ( 0 == $field_id && FieldPress_Capabilities::can_create_field() || FieldPress_Capabilities::can_update_field( $field_id ) ) { ?>
					<?php wp_nonce_field( 'field_details_overview' ); ?>

					<?php if ( isset( $field_id ) ) { ?>
						<input type="hidden" name="field_id" value="<?php echo esc_attr( $field_id ); ?>"/>
						<?php
						if ( FieldPress_Capabilities::can_update_field( $field_id ) || 0 == $field_id ) {
							?>
							<input type="hidden" name="admin_url" value="<?php echo esc_attr( admin_url( 'admin.php?page=field_details' ) ); ?>"/>
						<?php } ?>
						<input type="hidden" name="action" value="update"/>
					<?php } else { ?>
						<input type="hidden" name="action" value="add"/>
					<?php } ?>


					<div id='edit-sub' class='field-holder-wrap mp-wrap'>

						<div class='sidebar-name no-movecursor'>
							<h3><?php _e( 'Field Trip Setup', 'cp' ); ?></h3>
						</div>

						<div class='field-holder'>

							<!-- FIELD BUTTONS -->
							<div class="stop-control-buttons field-control-buttons">

								<?php /* if (( $field_id == 0 && current_user_can('fieldpress_create_field_cap'))) {//do not show anything
								  ?>
								  <input type="submit" name="submit-stop" class="button button-stops save-stop-button" value="<?php _e('Save Draft', 'cp'); ?>">
								  <input type="submit" name="submit-stop-publish" class="button button-stops button-publish" value="<?php _e('Publish', 'cp'); ?>">

								  <?php } */ ?>

								<?php /* if (( $field_id != 0 && current_user_can('fieldpress_update_field_cap') ) || ( $field_id != 0 && current_user_can('fieldpress_update_my_field_cap') && $field_details->post_author == get_current_user_id() )) {//do not show anything
								  ?>
								  <input type="submit" name="submit-stop" class="button button-stops save-stop-button" value="<?php echo ( $field_details->post_status == 'unpublished' ) ? __('Save Draft', 'cp') : __('Publish', 'cp'); ?>">
								  <?php } */ ?>

								<?php
								if ( $field_id != 0 && FieldPress_Capabilities::can_update_field( $field_id ) ) {//do not show anything
									?>
									<a class="button button-preview-overview" href="<?php echo get_permalink( $field_id ); ?>" target="_new"><?php _e( 'Preview', 'cp' ); ?></a>

									<?php
									/* if (current_user_can('fieldpress_change_field_status_cap') || ( current_user_can('fieldpress_change_my_field_status_cap') && $field_details->post_author == get_current_user_id() )) { ?>
									  <input type="submit" name="submit-stop-<?php echo ( $field_details->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>" class="button button-stops button-<?php echo ( $field_details->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>" value="<?php echo ( $field_details->post_status == 'unpublished' ) ? __('Publish', 'cp') : __('Unpublish', 'cp'); ?>">
									  <?php
									  } */
								}
								?>
							</div>
							<!-- /FIELD BUTTONS -->

							<!-- Field Trip Details -->
							<div class='field-details'>
								<?php
								$wp_field_search = new Field_Search( '', 1 );
								if ( FieldPress_Capabilities::can_create_field() ) {
									if ( $wp_field_search->is_light ) {
										if ( $wp_field_search->get_count_of_all_fields() < $wp_field_search->fields_per_page ) {
											$not_limited = true;
										} else {
											$not_limited = false;
										}
									} else {
										$not_limited = true;
									}
								}

								if ( ( isset( $_GET['field_id'] ) ) || ! isset( $_GET['field_id'] ) && $not_limited ) {
									?>
									<!--Field Trip Overview -->
									<div class="field-section step step-1 <?php echo 'step-1' == $field_setup_marker ? 'save-marker active' : ''; ?>">
										<div class='field-section-title'>
											<div class="status <?php echo empty( $field_setup_progress['step-1'] ) ? '' : $field_setup_progress['step-1']; ?> "></div>
											<h3><?php _e( 'Step 1 - Field Trip Overview', 'cp' ) ?></h3>
										</div>
										<div class='field-form'>
											<?php
											$set_status = $field_setup_progress['step-1'];
											?>
											<input type='hidden' name='meta_field_setup_progress[step-1]' class='field_setup_progress' value="<?php echo esc_attr( $set_status ); ?>"/>

											<div class="wide">
												<label for='field_name' class="required">
													<?php _e( 'Field Trip Name', 'cp' ); ?>
												</label>
												<input class='wide' type='text' name='field_name' id='field_name' value='<?php
												if ( isset( $_GET['field_id'] ) ) {
													echo esc_attr( stripslashes( $field->details->post_title ) );
												}
												?>'/>
											</div>

											<div class="wide">
												<label for='field_excerpt' class="required">
													<?php _e( 'Field Trip Excerpt / Short Overview', 'cp' ); ?>
													<?php //CP_Helper_Tooltip::tooltip( __( 'Provide a few short sentences to describe the field trip', 'cp' ) );    ?>
												</label>
												<?php
												$editor_name    = "field_excerpt";
												$editor_id      = "field_excerpt";
												$editor_content = htmlspecialchars_decode( ( isset( $_GET['field_id'] ) ? $field_details->post_excerpt : '' ) );
												//
												//$args = array(
												//	"textarea_name"	 => $editor_name,
												//	"editor_class"	 => 'cp-editor cp-field-overview',
												//	"textarea_rows"	 => 3,
												//	"media_buttons"	 => false,
												//	"quicktags"		 => false,
												//);
												//
												//if ( !isset( $field_excerpt->post_excerpt ) ) {
												//	$field_excerpt					 = new StdClass;
												//	$field_excerpt->post_excerpt	 = '';
												//}
												//
												//$desc = '';
												//
												//// Filter $args
												//$args = apply_filters( 'fieldpress_element_editor_args', $args, $editor_name, $editor_id );
												//
												//wp_editor( $editor_content, $editor_id, $args );
												$editor = '<textarea id="' . $editor_id . '" name="' . $editor_name . '" class="fieldpress-editor">' . $editor_content . '</textarea>';
												echo trim( $editor );
												$supported_image_extensions = implode( ", ", cp_wp_get_image_extensions() );
												?>
											</div>

											<div class="wide narrow">
												<label for='featured_url'>
													<?php _e( 'Listing Image', 'cp' ); ?><br/>
													<span><?php _e( 'The image is used on the "Field Trip" listing ( archive ) page along with the Field Trip Excerpt.', 'cp' ) ?></span>
												</label>

												<div class="featured_url_holder">
													<input class="featured_url" type="text" size="36" name="meta_featured_url" value="<?php
													if ( $field_id !== 0 ) {
														echo esc_attr( $field->details->featured_url );
													}
													?>" placeholder="<?php _e( 'Add Image URL or Browse for Image', 'cp' ); ?>"/>
													<input class="featured_url_button button-secondary" type="button" value="<?php _e( 'Browse', 'cp' ); ?>"/>
													<input type="hidden" name="_thumbnail_id" id="thumbnail_id" value="<?php
													if ( $field_id !== 0 ) {
														echo esc_attr( get_post_meta( $field_id, '_thumbnail_id', true ) );
													}
													?>"/>
													<?php
													//get_the_post_thumbnail( $field_id, 'field_thumb', array( 100, 100 ) );
													//echo wp_get_attachment_image( get_post_meta( $field_id, '_thumbnail_id', true ), array( 100, 100 ) );
													//echo 'asdads'.get_post_meta( $field_id, '_thumbnail_id', true );
													?>
													<div class="invalid_extension_message"><?php echo sprintf( __( 'Extension of the file is not valid. Please use one of the following: %s', 'cp' ), $supported_image_extensions ); ?></div>
												</div>
											</div>

											<!-- v2 -->
											<div class="narrow">
												<label>
													<?php _e( 'Field Trip Category', 'cp' ); ?>
													<a class="context-link" href="edit-tags.php?taxonomy=field_category&post_type=field" target="_blank"><?php _e( 'Manage Categories', 'cp' ); ?></a>
												</label>
												<?php
												$x = '';
												//$taxonomies = get_object_taxonomies( 'field' );
												$taxonomies = array(
													'field_category'
												);

												$args = array(
													'orderby'      => 'name',
													'order'        => 'ASC',
													'hide_empty'   => false,
													'fields'       => 'all',
													'hierarchical' => true,
												);

												$terms = get_terms( $taxonomies, $args );

												//$field_terms = wp_get_post_terms( $field_id, 'field_category', array() );
												$field_terms = wp_get_object_terms( $field_id, $taxonomies );

												$field_terms_array = array();
												foreach ( $field_terms as $field_term ) {
													$field_terms_array[] = $field_term->term_id;
												}

												$class_extra = is_rtl() ? 'chosen-rtl' : '';
												?>

												<select name="meta_field_category" id="field_category" class="postform chosen-select-field <?php echo $class_extra; ?>" multiple="true">
													<?php
													foreach ( $terms as $terms ) {
														?>
														<option value="<?php echo $terms->term_id; ?>" <?php
														if ( in_array( $terms->term_id, $field_terms_array ) ) {
															echo 'selected';
														}
														?>><?php echo $terms->name; ?></option>
													<?php
													}
													?>
												</select>

											</div>

											<div class="narrow">
												<label for='meta_field_language'><?php _e( 'Field Trip Language', 'cp' ); ?></label>
												<input type="text" name="meta_field_language" value="<?php echo esc_attr( stripslashes( $language ) ); ?>"/>
											</div>

											<?php do_action( 'field_step_1_fields', $field_id ); ?>

											<div class="field-step-buttons">
												<input type="button" class="button button-stops next" value="<?php _e( 'Next', 'cp' ); ?>"/>
												<input type="button" class="button button-stops update" value="<?php _e( 'Update', 'cp' ); ?>"/>
											</div>
										</div>
									</div>
									<!-- /Field Trip Overview -->

									<!-- Field Trip Description -->

									<div class="field-section step step-2 <?php echo 'step-2' == $field_setup_marker ? 'save-marker active' : ''; ?>">
										<div class='field-section-title'>
											<div class="status <?php echo empty( $field_setup_progress['step-2'] ) ? '' : $field_setup_progress['step-2']; ?> "></div>
											<h3><?php _e( 'Step 2 - Field Trip Description', 'cp' ) ?></h3>
										</div>
										<div class='field-form'>
											<?php
											$set_status = $field_setup_progress['step-2'];
											?>
											<input type='hidden' name='meta_field_setup_progress[step-2]' class='field_setup_progress' value="<?php echo $set_status; ?>"/>

											<div class="wide narrow">
												<?php
												global $content_width;

												wp_enqueue_style( 'thickbox' );
												wp_enqueue_script( 'thickbox' );
												wp_enqueue_media();
												wp_enqueue_script( 'media-upload' );

												$supported_video_extensions = implode( ", ", wp_get_video_extensions() );

												if ( ! empty( $data ) ) {
													if ( ! isset( $data->player_width ) or empty( $data->player_width ) ) {
														$data->player_width = empty( $content_width ) ? 640 : $content_width;
													}
												}
												?>

												<div class="video_url_holder mp-wrap">
													<label for='meta_field_video_url'>
														<?php _e( 'Featured Video', 'cp' ); ?><br/>
														<span><?php _e( 'This is used on the Field Trip Overview page and will be displayed with the Field Trip Description.', 'cp' ); ?></span>
													</label>
													<input class="field_video_url" type="text" size="36" name="meta_field_video_url" value="<?php echo esc_attr( $field_video_url ); ?>" placeholder="<?php
													_e( 'Add URL or Browse', 'cp' );
													echo ' ( ' . $supported_video_extensions . ' )';
													?>"/>

													<input type="button" class="field_video_url_button button-secondary" value="<?php _e( 'Browse', 'cp' ); ?>"/>

													<div class="invalid_extension_message"><?php echo sprintf( __( 'Extension of the file is not valid. Please use one of the following: %s', 'cp' ), $supported_video_extensions ); ?></div>
												</div>
											</div>

											<div class="wide">
												<label for='field_description' class="required">
													<?php _e( 'Field Trip Description', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'Provide a detailed description of the field trip', 'cp' ) );       ?>
												</label>

												<p><?php _e( 'This is an in-depth description of the field trip. It should include such things like an overview, meeting time and locations, different locations introductions, etc.', 'cp' ); ?></p>
												<?php
												$editor_name    = "field_description";
												$editor_id      = "field_description";
												$editor_content = htmlspecialchars_decode( isset( $field_details->post_content ) ? $field_details->post_content : '' );

												//$args = array(
												//	"textarea_name"	 => $editor_name,
												//	"editor_class"	 => 'cp-editor cp-field-overview',
												//	"textarea_rows"	 => 10,
												//);
												//
												//if ( !isset( $field_details->post_content ) ) {
												//	$field_details					 = new StdClass;
												//	$field_details->post_content	 = '';
												//}
												//
												//$desc = '';
												//
												//// Filter $args before showing editor
												//$args = apply_filters( 'fieldpress_element_editor_args', $args, $editor_name, $editor_id );
												//
												//wp_editor( $editor_content, $editor_id, $args );

												$editor = '<textarea id="' . $editor_id . '" name="' . $editor_name . '" class="fieldpress-editor">' . $editor_content . '</textarea>';
												echo trim( $editor );

												?>
											</div>

											<!-- PLACEHOLDER -->
											<div class="wide">
												<label>
													<?php _e( 'Field Trip Stop', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'Provide a detailed description of the field trip', 'cp' ) );       ?>
													<br/>
													<span><?php _e( 'This gives you the option to show/hide Field Trip Stops (Locations), Estimated Time for each location on the Field Trip Overview page', 'cp' ); ?></span>
												</label>

												<div class="field-stop">
													<input type='checkbox' id='meta_field_stop_options' name='meta_field_stop_options' <?php echo ( $field_stop_options == 'on' ) ? 'checked' : ''; ?> />
													<label for="meta_field_stop_options"><?php _e( 'Show the Field Trip Overview stop and Preview Options', 'cp' ); ?></label><br/>
													<input type='checkbox' id='meta_field_stop_time_display' name='meta_field_stop_time_display' <?php echo ( $field_stop_time_display == 'on' ) ? 'checked' : ''; ?> />
													<label for="meta_field_stop_time_display"><?php _e( 'Display Time Estimates for Stops or Locations', 'cp' ); ?></label>
													<table>
														<thead>
															<tr>
																<th class="column-field-stop"><?php _e( 'Field Trip Stop', 'cp' ); ?></th>
																<th class="column-show" style = "padding-right: 110px;"><?php _e( 'Show', 'cp' ); ?></th>
																<th class="column-time"><?php _e( 'Time', 'cp' ); ?></th>
															</tr>
															<tr class="break">
																<td colspan="4"></td>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td colspan="4">
																	<ol class="tree">
																		<li>
																			<label for="field_<?php echo ( ! isset( $field ) || ! empty( $field->details ) ) ? $field->details->ID : '0'; ?>"><?php echo( ! isset( $field ) || ! empty( $field->details ) && $field->details->post_title && $field->details->post_title !== '' ? $field->details->post_title : __( 'Field Trip', 'cp' ) ); ?></label>
																			<input type="checkbox" checked disabled id="field_<?php echo isset( $field->details ) ? $field->details->ID : ''; ?>" class="hidden_checkbox"/>
																			<?php
																			$field_id = isset( $field ) && isset( $field->details ) && ! empty( $field->details->ID ) ? $field->details->ID : 0;
																			$stops     = Stop::get_stops_from_field( $field_id, 'any', false );
																			$stops     = ! empty( $stops ) ? $stops : array();
																			if ( 0 == count( $stops ) ) {
																				?>
																				<ol>
																					<li>
																						<label><?php _e( 'There are currently no stops to display', 'cp' ); ?></label>
																					</li>
																				</ol>
																			<?php } else {
																				?>
																				<ol>
																					<?php
																					// Cheking for inhertited "show" status and forces a save.
																					$section_dirty = false;

																					foreach ( $stops as $stop ) {
																						$stop_id = $stop['post']->ID;
																						$stop_post = $stop['post'];
																						$stop_class      = new Stop( $stop_id );
																						$stop_pages      = $stop_class->get_number_of_stop_pages();
																						$stop_pagination = cp_stop_uses_new_pagination( $stop_id );

																						if ( $stop_pagination ) {
																							$stop_pages = fieldpress_stop_pages( $stop_id, $stop_pagination );
																						} else {
																							$stop_pages = fieldpress_stop_pages( $stop_id );
																						}

																						$modules = Stop_Module::get_modules( $stop_id );
																						?>

																						<li class="<?php echo( $stop_post->post_status == 'publish' ? 'enabled_stop' : 'disabled_stop' ); ?>">

																							<label for="stop_<?php echo $stop_id; ?>">
																								<div class="tree-stop-left"><?php echo( $stop_post->post_status != 'publish' ? __( '[draft] ', 'cp' ) : '' ); ?><?php echo $stop_post->post_title; ?></div>

																								<div class="tree-stop-right">
																									<input type='checkbox' class="module_show" id='show-<?php echo $stop_id; ?>' data-id="<?php echo esc_attr( $stop_id ); ?>" name='meta_show_stop[<?php echo $stop_id; ?>]' <?php
																									if ( isset( $show_stop[ $stop_id ] ) ) {
																										echo ( $show_stop[ $stop_id ] == 'on' ) ? 'checked' : '';
																									} else {
																										echo ( 'on' == $field_stop_options ) ? 'checked' : '';
																										$section_dirty = true;
																									}
																									?> <?php echo( $stop_post->post_status == 'publish' ? 'enabled' : 'disabled' ); ?> />


																									<span><?php echo $stop_class->get_stop_time_estimation( $stop_id ); ?></span>
																								</div>
																							</label>
																							<input type="checkbox" id="stop_<?php echo $stop_id; ?>" class="hidden_checkbox"/>


																							<ol>
																								<?php
																								if ( $stop_pages == 0 ) {
																									?>
																									<li>
																										<label><?php _e( 'There are currently no pages to display', 'cp' ); ?></label>
																									</li>
																								<?php
																								} else {
																									?>
																									<li class="field_stop_page_li">
																										<?php
																										for ( $i = 1; $i <= $stop_pages; $i ++ ) {
																											$pages_num  = 1;
																											$page_title = $stop_class->get_stop_page_name( $i );
																											?>

																											<label for="page_<?php echo $stop_id . '_' . $i; ?>">
																												<div class="tree-page-left">
																													<?php echo( isset( $page_title ) && $page_title !== '' ? $page_title : __( 'Untitled Page', 'cp' ) ); ?>
																												</div>
																												<div class="tree-page-right">
																													<input type='checkbox' class="module_show" id='show-<?php echo $stop_id . '_' . $i; ?>' data-id="<?php echo esc_attr( $stop_id . '_' . $i ); ?>" name='meta_show_page[<?php echo $stop_id . '_' . $i; ?>]' <?php
																													if ( isset( $show_page[ $stop_id . '_' . $i ] ) ) {
																														echo ( $show_page[ $stop_id . '_' . $i ] == 'on' ) ? 'checked' : '';
																													} else {
																														echo ( 'on' == $field_stop_options ) ? 'checked' : '';
																														$section_dirty = true;
																													}
																													?> <?php echo( $stop_post->post_status == 'publish' ? 'enabled' : 'disabled' ); ?> />
																													<?php
																													$disabled = '';
																													if ( isset( $preview_stop[ $stop_id ] ) ) {
																														if ( $preview_stop[ $stop_id ] == 'on' ) {
																															$disabled = 'disabled';
																														} else {
																															$disabled = '';
																														}
																													}
																													?>


																													<span><?php echo $stop_class->get_stop_page_time_estimation( $stop_id, $i ); ?></span>
																												</div>
																											</label>

																											<input type="checkbox" id="page_<?php echo $stop_id . '_' . $i; ?>" class="hidden_checkbox"/>

																											<ol class="field_stop_elements_ol">
																												<?php
																												/*
																												  foreach ($modules as $mod) {
																												  $class_name = $mod->module_type;

																												  if (class_exists($class_name)) {
																												  $module = new $class_name();

																												  if ($module->name == 'page_break_module') {
																												  $pages_num++;
																												  } else {
																												  ?>
																												  <?php
																												  if ($pages_num == $i) {
																												  if ($module->name !== 'section_break_module') {
																												  ?>
																												  <li class="element">
																												  <div class="tree-element-left">
																												  <?php echo ($mod->post_title && $mod->post_title !== '' ? $mod->post_title : __('Untitled Element', 'cp')); ?>
																												  </div>

																												  <div class="tree-element-right">
																												  <input type='checkbox' class="module_show" id='show-<?php echo $mod->ID; ?>' name='meta_show_module[<?php echo $mod->ID; ?>]' <?php
																												  if (isset($show_module[$mod->ID])) {
																												  echo ( $show_module[$mod->ID] == 'on' ) ? 'checked' : '';
																												  }
																												  ?> />

																												  <input type='checkbox' class="module_preview" id='preview-<?php echo $mod->ID; ?>' name='meta_preview_module[<?php echo $mod->ID; ?>]' <?php
																												  if (isset($preview_module[$mod->ID])) {
																												  echo ( $preview_module[$mod->ID] == 'on' ) ? 'checked' : '';
																												  }
																												  ?> />

																												  <span><?php echo (isset($mod->time_estimation) && $mod->time_estimation !== '') ? $mod->time_estimation.' '.__('min', 'cp') : __('N/A', 'cp');?></span>
																												  </div>
																												  </li>
																												  <?php
																												  }
																												  }
																												  }
																												  }
																												  } */
																												?>

																											</ol>
																										<?php
																										}
																										?>
																									</li>
																								<?php } ?>

																							</ol>
																						</li>


																					<?php
																					}

																					if ( $section_dirty ) {
																						?>
																						<input type="hidden" name="section_dirty" value="true"/>
																					<?php
																					}
																					?>
																				</ol>
																			<?php
																			}
																			?>
																		</li>

																	</ol>
																</td>
															</tr>
															<?php
															/* $stops = $field->get_stops();

															  if (0 == count($stops)) {
															  ?>
															  <tr>
															  <th colspan="4"><?php _e('There are currently no Stops to Display', 'cp'); ?></th>
															  </tr>
															  <?php
															  } else { */
															/* foreach ($stops as $stop) {
															  ?>
															  <tr>
															  <th class="title" colspan="4"><?php echo $stop_post->post_title; ?></th>
															  </tr>
															  <?php
															  $module = new Stop_Module();
															  $modules = $module->order_modules(Stop_Module::get_modules($stop_id));

															  foreach ($modules as $module) {
															  if (!empty($module->post_title)) {
															  ?>
															  <tr>
															  <td>
															  <?php echo $module->post_title; ?>
															  <input type="hidden" name="module_element[<?php echo $module->ID; ?>]" value="<?php echo $module->ID; ?>" />
															  </td>
															  <td><input type='checkbox' id='show-<?php echo $module->ID; ?>' name='meta_show_module[<?php echo $module->ID; ?>]' <?php
															  if (isset($show_module[$module->ID])) {
															  echo ( $show_module[$module->ID] == 'on' ) ? 'checked' : '';
															  }
															  ?> /></td>
															  <td><input type='checkbox' id='preview-<?php echo $module->ID; ?>' name='meta_preview_module[<?php echo $module->ID; ?>]' <?php
															  if (isset($preview_module[$module->ID])) {
															  echo ( $preview_module[$module->ID] == 'on' ) ? 'checked' : '';
															  }
															  ?> /></td>

															  <td>10 min</td>
															  </tr>
															  <?php
															  } // if not empty post title
															  } // foreach ( $modules as $modul )
															  ?>
															  <?php
															  } */ // foreach ( $stops as $stop )
															//}
															?>

														</tbody>
													</table>
												</div>
											</div>

											<?php do_action( 'field_step_2_fields', $field_id ); ?>

											<div class="field-step-buttons">
												<input type="button" class="button button-stops prev" value="<?php _e( 'Previous', 'cp' ); ?>"/>
												<input type="button" class="button button-stops next" value="<?php _e( 'Next', 'cp' ); ?>"/>
												<input type="button" class="button button-stops update" value="<?php _e( 'Update', 'cp' ); ?>"/>
											</div>
										</div>
									</div>
									<!-- /Field Trip Description -->

									<!-- Instructors -->

									<div class="field-section step step-3 <?php echo 'step-3' == $field_setup_marker ? 'save-marker active' : ''; ?>">
										<div class='field-section-title'>
											<div class="status <?php echo empty( $field_setup_progress['step-3'] ) ? '' : $field_setup_progress['step-3']; ?> "></div>
											<h3><?php _e( 'Step 3 - Instructors', 'cp' ) ?></h3>
										</div>
										<div class='field-form'>
											<?php
											$set_status = $field_setup_progress['step-3'];
											?>
											<input type='hidden' name='meta_field_setup_progress[step-3]' class='field_setup_progress' value="<?php echo $set_status; ?>"/>

											<div class="wide narrow">
												<label>
													<?php _e( 'Field Trip Instructor(s)', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'Select one or more instructor to facilitate this field trip.', 'cp' ) );                 ?>
													<br/>
													<span><?php _e( 'Select one or more instructor to facilitate this field trip', 'cp' ); ?></span>
												</label>

												<?php if ( FieldPress_Capabilities::can_assign_field_instructor( $field_id ) ) { ?>
													<?php cp_instructors_avatars_array(); ?>

													<div class="clearfix"></div>
													<?php cp_instructors_drop_down( 'postform chosen-select-field field-instructors ' . $class_extra ); ?>

													<input class="button-primary" id="add-instructor-trigger" type="button" value="<?php _e( 'Assign', 'cp' ); ?>">
													<!-- <p><?php _e( 'NOTE: If you need to add an instructor that is not on the list, please finish creating your field and save it. To create a new instructor, you must go to Users to create a new user account which you can select in this list. Then come back to this field trip and you can then select the instructor.', 'cp' ); ?></p> -->

													<?php
													$data_nonce = wp_create_nonce( 'manage-instructors-' . get_current_user_id() );
													?>


													<input type='hidden' name='instructor-ajax-check' id="instructor-ajax-check" data-id="<?php echo $field_id; ?>" data-uid="<?php echo get_current_user_id(); ?>" data-nonce="<?php echo $data_nonce; ?>" value=""/>
												<?php
												} else {
													if ( cp_get_number_of_instructors() == 0 || cp_instructors_avatars( $field_id, false, true ) == 0 ) {//just to fill in emtpy space if none of the instructors has been assigned to the field trip and in the same time instructor can't assign instructors to a field
														_e( 'You do not have required permissions to assign instructors to a field.', 'cp' );
													}
												}
												?>

												<p><?php _e( 'Assigned Instructors:', 'cp' ); ?></p>

												<div class="instructors-info" id="instructors-info">
													<?php if ( 0 >= cp_instructors_avatars( $field_id, true, true ) ) : ?>
														<div class="instructor-avatar-holder empty">
															<span class="instructor-name"><?php _e( 'Please Assign Instructor', 'cp' ); ?></span>
														</div>
													<?php endif ?>

													<?php
													$can_manage_instructors = FieldPress_Capabilities::can_assign_field_instructor( $field_id );
													?>

													<?php cp_instructors_avatars( $field_id, $can_manage_instructors ); ?>
													<?php cp_instructors_pending( $field_id, $can_manage_instructors ); ?>
												</div>

												<div class="clearfix"></div>
												<?php if ( $can_manage_instructors || 0 == $field_id ) : ?>
													<hr/>
													<!-- INVITE INSTRUCTOR -->

													<label>
														<?php _e( 'Invite New Instructor', 'cp' ); ?>
														<?php // CP_Helper_Tooltip::tooltip( __( 'If the instructor can not be found in the list above, you will need to invite them via email.', 'cp' ) );                 ?>
														<br/>
														<span><?php _e( 'If the instructor can not be found in the list above, you will need to invite them via email.', 'cp' ); ?></span>
													</label>
													<div class="instructor-invite">
														<label for="invite_instructor_first_name"><?php _e( 'First Name', 'cp' ); ?></label>
														<input type="text" name="invite_instructor_first_name" placeholder="<?php _e( 'First Name', 'cp' ); ?>"/>
														<label for="invite_instructor_last_name"><?php _e( 'Last Name', 'cp' ); ?></label>
														<input type="text" name="invite_instructor_last_name" placeholder="<?php _e( 'Last Name', 'cp' ); ?>"/>
														<label for="invite_instructor_email"><?php _e( 'E-Mail', 'cp' ); ?></label>
														<input type="text" name="invite_instructor_email" placeholder="<?php _e( 'instructor@email.com', 'cp' ); ?>"/>

														<div class="submit-message">
															<input class="button-primary" name="invite_instructor_trigger" id="invite-instructor-trigger" type="button" value="<?php _e( 'Send Invite', 'cp' ); ?>">
														</div>
													</div>
												<?php endif; ?>


											</div>

											<?php do_action( 'field_step_3_fields', $field_id ); ?>

											<div class="field-step-buttons">
												<input type="button" class="button button-stops prev" value="<?php _e( 'Previous', 'cp' ); ?>"/>
												<input type="button" class="button button-stops next" value="<?php _e( 'Next', 'cp' ); ?>"/>
												<input type="button" class="button button-stops update" value="<?php _e( 'Update', 'cp' ); ?>"/>
											</div>
										</div>
									</div>
									<!-- /Instructors -->

									<!-- Field Trip Date and Time -->

									<div class="field-section step step-4 <?php echo 'step-4' == $field_setup_marker ? 'save-marker active' : ''; ?>">
										<div class='field-section-title'>
											<div class="status <?php echo empty( $field_setup_progress['step-4'] ) ? '' : $field_setup_progress['step-4']; ?> "></div>
											<h3><?php _e( 'Step 4 - Field Trip Date and Time', 'cp' ) ?></h3>
										</div>
										<div class='field-form'>
											<?php
											$set_status = $field_setup_progress['step-4'];
											?>
											<input type='hidden' name='meta_field_setup_progress[step-4]' class='field_setup_progress' value="<?php echo esc_attr( $set_status ); ?>"/>

											<div class="wide field-dates">
												<label>
													<?php _e( 'Field Trip Date and Time', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'This is the duration the field trip will be open to the students.', 'cp' ) );                 ?>
												</label>

												<div class="field-date-override">
													<label><input type="checkbox" name="meta_open_ended_field" id="open_ended_field" <?php echo ( $open_ended_field == 'on' ) ? 'checked' : ''; ?> /><?php _e( 'This field trip has no end date', 'cp' ); ?>
													</label>
												</div>

												<p><?php _e( 'This is the duration the field trip will be open to the students', 'cp' ); ?></p>

												<div class="date-range">
													<div class="start-date">
														<label for="meta_field_start_date" class="start-date-label required"><?php _e( 'Start Date', 'cp' ); ?></label>

														<div class="date">
															<input type="text" class="dateinput" name="meta_field_start_date" value="<?php echo esc_attr( $field_start_date ); ?>"/><i class="calendar"></i>
														</div>

                                                        <label for="meta_field_start_time" ><?php _e( 'Start Time', 'cp' ); ?></label>
														<div class="date">
                                                           <input type="text" type= "text" name="meta_field_start_time" id="field_start_time" value="<?php echo esc_attr( $field_start_time ); ?>" />
        									            </div>
												    </div>

											    <div class="end-date <?php echo ( $open_ended_field == 'on' ) ? 'disabled' : ''; ?>">
														<label for="meta_field_end_date" class="end-date-label <?php echo ( $open_ended_field == 'on' ) ? '' : 'required'; ?>"><?php _e( 'End Date', 'cp' ); ?></label>

														<div class="date">
															<input type="text" class="dateinput" name="meta_field_end_date" value="<?php echo esc_attr( $field_end_date ); ?>" <?php echo ( $open_ended_field == 'on' ) ? 'disabled="disabled"' : ''; ?> />
														</div>

                                                        <label for="meta_field_end_time" ><?php _e( ' End Time', 'cp' ); ?></label>
														<div class="date">
                                                            <input type="text" class="timeinput" name="meta_field_end_time" value="<?php echo esc_attr( $field_end_time ); ?>" <?php echo ( $open_ended_field == 'on' ) ? 'disabled="disabled"' : ''; ?> />
                                                        </div>
													</div>
												</div>
												<div class="clearfix"></div>
											</div>

											<div class="wide enrollment-dates">
												<label>
													<?php _e( 'Enrollment Dates', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'These are the dates that students can enroll.', 'cp' ) );                 ?>
												</label>

												<div class="enrollment-date-override">
													<label><input type="checkbox" name="meta_open_ended_enrollment" id="open_ended_enrollment" <?php echo ( $open_ended_enrollment == 'on' ) ? 'checked' : ''; ?> /><?php _e( 'Users can enroll at any time', 'cp' ); ?>
													</label>
												</div>

												<p><?php _e( 'These are the dates that students can enroll', 'cp' ); ?></p>

												<div class="date-range">
													<div class="start-date <?php echo ( $open_ended_enrollment == 'on' ) ? 'disabled' : ''; ?>">
														<label for="meta_enrollment_start_date" class="start-date-label <?php echo ( $open_ended_enrollment == 'on' ) ? '' : 'required'; ?>"><?php _e( 'Start Date', 'cp' ); ?></label>

														<div class="date">
															<input type="text" class="dateinput" name="meta_enrollment_start_date" value="<?php echo esc_attr( $enrollment_start_date ); ?>" <?php echo ( $open_ended_enrollment == 'on' ) ? 'disabled="disabled"' : ''; ?> />
														</div>
													</div>
													<div class="end-date <?php echo ( $open_ended_enrollment == 'on' ) ? 'disabled' : ''; ?>">
														<label for="meta_enrollment_end_date" class="end-date-label <?php echo ( $open_ended_enrollment == 'on' ) ? '' : 'required'; ?>"><?php _e( 'End Date', 'cp' ); ?></label>

														<div class="date">
															<input type="text" class="dateinput" name="meta_enrollment_end_date" value="<?php echo esc_attr( $enrollment_end_date ); ?>" <?php echo ( $open_ended_enrollment == 'on' ) ? 'disabled="disabled"' : ''; ?> />
														</div>
													</div>
												</div>

												<div class="clearfix"></div>
											</div>
											<!--/all-field-dates-->

											<?php do_action( 'field_step_4_fields', $field_id ); ?>

											<div class="field-step-buttons">
												<input type="button" class="button button-stops prev" value="<?php _e( 'Previous', 'cp' ); ?>"/>
												<input type="button" class="button button-stops next" value="<?php _e( 'Next', 'cp' ); ?>"/>
												<input type="button" class="button button-stops update" value="<?php _e( 'Update', 'cp' ); ?>"/>
											</div>
										</div>
									</div>
									<!-- /Field Trip Date and Time -->

									<!-- Classes, Discussions & Workbook -->

									<div class="field-section step step-5 <?php echo 'step-5' == $field_setup_marker ? 'save-marker active' : ''; ?>">
										<div class='field-section-title'>
											<div class="status <?php echo empty( $field_setup_progress['step-5'] ) ? '' : $field_setup_progress['step-5']; ?> "></div>
											<h3><?php _e( 'Step 5 - Groups & Discussion', 'cp' ) ?></h3>
										</div>
										<div class='field-form'>
											<?php
											$set_status = $field_setup_progress['step-5'];
											?>
											<input type='hidden' name='meta_field_setup_progress[step-5]' class='field_setup_progress' value="<?php echo $set_status; ?>"/>

											<div class="wide narrow">
												<div>
													<label for='meta_class-size'>
														<input type="checkbox" name="meta_limit_class_size" id="limit_class_size" <?php echo ( $limit_class_size == 'on' ) ? 'checked' : ''; ?> />
														<span><?php _e( 'Limit field trip group size', 'cp' ); ?></span>
														<?php // CP_Helper_Tooltip::tooltip( __( 'Use this setting to set a limit for all classes. Uncheck for unlimited field trip group size( s ).', 'cp' ) );                    ?>
														<br/>
														<span><?php _e( 'Use this setting to set a limit for all classes. Uncheck for unlimited field trip group size( s ).', 'cp' ); ?></span>
													</label>
													<input class='spinners <?php echo ( $limit_class_size == 'on' ) ? '' : 'disabled'; ?> class_size' name='meta_class_size' id='class_size' value='<?php echo esc_attr( stripslashes( ( is_numeric( $class_size ) ? $class_size : 0 ) ) ); ?>' <?php echo ( $limit_class_size == 'on' ) ? '' : 'disabled="disabled"'; ?> />
													<span class="limit-class-size-required <?php echo ( $limit_class_size == 'on' ) ? 'required' : ''; ?>"></span>
												</div>
												<hr/>

												<label for='meta_allow_field_discussion'>
													<input type="checkbox" name="meta_allow_field_discussion" id="allow_field_discussion" <?php echo ( $allow_field_discussion == 'on' ) ? 'checked' : ''; ?> />
													<span><?php _e( 'Allow Field Trip Discussion', 'cp' ); ?></span>
													<?php // CP_Helper_Tooltip::tooltip( __( 'If checked, students can post questions and receive answers at a field trip level. A \'Discusssion\' menu item is added for the student to see ALL discussions occuring from all class members and instructors.', 'cp' ) );                    ?>
													<br/>
													<span><?php _e( 'If checked, students can post questions and receive answers at a field trip level. A \'Discusssion\' menu item is added for the student to see ALL discussions occuring from all class members and instructors.', 'cp' ); ?></span>
												</label>

												<label for='meta_allow_workbook_page'>
													<input type="checkbox" name="meta_allow_workbook_page" id="allow_workbook_page" <?php echo ( $allow_workbook_page == 'on' ) ? 'checked' : ''; ?> />
													<span><?php _e( 'Show student Workbook', 'cp' ); ?></span>
													<?php // CP_Helper_Tooltip::tooltip( __( 'If checked, students can see their field trip progress and grades for quiz and surveys.', 'cp' ) );                    ?>
													<br/>
													<span><?php _e( 'If checked, students can see their field trip progress and grades for quiz and surveys.', 'cp' ); ?></span>
												</label>

											</div>

											<?php do_action( 'field_step_5_fields', $field_id ); ?>

											<div class="field-step-buttons">
												<input type="button" class="button button-stops prev" value="<?php _e( 'Previous', 'cp' ); ?>"/>
												<input type="button" class="button button-stops next" value="<?php _e( 'Next', 'cp' ); ?>"/>
												<input type="button" class="button button-stops update" value="<?php _e( 'Update', 'cp' ); ?>"/>
											</div>
										</div>
									</div>
									<!-- /Classes, Discussions & Workbook -->

									<!-- Enrollment & Field Trip Cost -->

									<div class="field-section step step-6 <?php echo 'step-6' == $field_setup_marker ? 'save-marker active' : ''; ?>">
										<div class='field-section-title'>
											<?php
											$step_6_status = empty( $field_setup_progress['step-6'] ) ? '' : $field_setup_progress['step-6'];
											$step_6_status = ! $gateways && ( isset( $paid_field ) && $paid_field == 'on' ) ? 'attention' : $step_6_status;
											?>
											<div class="status <?php echo $step_6_status; ?> "></div>
											<?php
											$section_title = __( 'Step 6 - Enrollment & Field Trip Cost', 'cp' );
											if ( ! $offer_paid ) {
												$section_title = __( 'Step 6 - Enrollment', 'cp' );
											}
											?>
											<h3><?php echo esc_html( $section_title ); ?></h3>
										</div>
										<div class='field-form'>
											<?php
											$set_status = $field_setup_progress['step-6'];
											?>
											<input type='hidden' name='meta_field_setup_progress[step-6]' class='field_setup_progress' value="<?php echo $set_status; ?>"/>

											<div class="narrow">
												<label for='meta_enroll_type'>
													<?php _e( 'Who can Register in this field trip', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'Select the limitations on accessing and enrolling in this field trip.', 'cp' ) );                  ?>
													<br/>
													<span><?php _e( 'Select the limitations on accessing and enrolling in this field trip.', 'cp' ); ?></span>
												</label>

												<select class="wide" name="meta_enroll_type" id="enroll_type">
													<?php
													$enrollment_types = apply_filters( 'fieldpress_field_enrollment_types', array(
														'manually' => __( 'Manually added only', 'cp' ),
													) );
													?>
													<?php foreach ( $enrollment_types as $key => $type_text ) { ?>
														<option value="<?php echo esc_attr( $key ); ?>" <?php echo( $enroll_type == $key ? 'selected=""' : '' ) ?>><?php echo esc_html( $type_text ) ?></option>
													<?php } ?>
												</select>

												<?php //if ( !cp_user_can_register() && current_user_can( 'manage_options' ) ) {   ?>
												<!--	<span class="field_settings_enrollment_message">-->
												<?php //_e( 'In order to allow field trip enrollment (other than Manually) you have to activate "Anyone can register" from the WordPress settings.', 'cp' );     ?><!--</span>-->
												<?php //} ?>
											</div>

											<div class='wide' id='manually_added_holder'>
												<p><?php _e( 'NOTE: If you need to manually add a student, students must be registered on your site first. To do this for a student, you can do this yourself by going to Users in WordPress where you can add the students manually. You can then select them from this list.', 'cp' ); ?></p>
											</div>

											<div class="wide" id="enroll_type_prerequisite_holder" <?php echo( $enroll_type <> 'prerequisite' ? 'style="display:none"' : '' ) ?>>
												<label for='meta_enroll_type'>
													<?php _e( 'Prerequisite Field Trip', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'Students will need to fulfil prerequisite in order to enroll.', 'cp' ) );                  ?>
												</label>

												<p><?php _e( 'Students will need to complete the following prerequisite field trip in order to enroll.', 'cp' ); ?></p>
												<select name="meta_prerequisite" class="chosen-select">
													<?php
													$args = array(
														'post_type'      => 'field',
														'post_status'    => 'any',
														'posts_per_page' => - 1,
														'exclude'        => $field_id
													);

													$pre_fields = get_posts( $args );

													foreach ( $pre_fields as $pre_field ) {

														$pre_field_obj    = new Field( $pre_field->ID );
														$pre_field_object = $pre_field_obj->get_field();
														?>
														<option value="<?php echo $pre_field->ID; ?>" <?php selected( $prerequisite, $pre_field->ID, true ); ?>><?php echo $pre_field->post_title; ?></option>
													<?php
													}
													?>
												</select>

											</div>

											<div class="narrow" id="enroll_type_holder" <?php echo( $enroll_type <> 'passcode' ? 'style="display:none"' : '' ) ?>>
												<label for='meta_enroll_type'>
													<?php _e( 'Pass Code', 'cp' ); ?>
													<?php // CP_Helper_Tooltip::tooltip( __( 'Students will need to enter this pass code in order to enroll.', 'cp' ) );                  ?>
												</label>

												<p><?php _e( 'Students will need to enter this pass code in order to enroll.', 'cp' ); ?></p>

												<input type="text" name="meta_passcode" value="<?php echo esc_attr( stripslashes( $passcode ) ); ?>"/>

											</div>

										<?php
											// Check to see if we're offering Paid Field Trips.
											//***********************// Temporarily disable the function of payment gateway. To-do: need to clean up in the future.
											if ( ! $offer_paid ) {
												if ( cp_use_woo() ) {
													//START WOO
													?>
													<div class="narrow product">

														<label>
															<?php _e( 'Cost to participate in this field trip', 'cp' ); ?>
														</label>

														<div class="field-paid" id="marketpressprompt">
															<input type="checkbox" name="meta_paid_field" <?php echo ( isset( $paid_field ) && $paid_field == 'on' ) ? 'checked' : ''; ?> id="paid_field"></input>
															<span><?php _e( 'This is a Paid Field Trip', 'cp' ); ?></span>
														</div>

														<div>
															<?php
															$woo_product_id = CP_WooCommerce_Integration::woo_product_id( $field_id );

															$product_exists = 0 != $woo_product_id ? true : false;

															$paid_field = ! isset( $paid_field ) || $paid_field == 'off' ? 'off' : 'on';
															$paid_field = ! $product_exists ? 'off' : $paid_field;

															if ( isset( $field_id ) && $field_id !== 0 ) {
																$woo_product_details = get_post_custom( $woo_product_id ); //$field_id
															}

															if ( isset( $woo_product ) && $woo_product !== '' ) {
																$woo_product_sku = get_post_meta( $woo_product, '_sku', true );
															} else {
																$woo_product_sku = '';
															}

															$input_state = 'off' == $paid_field ? 'disabled="disabled"' : '';
															?>

															<input type="hidden" name="meta_mp_product_id" id="mp_product_id" value="<?php echo esc_attr( isset( $woo_product_id ) ? $woo_product_id : '' ); ?>"/>

															<div class="field-paid-field-details <?php echo ( $paid_field != 'on' ) ? 'hidden' : ''; ?>">
																<div class="field-sku">
																	<p>
																		<input type="checkbox" name="meta_auto_sku" <?php echo ( isset( $auto_sku ) && $auto_sku == 'on' ) ? 'checked' : ''; ?> <?php echo $input_state; ?>  />
																		<?php _e( 'Automatically generate Stock Keeping Stop (SKU)', 'cp' ); ?>
																	</p>
																	<input type="text" name="mp_sku" id="mp_sku" placeholder="CP-000001" value="<?php
																	echo esc_attr( isset( $woo_product_sku ) ? $woo_product_sku : '' );
																	?>" <?php echo $input_state; ?> />
																</div>

																<div class="field-price">
																	<span class="price-label <?php echo $paid_field == 'on' ? 'required' : ''; ?>"><?php _e( 'Price', 'cp' ); ?></span>
																	<input type="text" name="mp_price" id="mp_price" value="<?php echo isset( $woo_product_details['_regular_price'][0] ) ? esc_attr( $woo_product_details['_regular_price'][0] ) : ''; ?>" <?php echo $input_state; ?>  />
																</div>

																<div class="clearfix"></div>

																<div class="field-sale-price">
																	<?php
																	$woo_is_sale = isset( $woo_product_details["_sale_price"][0] ) ? ( is_numeric( $woo_product_details["_sale_price"][0] ) ? true : false ) : false;
																	?>
																	<p>
																		<input type="checkbox" id="mp_is_sale" name="mp_is_sale" value="<?php echo esc_attr( $woo_is_sale ); ?>" <?php checked( $woo_is_sale, '1', true ); ?><?php echo $input_state; ?>  />
																		<?php _e( 'Enabled Sale Price', 'cp' ); ?></p>
																	<span class="price-label <?php isset( $woo_product_details ) && ! empty( $woo_product_details["_sale_price"] ) && checked( $woo_product_details["_sale_price"][0], '1' ) ? 'required' : ''; ?>"><?php _e( 'Sale Price', 'cp' ); ?></span>
																	<input type="text" name="mp_sale_price" id="mp_sale_price" value="<?php echo( ! empty( $woo_product_details['_sale_price'] ) ? esc_attr( $woo_product_details["_sale_price"][0] ) : 0 ); ?>" <?php echo $input_state; ?>  />
																</div>

																<div class="clearfix"></div>

																<div class="field-enable-gateways gateway-active"></div>

															</div>
														</div>

													</div>
													<?php
													//END WOO
												} else {
													?>
													<hr/>
													<?php // START ////////////////////////////////////////////////////////////////////////////////////////////////////////////
													?>

													<div class="narrow product">
														<!-- MarketPress not Active -->
														<?php
														if ( FieldPress_MarketPress_Integration::is_active() || ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) ) {
															?>
															<label>
																<?php _e( 'Cost to participate in this field trip', 'cp' ); ?>
															</label>

															<div class="field-paid" id="marketpressprompt">
																<input type="checkbox" name="meta_paid_field" <?php echo ( isset( $paid_field ) && $paid_field == 'on' ) ? 'checked' : ''; ?> id="paid_field"></input>
																<span><?php _e( 'This is a Paid Field Trip', 'cp' ); ?></span>
															</div>

														<?php
														}
														if ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) {
															?>
															<div class="cp-markertpress-not-active <?php echo FieldPress_MarketPress_Integration::is_active() ? 'hidden' : ''; ?>">
																<div id="marketpressprompt-box">
																	<label>
																		<?php _e( 'Sell your field trips online with MarketPress.', 'cp' ); ?>
																	</label>

																	<?php
																	if ( ! FieldPress_Capabilities::is_pro() ) {
																		echo sprintf( __(
																			'To start selling your field trip, you will need to activate the MarketPress Lite plugin: <br /> %s<br /><br />' .
																			'If you require other payment gateways, you will need to upgrade to %s.', 'cp' ), '<a target="_blank" href="' . admin_url( 'admin.php?page=' . $this->screen_base . '_settings' . '&tab=cp-marketpress' ) . '">' . __( 'Begin Activating MarketPress Lite', 'cp' ) . '</a>', '<a target="_blank" href="http://greenconsensus.com/sandbox/wordpress">' . __( 'MarketPress', 'cp' ) . '</a>' );
																	} else {
																		echo sprintf( __( 'The full version of MarketPress has been bundled with %s.<br />' .
																		                  'To start selling your field trip, you will need to activate MarketPress: <br /> %s<br /><br />', 'cp' ), 'FieldPress', '<a target="_blank" href="' . admin_url( 'admin.php?page=' . $this->screen_base . '_settings' . '&tab=cp-marketpress' ) . '">' . __( 'Begin Activating MarketPress', 'cp' ) . '</a>' );
																	}
																	?>
																</div>
															</div>  <!-- cp-marketpress-not-active -->
														<?php
														}
														if (current_user_can( 'manage_options' ) || ( ! current_user_can( 'manage_options' ) && $gateways )) {

															echo FieldPress_MarketPress_Integration::product_settings( '', $field_id );

														} ?><!-- cp-markertpress-is-active -->
														<!--_e('Please ask administrator to enable at least one payment gateway.', 'cp');-->
													</div>

													<?php
													// End check for Campus.
												}
											}
											?>
											<?php // END ///////////////////////////////                     ?>

											<?php do_action( 'field_step_6_fields', $field_id ); ?>

											<div class="field-step-buttons">
												<input type="button" class="button button-stops prev" value="<?php _e( 'Previous', 'cp' ); ?>"/>
												<input type="button" class="button button-stops update" value="<?php _e( 'Update', 'cp' ); ?>"/>
												<input type="button" class="button button-stops done" value="<?php _e( 'Done', 'cp' ); ?>"/>
											</div>
										</div>
									</div>
									<!-- /Enrollment & Field Trip Cost -->

									<!-- OLD GRADEBOOK INTEGRATION
																																									<div class="full border-divider">
																									<label><?php _e( 'Show Grades Page for Students', 'cp' ); ?>
																											<a class="help-icon" href="javascript:;"></a>
																											<div class="tooltip">
																													<div class="tooltip-before"></div>
																													<div class="tooltip-button">&times;</div>
																													<div class="tooltip-content">
									<?php _e( 'If checked, students can see their field trip performance and grades by stops.', 'cp' ) ?>
																													</div>
																											</div>

																											<input type="checkbox" name="meta_allow_field_grades_page" id="allow_field_grades_page" <?php echo ( $allow_field_grades_page == 'on' ) ? 'checked' : ''; ?> />
																									</label>
																							</div>
									// OLD GRADEBOOK INTEGRATION -->


								<?php } else {
									?>
									<div class="limited_fields_message">
										<?php
										printf( __( 'While %s is suitable for offering a few field trips, you may have bigger goals for your site. %s takes the features you love from %s and unlocks the ability to create an unlimited number of field trips. And get 12 payment gateways making it even easier to accept payments for your premium content.' ), $this->name, '<a href="http://premium.wpmudev.org/project/fieldpress-pro/">' . __( 'FieldPress', 'cp' ) . '</a>', $this->name );
										//printf(__('You can create only %s field trips with Standard version of %s. Check out the %s.'), $wp_field_search->fields_per_page, $this->name, '<a href="http://premium.wpmudev.org/project/fieldpress-pro/">' . __('PRO version') . '</a>');
										?>
									</div>
								<?php
								}
								?>
							</div>
						</div>

						<!-- /Field Trip Details -->

						<!--
						<div class="buttons field-add-stops-button">
						<?php
						if ( $field_id !== 0 ) {
							?>
																																																				<a href="<?php echo admin_url( 'admin.php?page=' . (int) $_GET['page'] . '&tab=stops&field_id=' . (int) $_GET['field_id'] ); ?>" class="button-secondary"><?php _e( 'Add Stops &raquo;', 'cp' ); ?></a>
						<?php } ?>
						</div>
						-->

					</div>
				<?php } ?>
			</div>

		</div>
		<!-- field-liquid-left -->

	</form>

</div> <!-- wrap -->