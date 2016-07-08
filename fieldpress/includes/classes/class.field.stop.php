<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Stop' ) ) {

	class Stop extends FieldPress_Object {

		var $id = '';
		var $output = 'OBJECT';
		var $stop = array();
		var $details;
		var $field_id = '';
		var $status = array();
		public static $last_stops_request = array();

		function __construct( $id = '', $output = 'OBJECT' ) {
			$this->id     = $id;
			$this->output = $output;

			// Attempt to load from cache or create new cache object
			if ( ! self::load( self::TYPE_STOP, $this->id, $this->details ) ) {

				// Get the field trip
				$this->details = get_post( $this->id, $this->output );

				// Initialize the stop
				$this->init_stop( $this->details );

				// Cache the stop object
				self::cache( self::TYPE_STOP, $this->id, $this->details );
				// cp_write_log( 'Stop[' . $this->id . ']: Saved to cache..');
			} else {
				// cp_write_log( 'Stop[' . $this->id . ']: Loaded from cache...');
			};

			// Will return cached value if it exists
			$this->field_id = $this->get_parent_field_id();

			/**
			 * Perform action after a Stop object is created.
			 *
			 * @since 1.2.2
			 */
			do_action( 'fieldpress_stop_init', $this );
		}

		function Stop( $id = '', $output = 'OBJECT' ) {
			$this->__construct( $id, $output );
		}

		function init_stop( &$stop ) {
			if ( ! empty( $stop ) ) {

				if ( $stop->post_title == '' ) {
					$stop->post_title = __( 'Untitled', 'cp' );
				}

				if ( $stop->post_status == 'private' || $stop->post_status == 'draft' ) {
					$stop->post_status = 'unpublished';
				}

				// Set parent ID
				$field_id       = get_post_meta( $stop->ID, 'field_id', true );
				$stop->field_id = $field_id;

				$stop->current_stop_order = get_post_meta( $stop->ID, 'stop_order', true );
				// if ( !isset( $stop->details->post_name ) ) {
				// 	$stop->details->post_name = '';
				// }
			}
		}

		function get_stop() {
			return ! empty( $this->details ) ? $this->details : false;
		}

		public static function is_stop_available( $stop_id, $status = false ) {

			if ( ! $status ) {
				$status = self::get_stop_availability_status( $stop_id );
			}

			return $status['available'];
		}

		public static function get_stop_availability_status( $stop_id ) {
			$field_id           = (int) get_post_field( 'post_parent', $stop_id );
			$stop = Field::get_stop( $stop_id, $field_id, true );

			if( empty( $stop ) ) {
				return array( 'available' => false );
			}

			$stop_available_date = $stop->meta['stop_availability'][0];

			/* Not filtering date format as it could cause conflicts.  Only filter date on display. */
			$current_date = ( date( 'Y-m-d', current_time( 'timestamp', 0 ) ) );

			/* Check if previous has conditions (only published stops) */
			$previous_stop_id                         = self::get_previous_stop_from_the_same_field( $field_id, $stop_id );
			$previous_stop = ! empty( $previous_stop_id ) ? Field::get_stop( $previous_stop_id, $field_id, true ) : false;
			$force_current_stop_completion            = ! empty( $previous_stop_id ) ? $previous_stop->meta[ 'force_current_stop_completion' ][0] : '';
			$force_current_stop_successful_completion = ! empty( $previous_stop_id ) ? $previous_stop->meta[ 'force_current_stop_successful_completion' ][0] : '';

			$available = true;

			$student_id     = get_current_user_id();
			$mandatory_done = Student_Completion::is_mandatory_complete( $student_id, $stop->field_id, $previous_stop_id );
			$stop_completed = Student_Completion::is_stop_complete( $student_id, $stop->field_id, $previous_stop_id );

			$stop_status = array(
				'mandatory_required' => array(),
				'completion_required' => array(),
				'date_restriction' => array()
			);
			$stop_status['mandatory_required']['enabled'] = ! empty( $force_current_stop_completion ) && 'on' == $force_current_stop_completion;
			$stop_status['mandatory_required']['result']  = $mandatory_done;

			$stop_status['completion_required']['enabled'] = ! empty( $force_current_stop_successful_completion ) && 'on' == $force_current_stop_successful_completion;
			$stop_status['completion_required']['result']  = $stop_completed;

			$available = $stop_status['mandatory_required']['enabled'] ? $stop_status['mandatory_required']['result'] : $available;
			$available = $stop_status['completion_required']['enabled'] ? $stop_status['completion_required']['result'] : $available;

			$stop_status['date_restriction']['result'] = $current_date >= $stop_available_date;

			if ( ! $stop_status['date_restriction']['result'] || ! $available ) {
				$available = false;
			} else {
				$available = true;
			}

			/**
			 * Perform action if stop is available.
			 *
			 * @since 1.2.2
			 * */
			do_action( 'fieldpress_stop_availble', $available, $stop_id );

			/**
			 * Return filtered value.
			 *
			 * Can be used by other plugins to filter stop availability.
			 *
			 * @since 1.2.2
			 * */
			$available = apply_filters( 'fieldpress_filter_stop_availability', $available, $stop_id );

			$status              = $stop_status;
			$status['available'] = $available;

			return $status;
		}

		static function get_stops_from_field( $field_id, $status = 'any', $id_only = true ) {

			$stops  = Field::get_stops_with_modules( $field_id );

			if ( $id_only ) {
				return array_keys( $stops );
			} else {
				return $stops;
			}

		}

		public static function get_previous_stop_from_the_same_field( $field_id, $stop_id ) {
			$stops = self::get_stops_from_field( $field_id, 'publish', false );

			// Checks can only be done on publish stops so filter it, even if admin
			$stops = Field::filter_stops( 'publish', $stops );

			$ids      = array_keys( $stops );
			$position = array_search( $stop_id, $ids );

			if ( false === $position ) {
				return false;
			}

			return 0 !== $position ? $ids[ $position - 1 ] : false;
		}

		function get_stop_page_time_estimation( $stop_id, $page_num ) {

			$stop_pagination = cp_stop_uses_new_pagination( $stop_id );

			if ( $stop_pagination ) {
				$stop_pages = fieldpress_stop_pages( $stop_id, $stop_pagination );
			} else {
				$stop_pages = fieldpress_stop_pages( $stop_id );
			}

			//$stop_pages	 = $this->get_number_of_stop_pages();
			$modules = Stop_Module::get_modules( $stop_id, $page_num );

			foreach ( $modules as $mod ) {
				$total_minutes = 0;
				$total_seconds = 0;

				foreach ( $modules as $mod ) {
					$class_name      = $mod->module_type;
					$time_estimation = $mod->time_estimation;

					if ( class_exists( $class_name ) ) {

						if ( isset( $time_estimation ) && $time_estimation !== '' ) {
							$estimatation = explode( ':', $time_estimation );
							if ( isset( $estimatation[0] ) ) {
								$total_minutes = $total_minutes + intval( $estimatation[0] );
							}
							if ( isset( $estimatation[1] ) ) {
								$total_seconds = $total_seconds + intval( $estimatation[1] );
							}
						}
					}
				}

				$total_seconds = $total_seconds + ( $total_minutes * 60 ); //converted everything into minutes for easy conversion back to minutes and seconds

				$minutes = floor( $total_seconds / 60 );
				$seconds = $total_seconds % 60;

				if ( $minutes >= 1 || $seconds >= 1 ) {
					return apply_filters( 'fieldpress_stop_time_estimation_minutes_and_seconds_format', ( $minutes . ':' . ( $seconds <= 9 ? '0' . $seconds : $seconds ) . ' min' ) );
				} else {
					return apply_filters( 'fieldpress_stop_time_estimation_na_format', __( 'N/A', 'cp' ) );
				}
			}
		}

		function get_stop_time_estimation( $stop_id ) {
			$modules       = Stop_Module::get_modules( $stop_id );
			$total_minutes = 0;
			$total_seconds = 0;

			foreach ( $modules as $mod ) {
				$time_estimation = $mod->time_estimation;
				if ( isset( $time_estimation ) && $time_estimation !== '' ) {
					$estimatation = explode( ':', $time_estimation );
					if ( isset( $estimatation[0] ) ) {
						$total_minutes = $total_minutes + intval( $estimatation[0] );
					}
					if ( isset( $estimatation[1] ) ) {
						$total_seconds = $total_seconds + intval( $estimatation[1] );
					}
				}
			}

			$total_seconds = $total_seconds + ( $total_minutes * 60 ); //converted everything into minutes for easy conversion back to minutes and seconds

			$minutes = floor( $total_seconds / 60 );
			$seconds = $total_seconds % 60;

			if ( $minutes >= 1 || $seconds >= 1 ) {
				return apply_filters( 'fieldpress_stop_time_estimation_minutes_and_seconds_format', ( $minutes . ':' . ( $seconds <= 9 ? '0' . $seconds : $seconds ) . ' min' ) );
			} else {
				return apply_filters( 'fieldpress_stop_time_estimation_na_format', __( 'N/A', 'cp' ) );
			}
		}

		function create_auto_draft( $field_id ) {
			global $user_id;

			$post = array(
				'post_author'  => $user_id,
				'post_content' => '',
				'post_status'  => 'auto-draft', //$post_status
				'post_title'   => __( 'Untitled', 'cp' ),
				'post_type'    => 'stop',
				'post_parent'  => $field_id
			);

			$post_id = wp_insert_post( $post );

			// Clear cached object just in case
			self::kill( self::TYPE_STOP, $post_id );
			self::kill( self::TYPE_STOP_MODULES, $post_id );
			self::kill( self::TYPE_STOP_MODULES_PERF, $field_id );

			return $post_id;
		}

		function delete_all_elements_auto_drafts( $stop_id = false ) {
			global $wpdb;

			if ( ! $stop_id ) {
				$stop_id = $this->id;
			}

			$stop_id = (int) $stop_id;

			$drafts = get_posts( array(
				'post_type'     => array( 'module', 'stop' ),
				'post_status'   => 'auto-draft',
				'post_parent'   => $stop_id,
				'post_per_page' => - 1
			) );

			if ( ! empty( $drafts ) ) {
				foreach ( $drafts as $draft ) {
					// Clear possible cached objects because we're deleting them
					self::kill( self::TYPE_STOP, $draft->ID );
					self::kill( self::TYPE_STOP_MODULES, $draft->ID );
					self::kill( self::TYPE_STOP_MODULES_PERF, get_post_field( 'post_parent', $draft->ID ) );
					if ( get_post_type( $draft->ID ) == 'module' || get_post_type( $draft->ID ) == 'stop' ) {
						wp_delete_post( $draft->ID, true );
					}
				}
			}
		}

		function delete_all_stop_auto_drafts( $field_id = false ) {
			global $wpdb;

			if ( ! $stop_id ) {
				$stop_id = $this->field_id;
			}

			$field_id = (int) $field_id;

			$drafts = get_posts( array(
				'post_type'     => array( 'module', 'stop' ),
				'post_status'   => 'auto-draft',
				'post_parent'   => $field_id,
				'post_per_page' => - 1
			) );

			if ( ! empty( $drafts ) ) {
				foreach ( $drafts as $draft ) {
					// Clear possible cached objects because we're deleting them
					self::kill( self::TYPE_STOP, $draft->ID );
					self::kill( self::TYPE_STOP_MODULES, $draft->ID );
					self::kill( self::TYPE_STOP_MODULES_PERF, get_post_field( 'post_parent', $draft->ID ) );
					if ( get_post_type( $draft->ID ) == 'module' || get_post_type( $draft->ID ) == 'stop' ) {
						wp_delete_post( $draft->ID, true );
					}
				}
			}
		}

		function update_stop() {
			global $user_id, $last_inserted_stop_id;

			$post_status = 'private';

			if ( isset( $_POST['stop_id'] ) && $_POST['stop_id'] != 0 ) {

				$stop_id = ( isset( $_POST['stop_id'] ) ? $_POST['stop_id'] : $this->id );

				$stop = get_post( $stop_id, $this->output );

				if ( $_POST['stop_name'] !== '' && $_POST['stop_name'] !== __( 'Untitled', 'cp' ) /* && $_POST['stop_description'] !== '' */ ) {
					if ( $stop->post_status !== 'publish' ) {
						$post_status = 'private';
					} else {
						$post_status = 'publish';
					}
				} else {
					$post_status = 'draft';
				}
			}

			$post = array(
				'post_author'  => $user_id,
				'post_content' => '', //$_POST['stop_description']
				'post_status'  => $post_status, //$post_status
				'post_title'   => cp_filter_content( $_POST['stop_name'], true ),
				'post_type'    => 'stop',
				'post_parent'  => $_POST['field_id']
			);

			$new_stop = true;
			if ( isset( $_POST['stop_id'] ) ) {
				$post['ID'] = $_POST['stop_id']; //If ID is set, wp_insert_post will do the UPDATE instead of insert
				$new_stop   = false;
			}

			$post_id = wp_insert_post( $post );

			// Clear cached object because we're updating the object
			self::kill( self::TYPE_STOP, $post_id );
			self::kill( self::TYPE_STOP_MODULES, $post_id );
			self::kill( self::TYPE_STOP_MODULES_PERF, $_POST['field_id'] );
			// Clear related caches
			$field_id = $this->field_id;
			self::kill_related( self::TYPE_FIELD, $field_id );

			$last_inserted_stop_id = $post_id;

			update_post_meta( $post_id, 'stop_pagination', true );
			update_post_meta( $post_id, 'field_id', (int) $_POST['field_id'] );

			update_post_meta( $post_id, 'stop_availability', cp_filter_content( $_POST['stop_availability'] ) );

			update_post_meta( $post_id, 'force_current_stop_completion', cp_filter_content( $_POST['force_current_stop_completion'] ) );
			update_post_meta( $post_id, 'force_current_stop_successful_completion', cp_filter_content( $_POST['force_current_stop_successful_completion'] ) );

			//cp_write_log($_POST[ 'page_title' ]);
			if ( ! empty( $_POST['page_title'] ) ) {
				update_post_meta( $post_id, 'page_title', cp_filter_content( $_POST['page_title'], true ) );
				update_post_meta( $post_id, 'stop_page_count', count( cp_filter_content( $_POST['page_title'], true ) ) );
			}

			update_post_meta( $post_id, 'show_page_title', cp_filter_content( $_POST['show_page_title_field'] ) );

			if ( ! get_post_meta( $post_id, 'stop_order', true ) ) {
				update_post_meta( $post_id, 'stop_order', $post_id );
			}

			// $this->delete_all_elements_auto_drafts( $post_id );
			// $this->delete_all_stop_auto_drafts( $field_id );

			if ( $new_stop ) {
				// @todo: Potentially never triggered.
				do_action( 'fieldpress_stop_created', $post_id, $field_id );
			} else {
				do_action( 'fieldpress_stop_updated', $post_id, $field_id );
			}

			return $post_id;
		}

		function get_stop_page_name( $page_number ) {
			if ( cp_stop_uses_new_pagination( $this->details->ID ) ) {
				return ! empty( $this->details->page_title[ 'page_' . $page_number ] ) ? $this->details->page_title[ 'page_' . (int) $page_number ] : '';
			} else {
				return ! empty( $this->details->page_title ) ? $this->details->page_title[ (int) ( $page_number - 1 ) ] : '';
			}
		}

		public static function page_name( $stop_id, $page_number ) {
			$page_titles = get_post_meta( $stop_id, 'page_title', true );
			if ( cp_stop_uses_new_pagination( $stop_id ) ) {
				return ! empty( $page_titles[ 'page_' . $page_number ] ) ? $page_titles[ 'page_' . (int) $page_number ] : '';
				//return !empty( $this->details->page_title[ 'page_' . $page_number ] ) ? $this->details->page_title[ 'page_' . (int) $page_number ] : '';
			} else {
				return ! empty( $page_titles ) ? $page_titles[ (int) ( $page_number - 1 ) ] : '';
			}

			return '';
		}

		function delete_stop( $force_delete ) {

			/**
			 * Allow Stop deletion to be cancelled when filter returns true.
			 *
			 * @since 1.2.2
			 */
			if ( apply_filters( 'fieldpress_stop_cancel_delete', false, $this->id ) ) {

				/**
				 * Perform actions if the deletion was cancelled.
				 *
				 * @since 1.2.2
				 */
				do_action( 'fieldpress_stop_delete_cancelled', $this->id );

				return false;
			}

			$the_stop = new Stop( $this->id );

			// Clear cached object because we're deleting the object.
			self::kill( self::TYPE_STOP, $this->id );
			self::kill( self::TYPE_STOP_MODULES, $this->id );
			self::kill( self::TYPE_STOP_MODULES_PERF, get_post_field( 'post_parent', $this->id ) );
			// Clear related caches
			$field_id = $this->field_id;
			self::kill_related( self::TYPE_FIELD, $field_id );

			if ( get_post_type( $this->id ) == 'stop' ) {
				wp_delete_post( $this->id, $force_delete ); //Whether to bypass trash and force deletion
			}
			//Delete stop modules

			$args = array(
				'posts_per_page' => - 1,
				'post_parent'    => $this->id,
				'post_type'      => 'module',
				'post_status'    => 'any',
			);

			$stops_modules = get_posts( $args );

			foreach ( $stops_modules as $stops_module ) {
				Stop_Module::delete_module( $stops_module->ID, true );
			}

			/**
			 * Perform actions after a Stop is deleted.
			 *
			 * @var $field  The Stop object if the ID or post_title is needed.
			 *
			 * @since 1.2.1
			 */
			do_action( 'fieldpress_stop_deleted', $the_stop );
		}

		function change_status( $post_status ) {
			$post = array(
				'ID'          => $this->id,
				'post_status' => $post_status,
			);

			// Update the post status
			wp_update_post( $post );

			// Clear cached object because we've modified the object.
			self::kill( self::TYPE_STOP, $this->id );
			self::kill( self::TYPE_STOP_MODULES, $this->id );
			self::kill( self::TYPE_STOP_MODULES_PERF, get_post_field( 'post_parent', $this->id ) );
			// Clear related caches
			$field_id = $this->field_id;
			self::kill_related( self::TYPE_FIELD, $field_id );

			/**
			 * Perform actions when Stop status is changed.
			 *
			 * var $this->id  The Stop id
			 * var $post_status The new status
			 *
			 * @since 1.2.1
			 */
			do_action( 'fieldpress_stop_status_changed', $this->id, $post_status );
		}

		function can_show_permalink() {
			$stop = $this->get_stop();
			if ( $stop->post_status !== 'draft' ) {
				return true;
			} else {
				return false;
			}
		}

		public static function get_permalink( $stop_id, $field_id = '' ) {
			global $field_slug;
			global $stops_slug;

			if ( empty( $field_id ) ) {
				$field_id = get_post_meta( $stop_id, 'field_id', true );
			}

			$field_post_name = get_post_field( 'post_name', $field_id );
			$stop_post_name   = get_post_field( 'post_name', $stop_id );


			$stop_permalink = trailingslashit( home_url() . '/' ) . trailingslashit( $field_slug . '/' ) . trailingslashit( isset( $field_post_name ) ? $field_post_name : '' . '/' ) . trailingslashit( $stops_slug . '/' ) . trailingslashit( isset( $stop_post_name ) ? $stop_post_name : '' . '/' );

			return $stop_permalink;
		}

		function get_stop_id_by_name( $slug, $field_id = 0 ) {

			if ( empty( $field_id ) ) {
				$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
			}
			if ( ! cp_can_see_stop_draft() ) {
				$post = get_posts(
					array(
						'post_type'        => array( 'stop' ),
						'name'             => $slug,
						'post_per_page'    => 1,
						'post_status'      => 'publish',
						'post_parent'      => $field_id,
						'suppress_filters' => false,
					)
				);
			} else {
				$post_id = cp_get_id_by_post_name( $slug, $field_id );
				$post    = get_post( $post_id );
			}

			$post = ! empty( $post ) && is_array( $post ) ? array_pop( $post ) : $post;

			return ! empty( $post ) ? $post->ID : false;
		}

		function get_parent_field_id( $stop_id = '' ) {

			if ( $stop_id == '' ) {

				// If its already loaded from cache, return that value.
				if ( isset( $this->details ) && isset( $this->details->field_id ) ) {
					return $this->details->field_id;
				}

				$stop_id = $this->id;
			}

			$field_id = get_post_meta( $stop_id, 'field_id', true );

			return $field_id;
		}

		function get_number_of_stop_pages( $stop_id = '' ) {
			if ( $stop_id == '' ) {
				$stop_id = $this->id;
			}

			$modules = Stop_Module::get_modules( $stop_id );

			$pages_num = 1;

			foreach ( $modules as $mod ) {
				$class_name = $mod->module_type;

				if ( class_exists( $class_name ) ) {
					if ( $class_name == 'page_break_module' ) {
						$pages_num ++;
					}
				}
			}

			return $pages_num;
		}

		function get_stop_modules( $stop_id = '' ) {

			if ( $stop_id == '' ) {
				$stop_id = $this->id;
			}

			$args = array(
				'post_type'      => 'module',
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'post_parent'    => $stop_id,
				'meta_key'       => 'module_order',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
			);

			$modules = get_posts( $args );

			return $modules;
		}

		function duplicate( $stop_id = '', $field_id = '' ) {
			global $wpdb;

			if ( $stop_id == '' ) {
				$stop_id = $this->id;
			}

			/**
			 * Allow Stop duplication to be cancelled when filter returns true.
			 *
			 * @since 1.2.2
			 */
			if ( apply_filters( 'fieldpress_stop_cancel_duplicate', false, $stop_id ) ) {

				/**
				 * Perform actions if the duplication was cancelled.
				 *
				 * @since 1.2.2
				 */
				do_action( 'fieldpress_stop_duplicate_cancelled', $stop_id );

				return false;
			}

			/* Duplicate field and change some data */

			$new_stop    = $this->get_stop();
			$old_stop_id = $new_stop->ID;

			unset( $new_stop->ID );
			unset( $new_stop->guid );

			$new_stop->post_author = get_current_user_id();
			$new_stop->post_status = 'private';
			$new_stop->post_parent = $field_id;

			$new_stop_id = wp_insert_post( $new_stop );


			/*
			 * Duplicate stop post meta
			 */

			if ( ! empty( $new_stop_id ) ) {
				$post_metas = get_post_meta( $old_stop_id );
				foreach ( $post_metas as $key => $meta_value ) {
					$value = array_pop( $meta_value );
					$value = maybe_unserialize( $value );
					update_post_meta( $new_stop_id, $key, $value );
				}
			}

			update_post_meta( $new_stop_id, 'field_id', $field_id );

			$stop_modules = $this->get_stop_modules( $old_stop_id );

			foreach ( $stop_modules as $stop_module ) {
				$module = new Stop_Module( $stop_module->ID );
				$module->duplicate( $stop_module->ID, $new_stop_id );
			}

			/**
			 * Perform action when the stop is duplicated.
			 *
			 * @since 1.2.2
			 */
			do_action( 'fieldpress_stop_duplicated', $new_stop_id );
		}

		public static function get_page_count( $stop_id ) {
			// Try to get the page count from the meta field
			$page_count = get_post_meta( $stop_id, 'stop_page_count', true );

			// Or check the page title array if the meta field doesn't exist
			if ( ! isset( $page_count ) || empty( $page_count ) ) {
				$pages = get_post_meta( $stop_id, 'page_title', true );
				if ( isset( $pages ) && ! empty( $pages ) ) {
					$page_count = count( $pages );
				}
			}

			// Return the number of pages or 0.
			return isset( $page_count ) && ! empty( $page_count ) ? $page_count : 1;
		}

		public static function update_input_module_meta( $stop_id, $module_id, $meta ) {

			$input_module_meta = get_post_meta( $stop_id, 'input_modules', true );

			if ( empty( $input_module_meta ) ) {
				$input_module_meta = array();
			}

			$input_module_meta = maybe_unserialize( $input_module_meta );

			if ( $input_module_meta[ $module_id ]['mandatory_answer'] != $meta['mandatory_answer']
			     || $input_module_meta[ $module_id ]['gradable_answer'] != $meta['gradable_answer']
			     || $input_module_meta[ $module_id ]['minimum_grade_required'] != $meta['minimum_grade_required']
			) {
				do_action( 'fieldpress_module_completion_criteria_change', $stop_id, $module_id, $meta, $input_module_meta[ $module_id ] );
			}

			$input_module_meta[ $module_id ] = $meta;

			update_post_meta( $stop_id, 'input_modules', $input_module_meta );
		}

		public static function delete_input_module_meta( $stop_id, $module_id ) {
			$input_module_meta = get_post_meta( $stop_id, 'input_modules', true );

			if ( empty( $input_module_meta ) ) {
				$input_module_meta = array();
			}

			$input_module_meta = maybe_unserialize( $input_module_meta );

			if ( isset( $input_module_meta[ $module_id ] ) ) {
				unset( $input_module_meta[ $module_id ] );
				update_post_meta( $stop_id, 'input_modules', $input_module_meta );
			}
		}

		public static function get_input_module_meta( $stop_id ) {

			$input_module_meta = get_post_meta( $stop_id, 'input_modules', true );

			// For converting legacy stops
			if ( empty( $input_module_meta ) ) {
				// If the meta doesn't exist, create it, expensive call, but will only be used to convert legacy stops (once)
				self::_create_input_module_meta( $stop_id );
				// Now get the new data
				$input_module_meta = get_post_meta( $stop_id, 'input_modules', true );
			}

			return maybe_unserialize( $input_module_meta );
		}

		private static function _create_input_module_meta( $stop_id ) {

			$modules = Stop_Module::get_modules( $stop_id );

			foreach ( $modules as $mod ) {
				$module_id = $mod->ID;

				$module_type = get_post_meta( $module_id, 'module_type', true );
				$module_type = ! empty( $module_type ) ? is_array( $module_type ) ? $module_type[0] : $module_type : false;

				if ( $module_type ) {
					$input_module_types = Stop_Module::get_input_module_types();
					if ( in_array( $module_type, $input_module_types ) ) {

						$mandatory_answer       = get_post_meta( $module_id, 'mandatory_answer', true );
						$gradable_answer        = get_post_meta( $module_id, 'gradable_answer', true );
						$minimum_grade_required = get_post_meta( $module_id, 'minimum_grade_required', true );
						$limit_attempts         = get_post_meta( $module_id, 'limit_attempts', true );
						$limit_attempts_value   = get_post_meta( $module_id, 'limit_attempts_value', true );

						$module_meta = array(
							'mandatory_answer'       => ! empty( $mandatory_answer ) ? is_array( $mandatory_answer ) ? $mandatory_answer[0] : $mandatory_answer : array(),
							'gradable_answer'        => ! empty( $gradable_answer ) ? is_array( $gradable_answer ) ? $gradable_answer[0] : $gradable_answer : array(),
							'minimum_grade_required' => ! empty( $minimum_grade_required ) ? is_array( $minimum_grade_required ) ? $minimum_grade_required[0] : $minimum_grade_required : false,
							'limit_attempts'         => ! empty( $limit_attempts ) ? is_array( $limit_attempts ) ? $limit_attempts[0] : $limit_attempts : false,
							'limit_attempts_value'   => ! empty( $limit_attempts_value ) ? is_array( $limit_attempts_value ) ? $limit_attempts_value[0] : $limit_attempts_value : false,
						);

						self::update_input_module_meta( $stop_id, $module_id, $module_meta );
					}
				}
			}
		}

		public static function get_module_completion_data( $stop_id ) {

			if ( empty( $stop_id ) ) {
				return false;
			}

			$session_data = FieldPress_Session::session( 'fieldpress_stop_completion' );
			$in_session   = isset( $session_data[ $stop_id ] );

			$criteria = array();

			if ( $in_session && ! empty( $session_data[ $stop_id ]['all_input_ids'] ) ) {
				$criteria = $session_data[ $stop_id ];
			} else {
				$module_data              = self::get_input_module_meta( $stop_id );
				$mandatory_array          = array();
				$mandatory_gradable_array = array();
				$gradable_array           = array();
				$min_grades               = array();
				$attempts_array           = array();
				$all_input_ids            = array();

				if ( ! empty( $module_data ) ) {
					foreach ( $module_data as $module_id => $module ) {
						$all_input_ids[] = $module_id;

						$mandatory      = isset( $module['mandatory_answer'] ) ? ( ( $module['mandatory_answer'] == 'yes' ) ? true : false ) : false;
						$gradable       = isset( $module['gradable_answer'] ) ? ( ( $module['gradable_answer'] == 'yes' ) ? true : false ) : false;
						$limit_attempts = isset( $module['limit_attempts'] ) ? ( ( $module['limit_attempts'] == 'yes' ) ? true : false ) : false;

						if ( $mandatory ) {
							$mandatory_array[] = $module_id;
						}
						if ( $gradable ) {
							$gradable_array[]         = $module_id;
							$min_grade                = isset( $module['minimum_grade_required'] ) ? $module['minimum_grade_required'] : 0;
							$min_grades[ $module_id ] = $min_grade;
						}
						if ( $gradable && $mandatory ) {
							$mandatory_gradable_array[] = $module_id;
						}
						if ( ( $mandatory || $gradable ) && $limit_attempts ) {
							$allowed                      = isset( $module['limit_attempts_value'] ) ? $module['limit_attempts_value'] : false;
							$attempts_array[ $module_id ] = $allowed;
						}
					}
				}

				$in_session = false;
				$criteria   = array(
					'mandatory_modules'          => $mandatory_array,
					'gradable_modules'           => $gradable_array,
					'mandatory_gradable_modules' => $mandatory_gradable_array,
					'minimum_grades'             => $min_grades,
					'answer_limit'               => $attempts_array,
					'all_input_ids'              => $all_input_ids,
				);
			}

			if ( ! $in_session ) {
				//$_SESSION[ 'fieldpress_stop_completion' ][ $stop_id ] = $criteria;
				if ( ! is_array( $session_data ) ) {
					$session_data = array();
				}
				$session_data[ $stop_id ] = $criteria;
				FieldPress_Session::session( 'fieldpress_stop_completion', $session_data );
			}

			return $criteria;
		}

	}

}