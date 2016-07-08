<?php
global $fieldpress;

if ( isset( $_GET[ 'field_action' ] ) && isset( $_GET[ 'field_id' ] ) ) {
	if ( $_GET[ 'field_action' ] == 'duplicate' ) {
		if ( isset( $_GET[ 'duplicating_nonce' ] ) && wp_verify_nonce( $_GET[ 'duplicating_nonce' ], 'duplicating_field' ) ) {
			$field_id	 = (int) $_GET[ 'field_id' ];
			$field		 = new Field( $field_id );
			$field->duplicate();
		}
	}
}

if ( isset( $_GET[ 'quick_setup' ] ) ) {
	include( 'quick-setup.php' );
} else {
	if ( isset( $_GET[ 's' ] ) ) {
		$s = $_GET[ 's' ];
	} else {
		$s = '';
	}

	$page = $_GET[ 'page' ];

	if ( isset( $_POST[ 'bulk_fields' ] ) && !empty( $_POST[ 'bulk_fields' ] ) ) {
		$bulk_fields = explode( ',', $_POST[ 'bulk_fields' ] );
	} else {
		$bulk_fields = false;
	}

	if ( isset( $_POST[ 'action' ] ) && $bulk_fields ) {

		check_admin_referer( 'bulk-fields' );

		$action = $_POST[ 'action' ];

		$some_success = false;

		foreach ( $bulk_fields as $field_value ) {
			if ( is_numeric( $field_value ) ) {
				$field_id		 = (int) $field_value;
				$field			 = new Field( $field_id );
				$field_object	 = $field->get_field();

				switch ( addslashes( $action ) ) {
					case 'publish':
						if ( FieldPress_Capabilities::can_change_field_status( $field_id ) ) {
							$field->change_status( 'publish' );
							$message		 = __( 'Selected field trips have been published successfully.', 'cp' );
							$some_success	 = true;
						} else {
							if ( $some_success ) {
								$message = __( "Your selected field trips have been published successfully. Field Trips where you don't have access remain unchaged.", 'cp' );
							} else {
								$message = __( "You don't have right permissions to change field trip status.", 'cp' );
							}
						}
						break;

					case 'unpublish':
						if ( FieldPress_Capabilities::can_change_field_status( $field_id ) ) {
							$field->change_status( 'private' );
							$message		 = __( 'Selected fields have been unpublished successfully.', 'cp' );
							$some_success	 = true;
						} else {
							if ( $some_success ) {
								$message = __( "Your selected field trips have been unpublished successfully. Field trips where you don't have access remain unchaged.", 'cp' );
							} else {
								$message = __( "You don't have right permissions to change field trip status.", 'cp' );
							}
						}
						break;

					case 'delete':
						if ( FieldPress_Capabilities::can_delete_field( $field_id ) ) {
							$field->delete_field();
							$message		 = __( 'Selected field trip have been deleted successfully.', 'cp' );
							$some_success	 = true;
						} else {
							if ( $some_success ) {
								$message = __( "Your selected field trips have been deleted successfully. Field trips where you don't have access remain unchaged.", 'cp' );
							} else {
								$message = __( "You don't have right permissions to delete the field trip.", 'cp' );
							}
						}
						break;
				}
			}
		}
	}

// Query the field trips
	if ( isset( $_GET[ 'page_num' ] ) ) {
		$page_num = (int) $_GET[ 'page_num' ];
	} else {
		$page_num = 1;
	}

	if ( isset( $_GET[ 's' ] ) ) {
		$fieldsearch = $_GET[ 's' ];
	} else {
		$fieldsearch = '';
	}

	$field_category		 = isset( $_GET[ 'field_category_filter' ] ) ? (int) $_GET[ 'field_category_filter' ] : 0;
	$show_fields_per_page	 = isset( $_GET[ 'fields_per_page' ] ) ? $_GET[ 'fields_per_page' ] : 10;
	$wp_field_search		 = new Field_Search( $fieldsearch, $page_num, $show_fields_per_page, $field_category );

	if ( isset( $_GET[ 'field_id' ] ) ) {
		$field = new Field( $_GET[ 'field_id' ] );
	}

	if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'delete' && isset( $_GET[ 'field_id' ] ) && is_numeric( $_GET[ 'field_id' ] ) ) {
		if ( !isset( $_GET[ 'cp_nonce' ] ) || !wp_verify_nonce( $_GET[ 'cp_nonce' ], 'delete_field_' . $_GET[ 'field_id' ] ) ) {
			die( __( 'Cheating huh?', 'cp' ) );
		}
		$field_object = $field->get_field();
		if ( FieldPress_Capabilities::can_delete_field( $_GET[ 'field_id' ] ) ) {
			$field->delete_field( $force_delete	 = true );
			$message		 = __( 'Selected field has been deleted successfully.', 'cp' );
		} else {
			$message = __( "You don't have right permissions to delete the field trip.", 'cp' );
		}
	}

	if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'change_status' && isset( $_GET[ 'field_id' ] ) && is_numeric( $_GET[ 'field_id' ] ) ) {
		if ( !isset( $_GET[ 'cp_nonce' ] ) || !wp_verify_nonce( $_GET[ 'cp_nonce' ], 'change_field_status_' . $_GET[ 'field_id' ] ) ) {
			die( __( 'Cheating huh?', 'cp' ) );
		}
		$field->change_status( $_GET[ 'new_status' ] );
		$message = __( 'Status for the selected field has been changed successfully.', 'cp' );
	}
	?>
	<div class="wrap nosubsub cp-wrap">
		<input type="hidden" name="field_page_number" id="field_page_number" value="<?php echo (int) $page_num; ?>"/>

		<div class="icon32" id="icon-themes"><br></div>
		<h2><?php _e( 'Field Trips', 'cp' ); ?>
			<?php
			if ( FieldPress_Capabilities::can_create_field() ) {
				if ( $wp_field_search->is_light ) {
					if ( $wp_field_search->get_count_of_all_fields() < $wp_field_search->fields_per_page ) {
						?>
						<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=field_details' ); ?>"><?php _e( 'Add New', 'cp' ); ?></a>
						<?php
					}
				} else {
					?>
					<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=field_details' ); ?>"><?php _e( 'Add New', 'cp' ); ?></a>
					<?php
				}
			}
			?>
		</h2>

		<?php
		if ( isset( $message ) ) {
			?>
			<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
			<?php
		}
		?>
		<div class="tablenav tablenav-top">

			<div class="alignright actions new-actions">
				<form method="get" action="<?php echo admin_url( 'admin.php?page=' . $page ); ?>" class="search-form">
					<p class="search-box">
						<input type='hidden' name='page' value='<?php echo esc_attr( $page ); ?>'/>
						<label class="screen-reader-text"><?php _e( 'Search Field Trips', 'cp' ); ?>:</label>
						<input type="text" value="<?php echo esc_attr( $s ); ?>" name="s">
						<input type="submit" class="button" value="<?php _e( 'Search Field Trips', 'cp' ); ?>">
					</p>
				</form>
			</div>
			<!--/alignright-->

			<form method="post" action="<?php echo esc_attr( admin_url( 'admin.php?page=' . $page ) ); ?>" id="posts-filter">
				<?php // Use broad capability checking here, specific field capabilities will be checked when attempting to perform the actions. ?>
				<?php if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_change_field_status_cap' ) || current_user_can( 'fieldpress_delete_field_cap' ) ) { ?>
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value=""><?php _e( 'Bulk Actions', 'cp' ); ?></option>
							<?php if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_change_field_status_cap' ) ) { ?>
								<option value="publish"><?php _e( 'Publish', 'cp' ); ?></option>
								<option value="unpublish"><?php _e( 'Unpublish', 'cp' ); ?></option>
							<?php } ?>
							<?php if ( current_user_can( 'manage_options' ) || current_user_can( 'fieldpress_delete_field_cap' ) ) { ?>
								<option value="delete"><?php _e( 'Delete', 'cp' ); ?></option>
							<?php } ?>
						</select>
						<input type="hidden" name="bulk_fields" id="bulk_fields_values" value="" />
						<?php wp_nonce_field( 'bulk-fields' ); ?>
						<input type="submit" class="button-secondary action" id="doaction_bulk_fields" name="doaction" value="<?php _e( 'Apply', 'cp' ); ?>"/>
					</div>
				<?php } ?>
			</form>
			<form id="posts-filter" action="<?php echo esc_attr( admin_url( 'admin.php?page=' . $page ) ); ?>" method="get">
				<div class="alignleft actions">
					<input type='hidden' name='page' value='<?php echo esc_attr( $page ); ?>'/>
					<select name="field_category_filter" id="cat" class="postform">
						<?php
						$taxonomies = array(
							'field_category',
						);

						$args = array(
							'orderby'		 => 'name',
							'order'			 => 'ASC',
							'hide_empty'	 => false,
							'fields'		 => 'all',
							'hierarchical'	 => true,
						);

						$terms				 = get_terms( $taxonomies, $args );
						$category_filter	 = (!isset( $_GET[ 'field_category_filter' ] ) || ( isset( $_GET[ 'field_category_filter' ] ) && $_GET[ 'field_category_filter' ] == '0' ) ) ? false : true;
						$category_filter_val = (!$category_filter ) ? 0 : (int) $_GET[ 'field_category_filter' ];
						?>
						<option value="0" <?php selected( $category_filter_val, 0, true ); ?>><?php _e( 'View all categories', 'cp' ); ?></option>
						<?php
						foreach ( $terms as $terms ) {
							?>
							<option value="<?php echo $terms->term_id; ?>" <?php selected( $category_filter_val, $terms->term_id, true ); ?>><?php echo $terms->name; ?></option>
							<?php
						}
						?>
					</select>
					<input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php _e( 'Filter', 'cp' ); ?>">
				</div>
			</form>


			<br class="clear">

		</div>
		<!--/tablenav-->


		<?php
		wp_nonce_field( 'bulk-fields' );

		$columns = array(
			"field"	 => __( 'Field Trip', 'cp' ),
			"stops"		 => __( 'Stops', 'cp' ),
			"students"	 => __( 'Students', 'cp' ),
			"status"	 => __( 'Published', 'cp' ),
		//"actions" => __('Actions', 'cp'),
		);


		$col_sizes = array(
			'3',
			'55',
			'10',
			'4',
			'10'
		);

		$columns[ "remove" ] = __( 'Delete', 'cp' );
		$col_sizes[]		 = '7';
		?>

		<table cellspacing="0" class="widefat shadow-table stop-control-buttons" id="fields_table">
			<thead>
				<tr>
					<th style="width: 3%;" class="manage-column column-cb check-column" id="cb" scope="col" width="<?php echo $col_sizes[ 0 ] . '%'; ?>">
						<input type="checkbox"></th>
					<?php
					$n					 = 1;
					foreach ( $columns as $key => $col ) {
						?>
						<th class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" style="width: <?php echo $col_sizes[ $n ] . '%'; ?>;" scope="col"><?php echo $col; ?></th>
						<?php
						$n ++;
					}
					?>
				</tr>
			</thead>
			<?php
			$selected_field_order_by = get_option( 'field_order_by', 'post_date' );
			?>
			<tbody class="<?php
			if ( $selected_field_order_by == 'field_order' ) {
				echo 'field-rows';
			}
			?>">
					   <?php
					   $style			 = '';
					   $can_list_count	 = 0;
					   $list_order		 = 1;
					   $fields =  $wp_field_search->get_results();
					   foreach ( $fields as $field ) {

						   $can_list = false;

						   // $can_create = FieldPress_Capabilities::can_creare_field();
						   $can_update              = FieldPress_Capabilities::can_update_field( $field->ID );
						   $can_delete              = FieldPress_Capabilities::can_delete_field( $field->ID );
						   $can_publish             = FieldPress_Capabilities::can_change_field_status( $field->ID );
						   $can_create_stop         = FieldPress_Capabilities::can_create_field_stop( $field->ID );
						   $can_update_stop         = FieldPress_Capabilities::can_update_field_stop( $field->ID );
						   $can_view_stop           = FieldPress_Capabilities::can_view_field_stops( $field->ID );
						   $can_delete_stop         = FieldPress_Capabilities::can_delete_field_stop( $field->ID );
						   $can_publish_stop        = FieldPress_Capabilities::can_change_field_stop_status( $field->ID );
						   $my_field               = FieldPress_Capabilities::is_field_instructor( $field->ID );
						   $creator                 = FieldPress_Capabilities::is_field_creator( $field->ID );
						   $zero_instructor_fields = false;

						   if ( !$my_field && !$creator && !$can_update && !$can_delete && !$can_publish && !$can_view_stop ) {
							   continue;
						   } else {
							   $can_list = true;
							   $can_list_count ++;
						   }

						   $field_obj		 = new Field( $field->ID );
						   $field_object	 = $field_obj->get_field();

						   $style = ''; //( 'alternate' == $style ) ? '' : 'alternate';
						   ?>
					<tr id='user-<?php echo $field_object->ID; ?>' class="<?php echo $style; ?> field-row">
						<th scope='row' class='check-column'>
							<input type='checkbox' name='fields[]' id='user_<?php echo $field_object->ID; ?>' class='' value='<?php echo $field_object->ID; ?>'/>
						</th>
						<td class="column-field <?php echo $style; ?>"><?php if ( $can_update ) { ?>
								<a href="<?php echo admin_url( 'admin.php?page=field_details&field_id=' . $field_object->ID ); ?>"><?php } ?>
								<strong><?php echo $field_object->post_title; ?></strong><?php if ( $can_update ) { ?>
								</a><?php } ?><br/>
					<!-- <div class="field-thumbnail"><img src="<?php echo Field::get_field_thumbnail( $field->ID ); ?>" alt="<?php echo esc_attr( $field_object->post_title ); ?>" /></div> -->
							<div class="field_excerpt"><?php echo cp_get_the_field_excerpt( $field_object->ID, apply_filters( 'field_admin_excerpt_length', 55 ) ); ?></div>
							<div class="column-field-stops visible-small visible-extra-small">
								<strong><?php _e( 'Stops', 'cp' ); ?>:</strong>
								<?php echo $field_obj->get_stops( '', 'any', true ); ?> <?php _e( 'Stops', 'cp' ); ?>,
								<?php echo $field_obj->get_stops( '', 'publish', true ); ?> Published
							</div>
							<div class="column-field-students visible-small visible-extra-small">
								<strong><?php _e( 'Students', 'cp' ); ?>:</strong>
								<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=students&field_id=' . $field_object->ID ); ?>"><?php echo $field_obj->get_number_of_students(); ?></a>
							</div>
							<div class="row-actions hide-small hide-extra-small">
								<?php if ( $can_update ) { ?>
									<span class="edit_field"><a href="<?php echo admin_url( 'admin.php?page=field_details&field_id=' . $field_object->ID ); ?>"><?php _e( 'Edit', 'cp' ); ?></a> | </span>
								<?php } ?>
								<?php if ( $can_view_stop || $my_field ) { ?>
									<span class="field_stops"><a href="<?php echo admin_url( 'admin.php?page=field_details&tab=stops&field_id=' . $field_object->ID ); ?>"><?php _e( 'Stops', 'cp' ); ?></a> | </span>
								<?php } ?>
								<?php if ( $can_update || $my_field ) { ?>
									<span class="field_students"><a href="<?php echo admin_url( 'admin.php?page=field_details&tab=students&field_id=' . $field_object->ID ); ?>"><?php _e( 'Students', 'cp' ); ?></a> | </span>
								<?php } ?>
								<span class="view_field"><a href="<?php echo get_permalink( $field->ID ); ?>" rel="permalink"><?php _e( 'View Field Trip', 'cp' ) ?></a>
									<?php if ( $can_view_stop || $my_field || $can_update ) { ?> | <?php } ?></span>
								<?php if ( $can_view_stop || $my_field || $can_update ) { ?>
									<span class="stops"><a href="<?php echo trailingslashit( get_permalink( $field->ID ) ) . trailingslashit( $fieldpress->get_stops_slug() ); ?>" rel="permalink"><?php _e( 'View Stops', 'cp' ) ?></a></span>
								<?php } ?>
								<?php
								if ( FieldPress_Capabilities::can_create_field() ) {
									if ( $wp_field_search->is_light ) {
										if ( $wp_field_search->get_count_of_all_fields() < $wp_field_search->fields_per_page ) {
											?>
											|
											<span class="stops"><a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=fields&field_action=duplicate&field_id=' . $field_object->ID ), 'duplicating_field', 'duplicating_nonce' ); ?>"><?php _e( 'Duplicate Field Trip', 'cp' ) ?></a></span>
											<?php
										}
									} else {
										?>
										|
										<span class="stops"><a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=fields&field_action=duplicate&field_id=' . $field_object->ID ), 'duplicating_field', 'duplicating_nonce' ); ?>"><?php _e( 'Duplicate Field Trip', 'cp' ) ?></a></span>
										<?php
									}
								}
								?>
							</div>
						</td>
						<td class="column-stops <?php echo $style; ?>">
							<?php echo $field_obj->get_stops( '', 'any', true ); ?> <?php _e( 'Stops', 'cp' ); ?><br/>
							<?php echo $field_obj->get_stops( '', 'publish', true ); ?> <?php _e( 'Published', 'cp' ); ?>
						</td>
						<td class="center column-students <?php echo $style; ?>"><?php if ( $can_update || $my_field ) { ?>
								<a href="<?php echo admin_url( 'admin.php?page=field_details&tab=students&field_id=' . $field_object->ID ); ?>"><?php } ?><?php echo $field_obj->get_number_of_students(); ?><?php if ( $can_update || $my_field ) { ?></a> <?php } ?>
						</td>
						<td class="column-status <?php echo $style; ?>">
							<div class="fields-state">
								<?php
								$data_nonce = wp_create_nonce( 'toggle-' . $field->ID );
								?>
								<div class="field_state_id" data-id="<?php echo $field->ID; ?>" data-nonce="<?php echo $data_nonce; ?>"></div>
								<span class="draft <?php echo ( $field_object->post_status == 'unpublished' ) ? 'on' : '' ?>"><i class="fa fa-ban"></i></span>

								<div class="control <?php echo $can_publish ? '' : 'disabled'; ?> <?php echo ( $field_object->post_status == 'unpublished' ) ? '' : 'on' ?>">
									<div class="toggle"></div>
								</div>
								<span class="live <?php echo ( $field_object->post_status == 'unpublished' ) ? '' : 'on' ?>"><i class="fa fa-check"></i></span>
							</div>
						</td>

						<td class="column-remove <?php echo $style; ?>">
							<?php if ( $can_delete ) { ?>
								<a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=fields&action=delete&field_id=' . $field_object->ID ), 'delete_field_' . $field_object->ID, 'cp_nonce' ); ?>" onClick="return removeField();">
									<i class="fa fa-times-circle cp-move-icon remove-btn"></i>
								</a>
							<?php } ?>
						</td>
				<input type="hidden" class="field_order" value="<?php echo $list_order; ?>" name="field_order_<?php echo $field_object->ID; ?>"/>
				<input type="hidden" name="field_id" class="field_id" value="<?php echo $field_object->ID; ?>"/>
				</tr>
				<?php
				$list_order ++;
			}
			?>

			<?php
			if ( count( $wp_field_search->get_results() ) == 0 ) {
				?>
				<tr>
					<td colspan="6">
						<div class="zero-fields"><?php _e( 'No field trips found.', 'cp' ) ?></div>
					</td>
				</tr>
				<?php
			}

			if ( $can_list_count == 0 && !current_user_can( 'manage_options' ) ) {//shows only to instructors
				?>
				<tr>
					<td colspan="6">
						<div class="zero-fields"><?php _e( 'No field trips found.', 'cp' ) ?></div>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<!--/widefat shadow-table-->
	</form>
	<div class="tablenav">
		<?php if ( !$wp_field_search->is_light ) { ?>
			<form id="posts-filter" action="" method="get">
				<div class="alignleft actions bulkactions">
					<input type="hidden" name="page" value="fields"/>
					<input type="hidden" name="page_num" value="<?php echo esc_attr( $page_num ); ?>"/>
					<select name="fields_per_page" id="fields_per_page">
						<option value="10" <?php selected( $show_fields_per_page, 10, true ); ?>><?php _e( 'Show 10 rows', 'cp' ); ?></option>
						<option value="20" <?php selected( $show_fields_per_page, 20, true ); ?>><?php _e( 'Show 20 rows', 'cp' ); ?></option>
						<option value="30" <?php selected( $show_fields_per_page, 30, true ); ?>><?php _e( 'Show 30 rows', 'cp' ); ?></option>
						<option value="40" <?php selected( $show_fields_per_page, 40, true ); ?>><?php _e( 'Show 40 rows', 'cp' ); ?></option>
						<option value="50" <?php selected( $show_fields_per_page, 50, true ); ?>><?php _e( 'Show 50 rows', 'cp' ); ?></option>
						<option value="60" <?php selected( $show_fields_per_page, 60, true ); ?>><?php _e( 'Show 60 rows', 'cp' ); ?></option>
						<option value="70" <?php selected( $show_fields_per_page, 70, true ); ?>><?php _e( 'Show 70 rows', 'cp' ); ?></option>
						<option value="80" <?php selected( $show_fields_per_page, 80, true ); ?>><?php _e( 'Show 80 rows', 'cp' ); ?></option>
						<option value="90" <?php selected( $show_fields_per_page, 90, true ); ?>><?php _e( 'Show 90 rows', 'cp' ); ?></option>
						<option value="100" <?php selected( $show_fields_per_page, 100, true ); ?>><?php _e( 'Show 100 rows', 'cp' ); ?></option>
					</select>
					<input type="submit" name="" class="button action" value="<?php esc_attr_e( 'Apply', 'cp' ); ?>">
				</div>
			</form>

			<div class="tablenav-pages"><?php $wp_field_search->page_links( $show_fields_per_page, $field_category ); ?></div>
		<?php } ?>
	</div>
	<!--/tablenav-->


	</div><!--/wrap-->
<?php } ?>