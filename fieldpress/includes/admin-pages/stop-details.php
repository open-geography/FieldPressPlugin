<?php
global $page, $user_id, $cp_admin_notice;
global $fieldpress_modules, $fieldpress_modules_labels, $fieldpress_modules_descriptions, $fieldpress_modules_ordered, $save_elements;

$field_id	 = '';
$stop_id	 = '';

if ( isset( $_GET[ 'field_id' ] ) && is_numeric( $_GET[ 'field_id' ] ) ) {
	$field_id	 = (int) $_GET[ 'field_id' ];
	$field		 = new Field( $field_id );
}

if ( !empty( $field_id ) && !FieldPress_Capabilities::can_view_field_stops( $_GET[ 'field_id' ] ) ) {
	die( __( 'You do not have required permissions to access this page.', 'cp' ) );
}

if ( !isset( $_POST[ 'force_current_stop_completion' ] ) ) {
	$_POST[ 'force_current_stop_completion' ] = 'off';
}
if ( !isset( $_POST[ 'force_current_stop_successful_completion' ] ) ) {
	$_POST[ 'force_current_stop_successful_completion' ] = 'off';
}

Stop_Module::check_for_modules_to_delete();

if ( isset( $_GET[ 'stop_id' ] ) ) {
	$stop_id									 = (int) $_GET[ 'stop_id' ];
	$stop										 = new Stop( $stop_id );
	$stop_details								 = $stop->get_stop();
	$force_current_stop_completion				 = $stop->details->force_current_stop_completion;
	$force_current_stop_successful_completion	 = $stop->details->force_current_stop_successful_completion;
	$stop_pagination							 = cp_stop_uses_new_pagination( (int) $_GET[ 'stop_id' ] );
} else {
	$stop										 = new Stop();
	$stop_id									 = 0;
	$force_current_stop_completion				 = 'off';
	$force_current_stop_successful_completion	 = 'off';
	$stop_pagination							 = false;
}

if ( $stop_id == 0 ) {
	$stop_id = $stop->create_auto_draft( $field_id ); //create auto draft and get stop id
	//wp_redirect(admin_url('admin.php?page=' . $page . '&tab=stops&field_id=' . $field_id . '&action=edit&stop_id=' . $stop_id));
}

if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'update_stop' ) {

	if ( wp_verify_nonce( $_REQUEST[ '_wpnonce' ], 'stop_details_overview_' . $user_id ) ) {

		if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_create_field_stop_cap' ) || current_user_can( 'fieldpress_update_field_stop_cap' ) || current_user_can( 'fieldpress_update_my_field_stop_cap' ) || current_user_can( 'fieldpress_update_all_fields_stop_cap' ) ) {
			$new_post_id = $stop->update_stop( isset( $_POST[ 'stop_id' ] ) ? $_POST[ 'stop_id' ] : 0  );
		}

		if ( isset( $_POST[ 'stop_state' ] ) ) {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_change_field_stop_status_cap' ) || current_user_can( 'fieldpress_change_my_field_stop_status_cap' ) || current_user_can( 'fieldpress_change_all_fields_stop_status_cap' ) ) {
				$stop = new Stop( $new_post_id );
				$stop->change_status( $_POST[ 'stop_state' ] );
			}
		}

		if ( $new_post_id !== 0 ) {
			//ob_start();
			// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }

			/**
			 * @todo: Work out what needs to happen before the redirect so that we can properly exit the script.
			 */
			if ( isset( $_GET[ 'ms' ] ) ) {
				wp_redirect( admin_url( 'admin.php?page=' . $page . '&tab=stops&field_id=' . $field_id . '&action=edit&stop_id=' . $new_post_id . '&ms=' . $_GET[ 'ms' ] . '&active_element=' . $active_element . (isset( $preview_redirect_url ) && $preview_redirect_url !== '' ? '&preview_redirect_url=' . $preview_redirect_url : '' ) . '&stop_page_num=' . (isset( $stop_page_num ) ? $stop_page_num : 1) . '#stop-page-' . (isset( $stop_page_num ) ? $stop_page_num : 1) ) );
				//exit;  // exiting the script here breaks page elements
			} else {
				wp_redirect( admin_url( 'admin.php?page=' . $page . '&tab=stops&field_id=' . $field_id . '&action=edit&stop_id=' . $new_post_id ) );
				//exit; // exiting the script here breaks page elements
			}
		} else {
			//an error occured
		}

		/* }else{
		  die( __( 'You don\'t have right permissions for the requested action', 'cp' ) );
		  } */
	}
}

