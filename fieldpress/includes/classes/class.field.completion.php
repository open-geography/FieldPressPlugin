<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Field_Completion' ) ) {

	/**
	 * Class Field_Completion
	 *
	 * DEPRECATION NOTICE:
	 *
	 * This class is deprecated in favour of using student completion, rather than field completion.
	 * It is remaining here for backward compatibility, but will disappear in a future version.
	 *
	 */
	class Field_Completion extends Field {

		/**
		 * Primary object array for determining completion.
		 *
		 * The following items get added during constructions:
		 *
		 * * ->modules()
		 * * ->page_count
		 * * ->input_module_ids[]
		 * * ->mandatory_module_ids[]
		 * * ->gradable_module_ids[]
		 *
		 * The following additional items get added when initialising student status:
		 *
		 * * ->all_pages_viewed // bool
		 * * ->pages_visited[]
		 * * ->mandatory_answered[]
		 * * ->all_mandatory_answered // bool
		 * * ->gradable_passed[]
		 * * ->all_modules_passed //bool
		 * * ->remaining_mandatory_items
		 * * ->total_steps
		 * * ->completed_steps
		 * * ->completion
		 *
		 * @since 1.0
		 */
		var $stops = array();
		var $stop_index = array();
		var $completion_status = 'unfinished';
		var $student_id = 0;

		function __construct( $id = '', $output = 'OBJECT' ) {
			parent::__construct( $id, $output );
			$stops = $this->get_stops();

			foreach ( $stops as $key => $stop ) {
				$stop_id = $stop['post']->ID;
				$this->stop_index[ $stop_id ] = $key;

				// Used to get input modules
				$stop->modules = $this->get_stop_modules( $stop_id );
				// cp_write_log( $stop->modules );
				// Used to determine page views
				$stop->page_count = $this->get_stop_pages( $stop );

				// Used to determine mandatory modules
				$stop->input_module_ids     = $this->get_input_modules( $stop->modules );
				$stop->mandatory_module_ids = $this->get_mandatory_modules( $stop->modules, $stop->input_module_ids );

				// Uses only mandatory modules
				$stop->gradable_module_ids = $this->get_gradable_modules( $stop->modules, $stop->mandatory_module_ids );

				$this->stops[] = $stop;
			}
		}

		function Field_Completion( $id = '', $output = 'OBJECT' ) {
			$this->__construct( $id, $output );
		}

		function get_stop_modules( $stop_id ) {
			$modules = Stop_Module::get_modules( $stop_id );

			return $modules;
		}

		function get_stop_pages( $stop ) {
			$pages_num = 1;

			if ( ! cp_stop_uses_new_pagination( $stop['post']->ID ) ) {
				// Legacy
				$modules = $stop->modules;
				foreach ( $modules as $mod ) {
					$class_name = $mod->module_type;
					if ( 'page_break_module' == $class_name ) {
						$pages_num ++;
					}
				}
			} else {
				// New stop builder 1.2.3.5+
				$pages_num = Stop::get_page_count( $stop['post']->ID );
			}

			return $pages_num;
		}

		function get_input_modules( $modules ) {
			$inputs        = array();
			$input_modules = array(
				'checkbox_input_module',
				'file_input_module',
				'radio_input_module',
				'text_input_module'
			);
			$count         = 0;
			foreach ( $modules as $mod ) {
				$class_name = $mod->module_type;
				if ( in_array( $class_name, $input_modules ) ) {
					$inputs[ $mod->ID ] = $count;
				}
				$count += 1;
			}

			return $inputs;
		}

		function get_mandatory_modules( $modules, $input_ids ) {
			$mandatory_ids = array();
			foreach ( $input_ids as $key => $input_id ) {
				$mandatory = get_post_meta( $modules[ $input_id ]->ID, 'mandatory_answer', true );
				if ( 'yes' == $mandatory ) {
					$mandatory_ids[ $key ] = $input_id;
				}
			}

			return $mandatory_ids;
		}

		function get_gradable_modules( $modules, $input_ids ) {
			$gradable_ids = array();
			foreach ( $input_ids as $key => $input_id ) {
				$gradable = get_post_meta( $modules[ $input_id ]->ID, 'gradable_answer', true );
				if ( 'yes' == $gradable ) {
					$gradable_ids[ $key ] = $input_id;
				}
			}

			return $gradable_ids;
		}

		function init_pages_visited( $student_id = 0 ) {

			foreach ( $this->stops as $stop ) {
				$pages = get_user_option( 'visited_stop_pages_' . $stop->ID . '_page', $student_id );
				if ( $pages ) {
					$pages = explode( ',', $pages );
					//unset($pages[0]);
					$stop->pages_visited = $pages;
				} else {
					$stop->pages_visited = array();
				}
			}
		}

		function check_pages_visited( $student_id = 0 ) {
			foreach ( $this->stops as $stop ) {
				$visited = $stop->pages_visited;
				if ( $stop->page_count == count( $visited ) ) {
					$stop->all_pages_viewed = true;
					do_action( 'fieldpress_set_all_stop_pages_viewed', $student_id, $this->id, $stop->ID );
				} else {
					$stop->all_pages_viewed = false;
				}
			}
		}

		function init_mandatory_modules_answered( $student_id = 0 ) {

			foreach ( $this->stops as $stop ) {
				$stop->mandatory_answered = array();
				foreach ( $stop->mandatory_module_ids as $key => $mod_id ) {
					$module = $stop->modules[ $mod_id ];
					$module = new $module->module_type( $module->ID );

					$class_name = Stop_Module::get_module_type( $module->ID );
					$response   = call_user_func( $class_name . '::get_response', $student_id, $stop->modules[ $mod_id ]->ID );

					if ( ! empty( $response ) ) {
						$stop->mandatory_answered[ $key ] = true;
						do_action( 'fieldpress_set_mandatory_question_answered', $this->student_id, $this->id, $stop->ID, $mod_id );
					} else {
						$stop->mandatory_answered[ $key ] = false;
					}
				}
			}
		}

		function check_mandatory_modules_answered( $student_id = 0 ) {

			foreach ( $this->stops as $stop ) {
				$stop_answered = true;
				foreach ( $stop->mandatory_module_ids as $key => $mod_id ) {
					$module = $stop->modules[ $mod_id ];

					$answered = false;
					if ( ! empty( $stop->mandatory_answered[ $key ] ) && $stop->mandatory_answered[ $key ] ) {
						$answered = true;
					}
					$stop_answered &= $answered;
				}
				$stop->all_mandatory_answered = $stop_answered;
			}
		}

		function init_gradable_modules_passed( $student_id = 0 ) {
			foreach ( $this->stops as $stop ) {
				$stop->gradable_passed = array();
				foreach ( $stop->gradable_module_ids as $key => $mod_id ) {
					$module = $stop->modules[ $mod_id ];
					$module = new $module->module_type( $module->ID );

					$class_name = Stop_Module::get_module_type( $module->ID );
					$response   = call_user_func( $class_name . '::get_response', $student_id, $stop->modules[ $mod_id ]->ID );

					$minimum_grade = get_post_meta( $stop->modules[ $mod_id ]->ID, 'minimum_grade_required', true );
					$grade         = false;
					$success       = false;
					if ( ! empty( $response ) ) {
						$grade   = Stop_Module::get_response_grade( $response->ID );
						$success = $grade['grade'] >= $minimum_grade ? true : false;
						if ( $success ) {
							do_action( 'fieldpress_set_gradable_question_passed', $this->student_id, $this->id, $stop->ID, $mod_id );
						}
					}

					$stop->gradable_passed[ $key ] = $success;
				}
			}
		}

		function check_gradable_modules_passed( $student_id = 0 ) {

			foreach ( $this->stops as $stop ) {
				$stop_passed                 = true;
				$stop->gradable_passed_count = 0;
				$stop->total_gradable        = count( $stop->gradable_module_ids );
				foreach ( $stop->gradable_module_ids as $key => $mod_id ) {
					$module  = $stop->modules[ $mod_id ];
					$success = false;
					if ( ! empty( $stop->gradable_passed[ $key ] ) && $stop->gradable_passed[ $key ] ) {
						$success = true;
						$stop->gradable_passed_count += 1;
					}
					$stop_passed &= $success;
				}
				$stop->all_modules_passed = $stop_passed;
			}
		}

		function get_remaining_mandatory_items() {
			foreach ( $this->stops as $stop ) {
				$remaining = count( $stop->mandatory_module_ids );

				foreach ( array_keys( $stop->mandatory_module_ids ) as $module_id ) {

					$answered = $stop->mandatory_answered[ $module_id ];
					$gradable = in_array( $module_id, array_keys( $stop->gradable_module_ids ) );
					$passed   = $gradable ? $stop->gradable_passed[ $module_id ] : false;

					if ( $answered && ( ( $gradable && $passed ) || ! $gradable ) ) {
						$remaining -= 1;
					}
				}

				$stop->remaining_mandatory_items = $remaining;
			}
		}

		function get_total_steps() {
			foreach ( $this->stops as $stop ) {
				$total_steps = $stop->page_count;
				$total_steps += count( $stop->mandatory_module_ids );

				$stop->total_steps = $total_steps;
			}
		}

		function get_completed_steps() {
			foreach ( $this->stops as $stop ) {
				$completed_steps = count( $stop->pages_visited );
				$completed_steps += count( $stop->mandatory_module_ids ) - $stop->remaining_mandatory_items;

				$stop->completed_steps = $completed_steps;
			}
		}

		function get_completion() {
			foreach ( $this->stops as $stop ) {
				$completion = $stop->completed_steps / $stop->total_steps * 100;

				// Prevent an accidental percentage higher than 100%
				$completion = $completion <= 100 ? (int) $completion : 100;

				$stop->completion = ( int ) $completion;
			}
		}

		function init_student_status( $student_id = 0 ) {
			$this->student_id = ! empty( $student_id ) ? $student_id : get_current_user_id();

			$this->init_pages_visited( $this->student_id );
			$this->check_pages_visited( $this->student_id );

			$this->init_mandatory_modules_answered( $this->student_id );
			$this->check_mandatory_modules_answered( $this->student_id );

			$this->init_gradable_modules_passed( $this->student_id );
			$this->check_gradable_modules_passed( $this->student_id );

			$this->get_remaining_mandatory_items();
			$this->get_total_steps();
			$this->get_completed_steps();
			$this->get_completion();

			if ( $this->is_field_complete() ) {
				do_action( 'fieldpress_set_field_completed', $this->student_id, $this->id );
			}
		}

		function stop_progress( $stop_id ) {

			if ( ! in_array( $stop_id, array_keys( $this->stop_index ) ) ) {
				return false;
			} else {

				// Get the correct stop
				$stop = $this->stops[ $this->stop_index[ $stop_id ] ];

				return ( $stop->completion );
			}
		}

		function stop_mandatory_steps( $stop_id ) {

			if ( ! in_array( $stop_id, array_keys( $this->stop_index ) ) ) {
				return false;
			} else {

				// Get the correct stop
				$stop = $this->stops[ $this->stop_index[ $stop_id ] ];

				return count( $stop->mandatory_module_ids );
			}
		}

		function stop_completed_mandatory_steps( $stop_id ) {

			if ( ! in_array( $stop_id, array_keys( $this->stop_index ) ) ) {
				return false;
			} else {

				// Get the correct stop
				$stop = $this->stops[ $this->stop_index[ $stop_id ] ];

				return count( $stop->mandatory_module_ids ) - $stop->remaining_mandatory_items;
			}
		}

		function stop_all_pages_viewed( $stop_id ) {

			if ( ! in_array( $stop_id, array_keys( $this->stop_index ) ) ) {
				return false;
			} else {

				// Get the correct stop
				$stop = $this->stops[ $this->stop_index[ $stop_id ] ];

				return $stop->all_pages_viewed;
			}
		}

		function stop_all_mandatory_answered( $stop_id ) {

			if ( ! in_array( $stop_id, array_keys( $this->stop_index ) ) ) {
				return false;
			} else {

				// Get the correct stop
				$stop = $this->stops[ $this->stop_index[ $stop_id ] ];

				return $stop->all_mandatory_answered;
			}
		}

		function field_progress() {
			$total = 0;
			foreach ( $this->stops as $stop ) {
				$total += $stop->completion;
			}
			if ( count( $this->stops ) > 0 ) {
				$total = $total / count( $this->stops );
			} else {
				$total = 0;
			}

			return ( int ) $total;
		}

		function is_stop_complete( $stop_id ) {
			if ( ! in_array( $stop_id, array_keys( $this->stop_index ) ) ) {
				return false;
			} else {

				// Get the correct stop
				$stop = $this->stops[ $this->stop_index[ $stop_id ] ];

				return 100 == $stop->completion ? true : false;
			}
		}

		function is_field_complete() {
			$field_complete = ! empty( $this->stops ) ? true : false;
			foreach ( $this->stops as $stop ) {
				$stop_completed = $this->is_stop_complete( $stop->ID );

				if ( $stop_completed ) {
					do_action( 'fieldpress_set_stop_completed', $this->student_id, $this->id, $stop->ID );
				}

				$field_complete &= $stop_completed;
			}

			return $field_complete;
		}

	}

}