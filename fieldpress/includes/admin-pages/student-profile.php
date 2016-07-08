<?php
if ( isset( $_GET['student_id'] ) && is_numeric( $_GET['student_id'] ) ) {
	$student = new Student( $_GET['student_id'] );
}

if ( isset( $_POST['field_id'] ) ) {
	if ( wp_verify_nonce( $_POST['save_class_and_group_changes'], 'save_class_and_group_changes' ) ) {
		$field = new Field( $_POST['field_id'] );
		if ( current_user_can( 'manage_options' ) || ( current_user_can( 'fieldpress_change_students_group_class_cap' ) ) || ( current_user_can( 'fieldpress_change_my_students_group_class_cap' ) && $field->details->post_author == get_current_user_id() ) ) {
			$student->update_student_group( $_POST['field_id'], $_POST['field_group'] );
			$student->update_student_class( $_POST['field_id'], $_POST['field_class'] );
			$message = __( 'Group and Class for the student has been updated successfully.', 'cp' );
		} else {
			$message = __( 'You do not have required permissions to change field trip group and/or class for the student.', 'cp' );
		}
	}
}
?>
<div class="wrap nofieldsub cp-wrap">
	<a href="<?php echo admin_url( 'admin.php?page=students' ); ?>" class="back_link">&laquo; <?php _e( 'Back to Students', 'cp' ); ?></a>

	<h2><?php _e( 'Student Profile', 'cp' ); ?></h2>

	<form action="" name="field-add" method="post">

		<div class="field">

			<?php
			if ( isset( $message ) ) {
				?>
				<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
			<?php
			}
			?>

			<div id="field">

				<div id="edit-sub" class="field-holder-wrap mp-wrap">

					<div class="field-holder">

						<div class='student-profile-info'>
							<?php echo get_avatar( $student->ID, '80' ); ?>

							<div class="student_additional_info">
								<div>
									<span class="info_caption"><?php _e( 'Student ID', 'cp' ); ?></span>
									<span class="info"><?php echo $student->ID; ?></span>
								</div>
								<div>
									<span class="info_caption"><?php _e( 'Username', 'cp' ); ?></span>
									<span class="info"><?php echo $student->user_login; ?></span>
								</div>
								<div>
									<span class="info_caption"><?php _e( 'First Name', 'cp' ); ?></span>
									<span class="info"><?php echo $student->user_firstname; ?></span>
								</div>
								<div>
									<span class="info_caption"><?php _e( 'Surname', 'cp' ); ?></span>
									<span class="info"><?php echo $student->user_lastname; ?></span>
								</div>
								<div>
									<span class="info_caption"><?php _e( 'Email', 'cp' ); ?></span>
									<span class="info"><a href="mailto:<?php echo $student->user_email; ?>"><?php echo $student->user_email; ?></a></span>
								</div>
								<div>
									<span class="info_caption"><?php _e( 'Field Trips', 'cp' ); ?></span>
									<span class="info"><?php echo Student::get_fields_number( $student->ID ); ?></span>
								</div>
								<div>
									<span class="info_caption"><?php _e( 'Edit', 'cp' ); ?></span>
									<span class="info"><a href="user-edit.php?user_id=<?php echo $student->ID; ?>"><i class="fa fa-pencil"></i></a></span>
								</div>
							</div>
							<div class="full border-divider"></div>
						</div>
						<!--student-profile-info-->

						<?php
						$columns = array(
							"field"          => __( ' ', 'cp' ),
							"additional_info" => __( ' ', 'cp' ),
						);
						?>

						<div class="fields" id="student-profile-fields">
							<div class="sidebar-name no-movecursor">
								<h3><?php _e( 'Field Trips', 'cp' ); ?></h3>

								<?php
								$enrolled_fields = $student->get_enrolled_fields_ids();

								if ( count( $enrolled_fields ) == 0 ) {
									?>
									<div class="zero-fields"><?php _e( 'Student did not enroll in any field trip yet.', 'cp' ); ?></div>
								<?php
								}

								foreach ( $enrolled_fields as $field_id ) {

									$field_object = new Field( $field_id );
									$field_object = $field_object->get_field();

									if ( $field_object ) {
										?>
										<div class="student-field">

											<div class="student-field-top">
												<a href="<?php echo admin_url( 'admin.php?page=students&action=workbook&student_id=' . $student->ID . '&field_id=' . $field_object->ID ); ?>" class="button button-stops workbook-button"><?php _e( 'View Workbook', 'cp' ); ?>
													<i class="fa fa-book cp-move-icon"></i></a>

												<div class="field-title">
													<a href="<?php echo admin_url( 'admin.php?page=field_details&field_id=' . $field_object->ID ); ?>"><?php echo $field_object->post_title; ?></a>
													<a href="<?php echo admin_url( 'admin.php?page=field_details&field_id=' . $field_object->ID ); ?>"><i class="fa fa-pencil"></i></a>
													<a href="<?php echo get_permalink( $field_object->ID ); ?>" target="_blank"><i class="fa fa-external-link"></i></a>
												</div>
											</div>

											<div class="student-field-bottom">

												<div class="field-summary"><?php echo cp_get_the_field_excerpt( $field_object->ID ); ?></div>

												<div class="field-info-holder">
													<span class="field_info_caption"><?php _e( 'Start', 'cp' ); ?>
														<i class="fa fa-calendar"></i></span>
                                                    <span class="field_info">
                                                        <?php
                                                        if ( $field_object->open_ended_field == 'on' ) {
	                                                        _e( 'Open-ended', 'cp' );
                                                        } else {
	                                                        echo $field_object->field_start_date;
	                                                        echo $field_object->field_start_time;
                                                        }
                                                        ?>
                                                    </span>

													<span class="field_info_caption"><?php _e( 'End', 'cp' ); ?>
														<i class="fa fa-calendar"></i></span>
                                                    <span class="field_info">
                                                        <?php
                                                        if ( $field_object->open_ended_field == 'on' ) {
	                                                        _e( 'Open-ended', 'cp' );
                                                        } else {
	                                                        echo $field_object->field_end_date;
	                                                        echo $field_object->field_end_time;
                                                        }
                                                        ?>
                                                    </span>

													<span class="field_info_caption"><?php _e( 'Duration', 'cp' ); ?>
														<i class="fa fa-clock-o"></i></span>
                                                    <span class="field_info">
                                                        <?php
                                                        if ( $field_object->open_ended_field == 'on' ) {
	                                                        echo '&infin;';
                                                        } else {
	                                                        echo cp_get_number_of_days_between_dates( $field_object->field_start_date, $field_object->field_end_date );
                                                        }
                                                        ?> <?php _e( 'Days', 'cp' ); ?>
                                                    </span>
												</div>

											</div>
											<!--student-field-right-->

											<?php if ( ( ( current_user_can( 'fieldpress_change_students_group_class_cap' ) ) || ( current_user_can( 'fieldpress_change_my_students_group_class_cap' ) && $field_object->post_author == get_current_user_id() ) ) && 1 == 0 /* moving for the next release */ ) { ?>
												<div class="field-controls alternate">

													<form name="form_student_<?php echo $field_object->ID; ?>" id="form_student_<?php echo $field_object->ID; ?>" method="post" action="<?php echo admin_url( 'admin.php?page=students&action=view&student_id=' . $student->ID ); ?>">
														<?php wp_nonce_field( 'save_class_and_group_changes', 'save_class_and_group_changes' ); ?>

														<input type="hidden" name="field_id" value="<?php echo $field_object->ID; ?>"/>
														<input type="hidden" name="student_id" value="<?php echo $student->ID; ?>"/>

														<div class="changable">
															<label class="class-label">
																<?php _e( 'Class', 'cp' ); ?>

																<select name="field_class" data-placeholder="'<?php _e( 'Choose a Class...', 'cp' ); ?>'" id="field_class_<?php echo $field_object->ID; ?>">

																	<option value=""<?php echo( $student->{'enrolled_field_class_' . $field_object->ID} == '' ? ' selected="selected"' : '' ); ?>><?php _e( 'Default', 'cp' ); ?></option>
																	<?php
																	$field_classes = get_post_meta( $field_object->ID, 'field_classes', true );
																	if ( ! empty( $field_classes ) ) {
																		foreach ( $field_classes as $class ) {
																			?>
																			<option value="<?php echo $class; ?>"<?php echo( $student->{'enrolled_field_class_' . $field_object->ID} == $class ? ' selected="selected"' : '' ); ?>><?php echo $class; ?></option>
																		<?php
																		}
																	}
																	?>
																</select>
															</label>

															<label class="group-label">
																<?php _e( 'Group', 'cp' ); ?>
																<select name="field_group" id="field_group_<?php echo $field_object->ID; ?>" data-placeholder="<?php esc_attr_e( 'Choose a Group...', 'cp' ); ?>">
																	<option value=""<?php echo( $student->{'enrolled_field_group_' . $field_object->ID} == '' ? ' selected="selected"' : '' ); ?>><?php _e( 'Default', 'cp' ); ?></option>
																	<?php
																	$groups = get_option( 'field_groups' );
																	if ( count( $groups ) >= 1 && $groups != '' ) {
																		foreach ( $groups as $group ) {
																			?>
																			<option value="<?php echo $group; ?>"<?php echo( $student->{'enrolled_field_group_' . $field_object->ID} == $group ? ' selected="selected"' : '' ); ?>><?php echo $group; ?></option>
																		<?php
																		}
																	}
																	?>
																</select>
															</label>

															<?php submit_button( __( 'Save Changes', 'cp' ), 'secondary', 'save-group-class-changes', '' ) ?>

														</div>

													</form>

												</div>
											<?php } else { ?>
												<div class="full border-divider"></div>
											<?php } ?>
										</div>
									<?php
									}
								}
								?>
							</div>
						</div>

					</div>
					<!-- field holder -->
				</div>

			</div>
		</div>
		<!-- field -->

	</form>

</div>