if ( isset( $_GET[ 'preview_redirect_url' ] ) && $_GET[ 'preview_redirect_url' ] !== '' ) {
	wp_redirect( trailingslashit( get_permalink( $stop_id ) ) . 'page/' . (isset( $stop_page_num ) ? $stop_page_num : 1) );
	exit;
}

if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'edit' && isset( $_GET[ 'new_status' ] ) && isset( $_GET[ 'stop_id' ] ) && is_numeric( $_GET[ 'stop_id' ] ) ) {
	$stop		 = new Stop( $_GET[ 'stop_id' ] );
	$stop_object = $stop->get_stop();
	if ( FieldPress_Capabilities::can_change_field_stop_status( $field_id, $stop_id ) ) {
		$stop->change_status( $_GET[ 'new_status' ] );
	}
}

// cp_write_log(' preview redir: ' . $_POST['preview_redirect'] );

$preview_redirect	 = isset( $_REQUEST[ 'preview_redirect' ] ) ? $_REQUEST[ 'preview_redirect' ] : 'no';
?>
<div class='wrap mp-wrap nofieldsub stop-details cp-wrap' id='cp-stop-details-holder'>

    <div id="undefined-sticky-wrapper" class="sticky-wrapper">
        <div class="sticky-slider visible-small visible-extra-small"><i class="fa fa-chevron-circle-right"></i></div>
        <ul id="sortable-stops" class="mp-tabs" style="">
			<?php
			// $stops = $field->get_stops();
			// $field_id = isset( $field ) && isset( $field->details ) && ! empty( $field->details->ID ) ? $field->details->ID : 0;
			$stops				 = Stop::get_stops_from_field( $field_id, 'any', false );
			$stops				 = !empty( $stops ) ? $stops : array();
			?>
            <input type="hidden" name="stop_count" value="<?php echo $stops ? count( $stops ) : 0; ?>" />
			<?php
			$list_order			 = 1;

			foreach ( $stops as $stop ) {

				$stop_object = new Stop( $stop['post']->ID );
				$stop_object = $stop_object->get_stop();
				?>
				<li class="mp-tab <?php echo ( isset( $_GET[ 'stop_id' ] ) && $stop['post']->ID == $_GET[ 'stop_id' ] ? 'active' : '' ); ?>">
					<a class="mp-tab-link" href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&stop_id=' . $stop_object->ID . '&action=edit' ); ?>"><?php echo $stop_object->post_title; ?></a>
					<i class="fa fa-arrows-v cp-move-icon"></i>
					<span class="stop-state-circle <?php echo (isset( $stop_object->post_status ) && $stop_object->post_status == 'publish' ? 'active' : ''); ?>"></span>

					<input type="hidden" class="stop_order" value="<?php echo $list_order; ?>" name="stop_order_<?php echo $stop_object->ID; ?>" />
					<input type="hidden" name="stop_id" class="stop_id" value="<?php echo $stop_object->ID; ?>" />

				</li>
				<?php
				$list_order++;
			}
			?>
			<?php if ( FieldPress_Capabilities::can_create_field_stop( $field_id ) ) { ?>
				<li class="mp-tab <?php echo (!isset( $_GET[ 'stop_id' ] ) ? 'active' : '' ); ?> static">
					<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&action=add_new_stop' ); ?>" class="<?php echo (!isset( $_GET[ 'stop_id' ] ) ? 'mp-tab-link' : 'button-secondary' ); ?>"><?php _e( 'Add new Stop', 'cp' ); ?></a>
				</li>
			<?php } ?>
        </ul>

		<?php if ( FieldPress_Capabilities::can_create_field_stop( $field_id ) ) { ?>
			<!--<div class="mp-tabs">
				<div class="mp-tab <?php echo (!isset( $_GET[ 'stop_id' ] ) ? 'active' : '' ); ?>">
					<a href="?page=field_details&tab=stops&field_id=<?php echo $field_id; ?>&action=add_new_stop" class="<?php echo (!isset( $_GET[ 'stop_id' ] ) ? 'mp-tab-link' : 'button-secondary' ); ?>"><?php _e( 'Add new Stop', 'cp' ); ?></a>
				</div>
			</div>-->
		<?php } ?>

    </div>
    <div class='mp-settings'><!--field-liquid-left-->
        <form action="<?php echo esc_attr( admin_url( 'admin.php?page=' . $page . '&tab=stops&field_id=' . $field_id . '&action=add_new_stop' . ( ( $stop_id !== 0 ) ? '&ms=uu' : '&ms=ua' ) . (isset( $preview_redirect_url ) && $preview_redirect_url !== '' ? '&preview_redirect_url=' . $preview_redirect_url : '' ) ) ); ?>" name="stop-add" id="stop-add" class="stop-add" method="post">

			<?php wp_nonce_field( 'stop_details_overview_' . $user_id ); ?>
            <input type="hidden" name="stop_state" id="stop_state" value="<?php echo esc_attr( (isset( $stop_id ) && ($stop_id > 0) ? isset( $stop_object->post_status ) ? $stop_object->post_status : 'live'  : 'live' ) ); ?>" />

            <input type="hidden" name="field_id" value="<?php echo esc_attr( $field_id ); ?>" />
            <input type="hidden" name="stop_id" id="stop_id" value="<?php echo esc_attr( $stop_id ); ?>" />
            <input type="hidden" name="stop_page_num" id="stop_page_num" value="1" />
			<input type="hidden" name="stop_pagination" class="stop_pagination" value="<?php echo $stop_pagination; ?>" />
            <input type="hidden" name="action" value="update_stop" />
            <input type="hidden" name="active_element" id="active_element" value="<?php echo (isset( $_GET[ 'active_element' ] ) ? (int) $_GET[ 'active_element' ] : 1); ?>" />

			<?php
			$stop		 = new Stop( $stop_id );
			$stop_object = $stop->get_stop();
			$stop_id	 = (isset( $stop_object->ID ) && $stop_object->ID !== '') ? $stop_object->ID : '';

			$can_publish		 = FieldPress_Capabilities::can_change_field_stop_status( $field_id, $stop_id );
			$data_nonce			 = wp_create_nonce( 'toggle-' . $stop_id );
			?>

            <div class='section static'>
                <div class='stop-detail-settings'>
                    <h3><i class="fa fa-cog"></i> <?php _e( 'Stop Settings', 'cp' ); ?>
                        <div class="stop-state">
							<?php
							$control_position	 = 'off';
							if ( $stop_id > 0 && $stop_object && 'publish' == $stop_object->post_status ) {
								$control_position = 'on';
							}
							?>
                            <div class="stop_state_id" data-id="<?php echo $stop_id; ?>" data-nonce="<?php echo $data_nonce; ?>"></div>
                            <span class="draft <?php echo 'off' == $control_position ? 'on' : 'off'; ?>"><?php _e( 'Draft', 'cp' ); ?></span>
                            <div class="control <?php echo $can_publish ? '' : 'disabled'; ?> <?php echo $control_position; ?>">
                                <div class="toggle"></div>
                            </div>
                            <span class="live <?php echo 'on' == $control_position ? 'on' : 'off'; ?>"><?php _e( 'Live', 'cp' ); ?></span>
                        </div>
                    </h3>

                    <div class='mp-settings-label'><label for='stop_name'><?php _e( 'Stop Title', 'cp' ); ?></label></div>
                    <div class='mp-settings-field'>
                        <input class='wide' type='text' name='stop_name' id='stop_name' value='<?php echo esc_attr( stripslashes( isset( $stop_details->post_title ) ? $stop_details->post_title : ''  ) ); ?>' />
                    </div>
                    <div class='mp-settings-label'><label for='stop_availability'><?php _e( 'Stop Availability', 'cp' ); ?></label></div>
                    <div class='mp-settings-field'>
                        <input type="text" class="dateinput" name="stop_availability" value="<?php echo esc_attr( stripslashes( isset( $stop_details->stop_availability ) ? $stop_details->stop_availability : ( date( 'Y-m-d', current_time( 'timestamp', 0 ) ) )  ) ); ?>" />
          <!--              <div class="force_stop_completion">
                            <input type="checkbox" name="force_current_stop_completion" id="force_current_stop_completion" value="on" <?php echo ( $force_current_stop_completion == 'on' ) ? 'checked' : ''; ?> /> <?php _e( 'User needs to <strong><em>answer</em></strong> all mandatory assessments and view all pages in order to access the next stop', 'cp' ); ?>
                        </div>  -->
                        <div class="force_stop_successful_completion">
							<input type="checkbox" name="force_current_stop_successful_completion" id="force_current_stop_successful_completion" value="on" <?php echo ( $force_current_stop_successful_completion == 'on' ) ? 'checked' : ''; ?> /> <?php _e( 'User also needs to <strong><em>pass</em></strong> all mandatory assessments', 'cp' ); ?>
						</div>
                        <div class="refresh_stop_completion_progress">
                            <input type="checkbox" name="refresh_stop_completion_progress" id="refresh_stop_completion_progress" value="on" /> <?php _e( 'Force stop completion refresh.', 'cp' ); ?>
                        </div>
                    </div>
                </div>
                <div class="stop-control-buttons">

					<?php
					if ( $stop_id == 0 && FieldPress_Capabilities::can_create_field_stop( $field_id ) ) {//do not show anything
						?>
						<input type="hidden" name="preview_redirect" value="<?php echo $preview_redirect; ?>" />
						<input type="submit" name="submit-stop" class="button button-stops save-stop-button" value="<?php _e( 'Save', 'cp' ); ?>">
						<!--<input type="submit" name="submit-stop-publish" class="button button-stops button-publish" value="<?php _e( 'Publish', 'cp' ); ?>">-->

					<?php } ?>

					<?php
					if ( $stop_id != 0 && FieldPress_Capabilities::can_update_field_stop( $field_id, $stop_id ) ) {//do not show anything
						?>
						<input type="hidden" name="preview_redirect" value="<?php echo $preview_redirect; ?>" />
						<input type="submit" name="submit-stop" class="button button-stops save-stop-button" value="<?php echo ( $stop_object->post_status == 'unpublished' ) ? __( 'Save', 'cp' ) : __( 'Save', 'cp' ); ?>">
					<?php } ?>

					<?php
					if ( FieldPress_Capabilities::can_update_field_stop( $field_id, $stop_id ) ) {//do not show anything if user can't update Field Trip Stop
						?>
						<a class="button button-preview" href="<?php echo get_permalink( $stop_id ); ?>" data-href="<?php echo get_permalink( $stop_id ); ?>" target="_new"><?php _e( 'Preview', 'cp' ); ?></a>

						<?php
						/* if (current_user_can('fieldpress_change_field_stop_status_cap') || ( current_user_can('fieldpress_change_my_field_stop_status_cap') && $stop_object->post_author == get_current_user_id() )) { ?>
						  <input type="submit" name="submit-stop-<?php echo ( $stop_object->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>" class="button button-stops button-<?php echo ( $stop_object->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>" value="<?php echo ( $stop_object->post_status == 'unpublished' ) ? __('Publish', 'cp') : __('Unpublish', 'cp'); ?>">
						  <?php
						  } */
					}
					?>

					<?php if ( $stop_id != 0 ) { ?>
						<span class="delete_stop">
							<a class="button button-stops button-delete-stop" href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_id . '&stop_id=' . $stop_id . '&action=delete_stop' ); ?>" onclick="return removeStop();">
								<i class="fa fa-trash-o"></i>&nbsp;&nbsp;&nbsp;<?php _e( 'Delete Stop', 'cp' ); ?>
							</a>
						</span>
					<?php } ?>

                </div>
            </div>
            <div class='section elements-section'>
                <input type="hidden" name="beingdragged" id="beingdragged" value="" />
                <div id='field'>


                    <div id='edit-sub' class='field-holder-wrap elements-wrap'>

                        <div class='field-holder'>
                            <!--<div class='field-details'>

                                <label for='stop_description'><?php //_e('Introduction to this Stop', 'cp');                   ?></label>
							<?php
							// $editor_name = "stop_description";
							// $editor_id = "stop_description";
							// $editor_content = htmlspecialchars_decode($stop_details->post_content);
							//
							//                             $args = array( "textarea_name" => $editor_name, "textarea_rows" => 10 );
							//
							//                             if ( !isset($stop_details->post_content) ) {
							//                                 $stop_details = new StdClass;
							//                                 $stop_details->post_content = '';
							//                             }
							//
							//                             $desc = '';
							//
							// // Filter $args before showing editor
							// $args = apply_filters('fieldpress_element_editor_args', $args, $editor_name, $editor_id);
							//
							//                             wp_editor($editor_content, $editor_id, $args);
							?>
                                <br/>

                            </div>-->


                            <div class="module-droppable levels-sortable ui-droppable" style='display: none;'>
								<?php _e( 'Drag & Drop stop elements here', 'cp' ); ?>
                            </div>

                            <div id="stop-pages">
                                <ul class="sidebar-name stop-pages-navigation">
                                    <li class="stop-pages-title"><span><?php _e( 'Stop Page(s)', 'cp' ); ?></span></li>
									<?php
									if ( $stop_pagination ) {
										$stop_pages = fieldpress_stop_pages( $stop_id, $stop_pagination );
									} else {
										$stop_pages = fieldpress_stop_pages( $stop_id );
									}
									if ( $stop_id == 0 ) {
										$stop_pages = 1;
									}
									for ( $i = 1; $i <= $stop_pages; $i++ ) {
										?>
										<li><a href="#stop-page-<?php echo $i; ?>"><?php echo $i; ?></a><span class="arrow-down"></span></li>
									<?php } ?>
                                    <li class="ui-state-default ui-corner-top add_new_stop_page"><a id="add_new_stop_page" class="ui-tabs-anchor">+</a></li>
                                </ul>

								<?php
								//$pages_num = 1;

								$save_elements = true;

								$show_title = get_post_meta( $stop_id, 'show_page_title', true );

								for ( $i = 1; $i <= $stop_pages; $i++ ) {
									?>
									<div id="stop-page-<?php echo $i; ?>" class='stop-page-holder'>
										<div class='field-details elements-holder'>
											<div class="stop_page_title">
												<label><?php _e( 'Page Label', 'cp' ); ?>
													<span class="delete_stop_page">
														<a class="button button-stops button-delete-stop"><i class="fa fa-trash-o"></i> <?php _e( 'Delete Page', 'cp' ); ?></a>
													</span>
												</label>
												<div class="description"><?php _e( 'The label will be displayed on the Field Trip Overview and Stop page', 'cp' ); ?></div>
												<input type="text" value="<?php echo esc_attr( $stop->get_stop_page_name( $i ) ); ?>" name="page_title[page_<?php echo $i; ?>]" id="page_title_<?php echo $i; ?>" class="page_title" />
												<label class="show_page_title">
													<input type="checkbox" name="show_page_title[]" value="yes" <?php echo ( isset( $show_title[ $i - 1 ] ) && $show_title[ $i - 1 ] == 'yes' ? 'checked' : (!isset( $show_title[ $i - 1 ] ) ) ? 'checked' : '' ) ?> />
													<input type="hidden" name="show_page_title_field[]" value="<?php echo ( (isset( $show_title[ $i - 1 ] ) && $show_title[ $i - 1 ] == 'yes') || !isset( $show_title[ $i - 1 ] ) ? 'yes' : 'no' ) ?>" />
													<?php _e( 'Show page label on stop.', 'cp' ); ?><br />
												</label>

												<label><?php _e( 'Build Page', 'cp' ); ?></label>
												<div class="description"><?php _e( 'Click to add elements to the page', 'cp' ); ?></div>
											</div>
											<?php
											foreach ( $fieldpress_modules_ordered[ 'output' ] as $element ) {
												?>
												<div class="output-element <?php echo $element; ?>">
													<span class="element-label">
														<?php
														$module = new $element;
														echo $module->label;
														?>
													</span>
													<a class="add-element" id="<?php echo $element; ?>"></a>
												</div>
												<?php
											}
											?>
											<div class="elements-separator"></div>
											<?php
											foreach ( $fieldpress_modules_ordered[ 'input' ] as $element ) {
												?>
												<div class="input-element <?php echo $element; ?>">
													<span class="element-label">
														<?php
														$module = new $element;
														echo $module->label;
														?>
													</span>
													<a class="add-element" id="<?php echo $element; ?>"></a>
												</div>
												<?php
											}
											foreach ( $fieldpress_modules_ordered[ 'invisible' ] as $element ) {
												?>
												<div class="input-element <?php echo $element; ?>">
													<span class="element-label">
														<?php
														$module = new $element;
														echo $module->label;
														?>
													</span>
													<a class="add-element" id="<?php echo $element; ?>"></a>
												</div>
												<?php
											}
											$save_elements	 = false;
											?>

											<hr />

											<span class="no-elements"><?php _e( 'No elements have been added to this page yet', 'cp' ); ?></span>

										</div>


										<?php /* if ( is_array( $modules ) && count( $modules ) >= 1 ) {
										  ?>
										  <div class="loading_elements"><?php _e( 'Loading Stop elements, please wait...', 'cp' ); ?></div>
										  <?php } */ ?>

										<div class="modules_accordion">
											<!--modules will appear here-->
											<?php
											$stop_id		 = ($stop_id == 0 ? -1 : $stop_id);

											if ( $stop_pagination ) {
												$modules	 = Stop_Module::get_modules( $stop_id, $i );
												$pages_num	 = 1;
												foreach ( $modules as $mod ) {
													$class_name = $mod->module_type;
													if ( class_exists( $class_name ) ) {
														$module = new $class_name();
														$module->admin_main( $mod );
													}
												}
											} else {
												$modules	 = Stop_Module::get_modules( $stop_id, 0 );
												$pages_num	 = 1;
												foreach ( $modules as $mod ) {
													$class_name = $mod->module_type;

													if ( class_exists( $class_name ) ) {
														$module = new $class_name();

														if ( $module->name == 'page_break_module' ) {
															$pages_num++;
															if ( $pages_num == $i ) {
																$module->admin_main( $mod );
															}
														} else {
															if ( $pages_num == $i ) {
																$module->admin_main( $mod );
															}
														}
													}
												}
											}
											//$module->get_modules_admin_forms( isset( $_GET['stop_id'] ) ? $_GET['stop_id'] : '-1' );
											?>
										</div>

									</div>
									<?php
								}
								?>
                            </div>

							<div class="stop_pages_preloader">
								<div class="preloader_image"><?php _e( 'Loading stop elements...', 'cp' ); ?></div>
							</div>

							<div class="stop_pages_delete">
								<div class="stop_pages_delete_message"><?php _e( 'Deleting the stop page...', 'cp' ); ?></div>
							</div>

                            <div class="field-details-stop-controls">
                                <div class="stop-control-buttons">

									<?php
									if ( $stop_id == 0 && FieldPress_Capabilities::can_create_field_stop( $field_id ) ) {//do not show anything
										?>
										<input type="hidden" name="preview_redirect" value="<?php echo $preview_redirect; ?>" />
										<input type="submit" name="submit-stop" class="button button-stops save-stop-button" value="<?php _e( 'Save', 'cp' ); ?>">
										<!--<input type="submit" name="submit-stop-publish" class="button button-stops button-publish" value="<?php _e( 'Publish', 'cp' ); ?>">-->

									<?php } ?>

									<?php
									if ( $stop_id != 0 && FieldPress_Capabilities::can_update_field_stop( $field_id, $stop_id ) ) {//do not show anything
										?>
										<input type="hidden" name="preview_redirect" value="<?php echo $preview_redirect; ?>" />
										<input type="submit" name="submit-stop" class="button button-stops save-stop-button" value="<?php echo ( $stop_object->post_status == 'unpublished' ) ? __( 'Save', 'cp' ) : __( 'Save', 'cp' ); ?>">
									<?php } ?>

									<?php
									if ( FieldPress_Capabilities::can_update_field_stop( $field_id, $stop_id ) ) {//do not show anything
										?>
										<a class="button button-preview" href="<?php echo get_permalink( $stop_id ); ?>" data-href="<?php echo get_permalink( $stop_id ); ?>" target="_new"><?php _e( 'Preview', 'cp' ); ?></a>

										<?php
										/* if (current_user_can('fieldpress_change_field_stop_status_cap') || ( current_user_can('fieldpress_change_my_field_stop_status_cap') && $stop_object->post_author == get_current_user_id() )) { ?>
										  <input type="submit" name="submit-stop-<?php echo ( $stop_object->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>" class="button button-stops button-<?php echo ( $stop_object->post_status == 'unpublished' ) ? 'publish' : 'unpublish'; ?>" value="<?php echo ( $stop_object->post_status == 'unpublished' ) ? __('Publish', 'cp') : __('Unpublish', 'cp'); ?>">
										  <?php
										  } */
									}
									?>

                                    <div class="stop-state">
										<?php
										$control_position = 'off';
										if ( $stop_id > 0 && $stop_object && 'publish' == $stop_object->post_status ) {
											$control_position = 'on';
										}
										?>
                                        <div class="stop_state_id" data-id="<?php echo $stop_id; ?>" data-nonce="<?php echo $data_nonce; ?>"></div>
                                        <span class="draft <?php echo 'off' == $control_position ? 'on' : 'off'; ?>"><?php _e( 'Draft', 'cp' ); ?></span>
                                        <div class="control <?php echo $can_publish ? '' : 'disabled'; ?> <?php echo $control_position; ?>">
                                            <div class="toggle"></div>
                                        </div>
                                        <span class="live <?php echo 'on' == $control_position ? 'on' : 'off'; ?>"><?php _e( 'Live', 'cp' ); ?></span>
                                    </div>

                                </div>
                            </div>

                        </div><!--/field-holder-->
                    </div><!--/field-holder-wrap-->
                </div><!--/field-->
            </div> <!-- /section -->
        </form>
    </div> <!-- field-liquid-left -->

    <div class='level-liquid-right' style="display:none;">
        <div class="level-holder-wrap">
			<?php
			$sections = array( "input" => __( 'Input Elements', 'cp' ), "output" => __( 'Output Elements', 'cp' ), "invisible" => __( 'Invisible Elements', 'cp' ) );

			foreach ( $sections as $key => $section ) {
				?>

				<div class="sidebar-name no-movecursor">
					<h3><?php echo $section; ?></h3>
				</div>

				<div class="section-holder" id="sidebar-<?php echo $key; ?>" style="min-height: 98px;">
					<ul class='modules'>
						<?php
						if ( isset( $fieldpress_modules[ $key ] ) ) {
							foreach ( $fieldpress_modules[ $key ] as $mmodule => $mclass ) {
								$module = new $mclass();
								if ( !array_key_exists( $mmodule, $module ) ) {
									$module->admin_sidebar( false );
								} else {
									$module->admin_sidebar( true );
								}

								$module->admin_main( array() );
							}
						}
						?>
					</ul>
				</div>
				<?php
			}
			?>
        </div> <!-- level-holder-wrap -->

    </div> <!-- level-liquid-right -->


    <script type="text/javascript">
		jQuery( document ).ready( function($) {

			/*jQuery( '.modules_accordion .switch-tmce' ).each( function() {
				jQuery( this ).trigger( 'click' );
			} );*/

			jQuery('.switch-html').click();
			//jQuery('.switch-tmce').click();

			//$('.wp-switch-editor.switch-tmce').click();

			var current_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
			var elements_count = jQuery( '#stop-page-1 .modules_accordion .module-holder-title' ).length;
			var current_page_elements_count = jQuery( '#stop-page-' + current_page + ' .modules_accordion .module-holder-title' ).length;
			//jQuery('#stop-page-' + current_stop_page + ' .elements-holder .no-elements').show();

			if ( fieldpress_stops.stop_pagination == 0 ) {
				if ( ( current_page == 1 && elements_count == 0 ) || ( current_page >= 2 && current_page_elements_count == 1 ) ) {
					jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
				} else {
					jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
				}
			} else {
				if ( elements_count == 0 ) {
					jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
				} else {
					jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
				}
			}

			var current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();

			jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion( "option", "active", <?php echo ($stop_page_num == 1) ? ($active_element) : $active_element; ?> );

			var stop_pages = jQuery( "#stop-pages .ui-tabs-nav li" ).size() - 2;

			if ( stop_pages == 1 ) {
				jQuery( ".delete_stop_page" ).hide();
			} else {
				jQuery( ".delete_stop_page" ).show();
			}

			FieldPress.editor.init();
			//$.each( $( '.fieldpress-editor' ), function ( index, editor ) {
			//	var id = $( editor ).attr( 'id' );
			//
			//	var content = $( '#' + id ).val();
			//	var name = $( editor ).attr( 'name' );
			//	var height = $( editor ).attr( 'data-height' ) ? $( editor ).attr( 'data-height' ) : 400;
			//
			//	FieldPress.editor.create( editor, id, name, content, false, height );
			//
			//	$( '[name="' + name + '"]' ).on('keyup', function( object ) {
			//
			//		// Fix Enter/Return key
			//		if( 13 === object.keyCode ) {
			//			$( this ).val( $( this ).val() + "\n" );
			//		}
			//
			//		FieldPress.Events.trigger( 'editor:keyup', this );
			//	});
			//
			//} );

			jQuery( '#stop-pages' ).css( 'display', 'block' );
			jQuery( '.stop_pages_preloader' ).css( 'display', 'none' );
			jQuery( '.stop-pages-navigation' ).css( 'opacity', '1' );
			//jQuery( '#stop-pages' ).css( 'cursor', 'default' );
			//jQuery('#stop-pages').tabs({active: <?php echo $stop_page_num; ?>});
		} );
    </script>
</div> <!-- wrap -->