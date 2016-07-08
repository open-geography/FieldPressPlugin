<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( !defined( 'CP_GRADABLE_RESULTS_HISTORY_LENGTH' ) ){
    define( 'CP_GRADABLE_RESULTS_HISTORY_LENGTH', 10 );//Define the max amount of answer attempts that we keep on records.
}

if ( ! class_exists( 'Student_Completion' ) ) {

	class Student_Completion {

		const CURRENT_VERSION = 2;

        function __construct() {
            add_action( 'fieldpress_module_completion_criteria_change', array( $this, 'on_fieldpress_module_completion_criteria_change' ), 10, 4);
            add_action( 'fieldpress_stop_updated', array( $this, 'on_fieldpress_stop_updated' ), 10, 2);
        }

        function Student_Completion() {
            $this->__construct();
        }

        function on_fieldpress_module_completion_criteria_change($stop_id, $module_id, $new_meta, $old_meta){

            if( $new_meta['mandatory_answer'] =='yes' || 'yes' == $new_meta['gradable_answer']){

                $input_modules = Stop_Module::get_input_module_types();
                $module_type     = Stop_Module::get_module_type( $module_id );
                $module_is_input = in_array( $module_type, $input_modules );

                // Only for input modules
                if ( $module_is_input ) {
                    self::refresh_module_completion($stop_id, $module_id, $module_type, $new_meta);
                }
            }
        }

        function on_fieldpress_stop_updated( $post_id, $field_id ){

            if( !empty( $_POST['refresh_stop_completion_progress'] )){
                //Refresh the mandatory inputs count in session and post_meta.
                $session_data = FieldPress_Session::session( 'fieldpress_stop_completion' );
                unset($session_data[ $post_id ][ 'all_input_ids' ]);
                $input_module_meta = array();
                update_post_meta( $post_id, 'input_modules', $input_module_meta );

                //Refresh the stop completion for each student.
                self::refresh_stop_completion( $post_id );
            }
        }

		/* ----------------------------- GETTING COMPLETION DATA ----------------------------------- */

		public static function get_completion_data( $student_id, $field_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$session_data = FieldPress_Session::session( 'fieldpress_student', null, false, '+10 minutes' ); // Keep completion data for only 10 minutes

			$in_session = isset( $session_data ) && isset( $session_data[ $student_id ]['field_completion'][ $field_id ]['stop'] );
			//$in_session = isset( $_SESSION['fieldpress_student'][ $student_id ]['field_completion'][ $field_id ] );

			if ( $in_session && ! empty( $session_data[ $student_id ]['field_completion'][ $field_id ]['stop'] ) ) {
				// Try the session first...
				//$field_progress = $_SESSION['fieldpress_student'][ $student_id ]['field_completion'][ $field_id ];
				$field_progress = $session_data[ $student_id ]['field_completion'][ $field_id ];
			} else {
				// Otherwise it should be in user meta
				$field_progress = get_user_option( '_field_' . $field_id . '_progress', $student_id );
				if ( empty( $field_progress ) ) {
                    if( is_array( $session_data ) && !empty($session_data[ $student_id ]['field_completion'][ $field_id ]) ) {
                        //If we are here, there are no stop completion data.
                        //Let's keep basic field information from session.
                        $field_progress = $session_data[ $student_id ]['field_completion'][ $field_id ];
                        $in_session = true;
                    } else {
					$field_progress = array();
    				$in_session = false;
			        }
				}
			}

            /********** CHANGE ****/
            /*$field_progress = get_user_option( '_field_' . $field_id . '_progress', $student_id );
            if ( empty( $field_progress ) ) {
                $field_progress = array();
            }
            $in_session = false;*/
            /****** END CHANGE *****/

			if ( ! $in_session ) {
				//$_SESSION['fieldpress_student'][ $student_id ]['field_completion'][ $field_id ] = $field_progress;
				if( ! is_array( $session_data ) ) {
					$session_data = array();
				}
				$session_data[ $student_id ]['field_completion'][ $field_id ] = $field_progress;
				FieldPress_Session::session( 'fieldpress_student', $session_data );
			}

			// Check that we're on the right version or upgrade
			if ( ! self::_check_version( $student_id, $field_id, $field_progress ) ) {
				$field_progress = self::get_completion_data( $student_id, $field_id );
			};

			FieldPress_Cache::cp_cache_set($cache_key, $field_progress);
			return $field_progress;
		}

		public static function get_visited_pages( $student_id, $field_id, $stop_id ) {
			$data = self::get_completion_data( $student_id, $field_id );

			return isset( $data['stop'][ $stop_id ]['visited_pages'] ) ? $data['stop'][ $stop_id ]['visited_pages'] : array();
		}

		public static function get_last_visited_page( $student_id, $field_id, $stop_id ) {
			$data = self::get_completion_data( $student_id, $field_id );

			return isset( $data['stop'][ $stop_id ]['last_visited_page'] ) ? $data['stop'][ $stop_id ]['last_visited_page'] : false;
		}

		public static function is_field_visited( $student_id, $field_id ) {
			$data = self::get_completion_data( $student_id, $field_id );

			return isset( $data['visited'] ) && ! empty( $data['visited'] ) ? true : false;
		}

		public static function get_remaining_pages( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$visited = count( self::get_visited_pages( $student_id, $field_id, $stop_id ) );
			$total   = Stop::get_page_count( $stop_id );
			$remaining = $total - $visited;

			if( 0 == $remaining ) {
				do_action( 'fieldpress_set_all_stop_pages_viewed', $student_id, $field_id, $stop_id );
			}

			FieldPress_Cache::cp_cache_set($cache_key, $remaining);
			return $remaining;
		}

		public static function get_mandatory_modules_answered( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$data = self::get_completion_data( $student_id, $field_id, $stop_id );

			if ( isset( $data['stop'][ $stop_id ]['mandatory_answered'] ) ) {
				foreach ( $data['stop'][ $stop_id ]['mandatory_answered'] as $module_id => $value ) {
					if ( $value !== true ) {
						unset( $data['stop'][ $stop_id ]['mandatory_answered'][ $module_id ] );
					}
				}

				$mandatory_modules_answered = array_keys( $data['stop'][ $stop_id ]['mandatory_answered'] );
				FieldPress_Cache::cp_cache_set($cache_key, $mandatory_modules_answered);
				return $mandatory_modules_answered;
			} else {
				FieldPress_Cache::cp_cache_set($cache_key, array());
				return array();
			}
		}

		public static function get_gradable_module_answered( $student_id, $field_id, $stop_id ) {
			$data = self::get_completion_data( $student_id, $field_id );

			if ( isset( $data['stop'][ $stop_id ]['gradable_results'] ) ) {
				return $data['stop'][ $stop_id ]['gradable_results'];
			} else {
				return array();
			}
		}

		public static function get_gradable_modules_passed( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$criteria = Stop::get_module_completion_data( $stop_id );
			$answers  = self::get_gradable_module_answered( $student_id, $field_id, $stop_id );

			if ( empty( $criteria ) || empty( $answers ) ) {
				FieldPress_Cache::cp_cache_set($cache_key, array());
				return array();
			}

			$passed_array = array();

			foreach ( $criteria['gradable_modules'] as $module_id ) {

				$required = (int) $criteria['minimum_grades'][ $module_id ];
				$passed   = false;

				if ( ! isset( $answers[ $module_id ] ) ) {
					continue;
				}

				foreach ( array_filter( $answers[ $module_id ] ) as $answer ) {
					if ( (int) $answer >= $required ) {
						$passed = true;
						do_action( 'fieldpress_set_gradable_question_passed', $student_id, $field_id, $stop_id, $module_id );
					} else {
						// Could not find a result in completion, but lets check the module for an answer and record it.
						$module          = get_post_meta( $module_id, 'module_type', true );
						$response        = call_user_func( $module . '::get_response', $student_id, $module_id );
						$response_result = Stop_Module::get_response_grade( $response->ID );
						$grade           = (int) $response_result['grade'];

						if( 0 < $grade ) { // Avoid repeated recording of 0 values
							self::record_gradable_result( $student_id, $field_id, $stop_id, $module_id, $grade );
							if ( $grade >= $required ) {
								$passed = true;
								do_action( 'fieldpress_set_gradable_question_passed', $student_id, $field_id, $stop_id, $module_id );
							}
						}
					}
				}
				if ( $passed ) {
					$passed_array[] = $module_id;
				}
			}

			FieldPress_Cache::cp_cache_set($cache_key, $passed_array);
			return $passed_array;
		}

		public static function get_mandatory_gradable_modules_passed( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$criteria = Stop::get_module_completion_data( $stop_id );
			if ( empty( $criteria ) ) {
				FieldPress_Cache::cp_cache_set($cache_key, false);
				return false;
			}
			$mandatory  = $criteria['mandatory_modules'];
			$all_passed = self::get_gradable_modules_passed( $student_id, $field_id, $stop_id );

			// Forget about the ones that are not mandatory
			$mandatory_passed = array_intersect( $mandatory, $all_passed );

			FieldPress_Cache::cp_cache_set($cache_key, $mandatory_passed);
			return $mandatory_passed;
		}

		public static function get_remaining_mandatory_answers( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$criteria = Stop::get_module_completion_data( $stop_id );
			if ( empty( $criteria ) ) {
				FieldPress_Cache::cp_cache_set($cache_key, false);
				return false;
			}
			$mandatory_required = $criteria['mandatory_modules'];
			$mandatory_answered = self::get_mandatory_modules_answered( $student_id, $field_id, $stop_id );

			// Deal with mandatory gradable answers. A mandatory question is not considered done if it is gradable and not passed.
			$mandatory_gradable = $criteria['mandatory_gradable_modules'];
			$mandatory_passed   = self::get_mandatory_gradable_modules_passed( $student_id, $field_id, $stop_id );
			$mandatory_remove   = array_diff( $mandatory_gradable, $mandatory_passed );

			// Some mandatory gradable answers are not yet passed
			if ( ! empty( $mandatory_remove ) ) {
				$mandatory_answered = array_diff( $mandatory_answered, $mandatory_remove );
			}

			$remaining_mandatory_answers = array_diff( $mandatory_required, $mandatory_answered );

			FieldPress_Cache::cp_cache_set($cache_key, $remaining_mandatory_answers);
			return $remaining_mandatory_answers;
		}

		public static function get_remaining_gradable_answers( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$criteria = Stop::get_module_completion_data( $stop_id );
			if ( empty( $criteria ) ) {
				FieldPress_Cache::cp_cache_set($cache_key, false);
				return false;
			}
			$gradable_required = $criteria['gradable_modules'];
			$gradable_passed   = self::get_gradable_modules_passed( $student_id, $field_id, $stop_id );

			$remaining_gradable_answers = array_diff( $gradable_required, $gradable_passed );

			FieldPress_Cache::cp_cache_set($cache_key, $remaining_gradable_answers);
			return $remaining_gradable_answers;
		}

		public static function get_mandatory_steps_completed( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$criteria = Stop::get_module_completion_data( $stop_id );
			if ( empty( $criteria ) ) {
				return false;
			}
			$mandatory           = count( $criteria['mandatory_modules'] );
			$mandatory_remaining = count( self::get_remaining_mandatory_answers( $student_id, $field_id, $stop_id ) );

			$steps_completed = $mandatory - $mandatory_remaining;

			FieldPress_Cache::cp_cache_set($cache_key, $steps_completed);
			return $steps_completed;
		}

		/**
		 * Works out steps left in the stop.
		 *
		 * Calculation:
		 *    $total = number_of_pages_in_stop + number_of_mandatory_questions // (includes graded and non-graded marked as mandatory)
		 *    $completed = number_of_pages_visited + number_of_mandatory_questions_completed // (subtract any mandatory gradable questions not passed)
		 *    $answer = $total - $completed
		 *
		 * @param $student_id
		 * @param $field_id
		 * @param $stop_id
		 *
		 * @return array
		 */
		public static function get_remaining_steps( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$total = self::_total_steps_required( $stop_id );

			//$completed = count( self::get_visited_pages( $student_id, $field_id, $stop_id ) ) + self::get_mandatory_steps_completed( $student_id, $field_id, $stop_id );
			$completed = count( self::get_visited_pages( $student_id, $field_id, $stop_id ) ) ;

			$remaining_steps = $total - $completed;

			FieldPress_Cache::cp_cache_set($cache_key, $remaining_steps);
			return $remaining_steps;
		}

		public static function is_stop_complete( $student_id, $field_id, $stop_id ) {
			$progress = self::calculate_stop_completion( $student_id, $field_id, $stop_id, false );

			return ( 100 <= (int) $progress ) ? true : false;
		}

		public static function is_field_complete( $student_id, $field_id ) {
			$progress = self::calculate_field_completion( $student_id, $field_id, false );

			return ( 100 == (int) $progress ) ? true : false;
		}

		public static function get_mandatory_steps_required( $stop_id ) {
			$cache_key = __METHOD__ . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$criteria = Stop::get_module_completion_data( $stop_id );
			if ( empty( $criteria ) ) {
				FieldPress_Cache::cp_cache_set($cache_key, false);
				return false;
			}

			$mandatory_steps = count( $criteria['mandatory_modules'] );
			FieldPress_Cache::cp_cache_set($cache_key, $mandatory_steps);
			return $mandatory_steps;
		}

		public static function is_mandatory_complete( $student_id, $field_id, $stop_id ) {
			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$remaining = count( self::get_remaining_mandatory_answers( $student_id, $field_id, $stop_id ) );
			$completed = 0 == $remaining ? true : false;

			FieldPress_Cache::cp_cache_set($cache_key, $completed);
			return $completed;
		}

		/* ----------------------------- CALCULATES AND UPDATES STOP/FIELD COMPLETION ----------------------------------- */

		public static function calculate_stop_completion( $student_id, $field_id, $stop_id, $update = true, &$data = false ) {

			if ( empty( $stop_id ) ) {
				return false;
			}

			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id . '-' . $stop_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			if ( empty( $data ) ) {
				$data = self::get_completion_data( $student_id, $field_id );
				self::_check_stop( $data, $stop_id );
			}

			$total     = self::_total_steps_required( $stop_id );
			$completed = $total - self::get_remaining_steps( $student_id, $field_id, $stop_id );

			$progress = $completed / $total * 100.0;
			$progress = $progress > 100 ? 100 : $progress;

			$data['stop'][ $stop_id ]['stop_progress'] = $progress;

			if ( $update ) {
				self::update_completion_data( $student_id, $field_id, $data );
			}

			if( 100 == (int) $progress ) {
				do_action( 'fieldpress_set_stop_completed', $student_id, $field_id, $stop_id );
			}

			FieldPress_Cache::cp_cache_set($cache_key, $progress);
			return $progress;
		}

		public static function calculate_field_completion( $student_id, $field_id, $update = true ) {

			if ( empty( $field_id ) ) {
				return false;
			}

			$cache_key = __METHOD__ . '-' . $student_id . '-' . $field_id;
			if( FieldPress_Cache::cp_cache_get($cache_key) ){
				return FieldPress_Cache::cp_cache_get($cache_key);
			}

			$data        = self::get_completion_data( $student_id, $field_id );
			$field      = new Field( $field_id );
			$total_stops = $field->get_stops( $field_id, 'publish', true );

			// No stops or no stops published
			if ( empty( $total_stops ) ) {
				FieldPress_Cache::cp_cache_set($cache_key, 0);
				return 0;
			}

			$progress = 0.0;

			if ( isset( $data['stop'] ) && is_array( $data['stop'] ) ) {
				foreach ( $data['stop'] as $stop_id => $stop ) {
					if ( 'publish' == get_post_status( $stop_id ) ) {
						$progress += self::calculate_stop_completion( $student_id, $field_id, $stop_id, $update, $data );
					}
				}

				$progress                = $progress / $total_stops;
				$progress = $progress > 100 ? 100 : $progress;
				$data['field_progress'] = $progress;
			}

			if ( $update ) {
				self::update_completion_data( $student_id, $field_id, $data );
			}

			if( 100 == (int) $progress ) {
				do_action( 'fieldpress_set_field_completed', $student_id, $field_id );
			}

			FieldPress_Cache::cp_cache_set($cache_key, $progress);
			return $progress;
		}

		/* ----------------------------- RECORDING AND UPDATING COMPLETION DATA ----------------------------------- */

		public static function update_completion_data( $student_id, $field_id, $data, $version = true ) {

			$global_setting = ! is_multisite();

			if ( empty( $data ) ) {
				$data = self::get_completion_data( $student_id, $field_id );
			}

			update_user_option( $student_id, '_field_' . $field_id . '_progress', $data, $global_setting );

            if( $student_id != get_current_user_id()){
                //If we are here, the current user is the admin or an instructor. i.e. when the student is being graded.
                //We should ensure that the field trip progress in student's session is cleared in order to pick up the fresh data.
                $student_session = WP_Session_Tokens::get_instance( $student_id );
                $student_session->destroy('fieldpress_'.$student_id);
            }

            // make sure session data is also up to date
            $session_data[ $student_id ]['field_completion'][ $field_id ] = $data;
            FieldPress_Session::session( 'fieldpress_student', $session_data );
			$_SESSION['fieldpress_student'][ $student_id ]['field_completion'][ $field_id ] = $data;
			FieldPress_Cache::cp_cache_purge();
		}

		public static function record_mandatory_answer( $student_id, $field_id, $stop_id, $module_id, &$data = false ) {
			if ( $data === false ) {
				$data = self::get_completion_data( $student_id, $field_id );
			}
			self::_check_stop( $data, $stop_id );

			if ( ! isset( $data['stop'][ $stop_id ]['mandatory_answered'] ) ) {
				$data['stop'][ $stop_id ]['mandatory_answered'] = array();
			}

			$data['stop'][ $stop_id ]['mandatory_answered'][ $module_id ] = true;

			do_action( 'fieldpress_set_mandatory_question_answered', $student_id, $field_id, $stop_id, $module_id );

			self::update_completion_data( $student_id, $field_id, $data );
		}

		public static function clear_mandatory_answer( $student_id, $field_id, $stop_id, $module_id ) {
			$data = self::get_completion_data( $student_id, $field_id );
			self::_check_stop( $data, $stop_id );

			if ( ! isset( $data['stop'][ $stop_id ]['mandatory_answered'] ) ) {
				$data['stop'][ $stop_id ]['mandatory_answered'] = array();
			}

			$data['stop'][ $stop_id ]['mandatory_answered'][ $module_id ] = false;
			self::update_completion_data( $student_id, $field_id, $data );
		}

		public static function record_gradable_result( $student_id, $field_id, $stop_id, $module_id, $result, &$data = false ) {
			if ( $data === false ) {
				$data = self::get_completion_data( $student_id, $field_id );
			}
			self::_check_stop( $data, $stop_id );

			if ( ! isset( $data['stop'][ $stop_id ]['gradable_results'] ) ) {
				$data['stop'][ $stop_id ]['gradable_results'] = array();
			}

			if ( ! isset( $data['stop'][ $stop_id ]['gradable_results'][ $module_id ] ) ) {
				$data['stop'][ $stop_id ]['gradable_results'][ $module_id ] = array();
			}

            $gradable_results = $data['stop'][ $stop_id ]['gradable_results'][ $module_id ];
			// Keep previous results, so push to the last entry
            $gradable_results[] = $result;
            // Keep only a few previous records to avoid memory issues.
            // The amount of records to be stored will be determined by the value of CP_GRADABLE_RESULTS_HISTORY_LENGTH.
            $data['stop'][ $stop_id ]['gradable_results'][ $module_id ] = array_slice($gradable_results,count($gradable_results)-CP_GRADABLE_RESULTS_HISTORY_LENGTH);

			self::update_completion_data( $student_id, $field_id, $data );
		}

		public static function record_visited_page( $student_id, $field_id, $stop_id, $page_num, &$data = false ) {
			if ( $data === false ) {
				$data = self::get_completion_data( $student_id, $field_id );
			}
			self::_check_stop( $data, $stop_id );

			if ( ! isset( $data['stop'][ $stop_id ]['visited_pages'] ) ) {
				$data['stop'][ $stop_id ]['visited_pages'] = array();
			}

			if ( ! in_array( $page_num, $data['stop'][ $stop_id ]['visited_pages'] ) ) {
				$data['stop'][ $stop_id ]['visited_pages'][] = $page_num;
			}

			self::_record_last_visited_page( $stop_id, $page_num, $data );
			self::_record_visited_field( $student_id, $field_id, $data );
			self::update_completion_data( $student_id, $field_id, $data );
		}

		/* ----------------------------- PRIVATE METHODS FOR THIS CLASS ----------------------------------- */

		private static function _record_last_visited_page( $stop_id, $page_num, &$data ) {
			$data['stop'][ $stop_id ]['last_visited_page'] = $page_num;
		}

		private static function _record_visited_field( $student_id, $field_id, &$data ) {
			if ( ! isset( $data['visited'] ) ) {
				$data['visited'] = 1;
			}
		}

		private static function _check_stop( &$data, $stop_id ) {
			if ( ! isset( $data['stop'] ) ) {
				$data['stop'] = array();
			}
			if ( ! isset( $data['stop'][ $stop_id ] ) ) {
				$data['stop'][ $stop_id ] = array();
			}
		}

		private static function _total_steps_required( $stop_id ) {
			$criteria = Stop::get_module_completion_data( $stop_id );
			if ( empty( $criteria ) ) {
				return false;
			}
			$total_answers = count( $criteria['mandatory_modules'] );
			$total_pages   = Stop::get_page_count( $stop_id );

			return $total_pages;

			//return $total_answers + $total_pages;
		}

		/* ----------------------------- PRIVATE MAINTENANCE METHODS FOR THIS CLASS ------------------------------ */

		private static function _check_version( $student_id, $field_id, $data ) {
			if ( ! isset( $data['version'] ) || self::CURRENT_VERSION > $data['version'] ) {
				self::_run_completion_upgrade( $student_id, $field_id, $data );

				return false;
			} else {
				return true;
			}
		}

		// Used to update the completion system
		private static function _update_version( $student_id, $field_id, $data, $version ) {
			$data['version'] = $version;
			self::update_completion_data( $student_id, $field_id, $data );
		}

		private static function _run_completion_upgrade( $student_id, $field_id, $data ) {

			$old_version = isset( $data['version'] ) ? (int) $data['version'] : 0;

			// Upgrade to version 1
			if ( 1 > $old_version ) {
				self::_version_1_upgrade( $student_id, $field_id, $data );
			} else if ( 2 > $old_version ){
                self::_version_2_upgrade( $student_id, $field_id, $data );
            }

		}

		// Upgrade to version 1
		public static function _version_1_upgrade( $student_id, $field_id, $data ) {
			// Get stops
			$stops = Stop::get_stops_from_field( $field_id, 'any', true );

			if ( ! empty( $stops ) ) {

				// Traverse stops
				foreach ( $stops as $stop_id ) {

					// Get visited pages data
					$visited_pages = get_user_option( 'visited_stop_pages_' . $stop_id . '_page', $student_id );
					$visited_pages = explode( ',', $visited_pages );

					if ( ! empty( $visited_pages ) ) {
						foreach ( $visited_pages as $page ) {
							if ( ! empty( $page ) ) {
								self::record_visited_page( $student_id, $field_id, $stop_id, $page, $data );
								//cp_write_log( 'Record visited page: Stop: ' . $stop_id . ' Page: ' . $page );
							}
						}
					}

					// Get modules
					$modules       = Stop_Module::get_modules( $stop_id, 0, true );
					$input_modules = Stop_Module::get_input_module_types();

					if ( ! empty( $modules ) ) {

						// Traverse modules
						foreach ( $modules as $module_id ) {

							$module_type     = Stop_Module::get_module_type( $module_id );
							$module_is_input = in_array( $module_type, $input_modules );

							// Only for input modules
							if ( $module_is_input ) {

								$module_meta = Stop_Module::get_module_meta( $module_id );

								// Did the student answer it?
								$response = call_user_func( $module_type . '::get_response', get_current_user_id(), $module_id, 'inherit', - 1, true );

								// Yes
								if ( ! empty( $response ) ) {

									if ( 'yes' == $module_meta['mandatory_answer'] ) {
										self::record_mandatory_answer( $student_id, $field_id, $stop_id, $module_id, $data );
										//cp_write_log( 'Record mandatory answer: Module: ' . $module_id );
									}

									if ( 'yes' == $module_meta['gradable_answer'] ) {
										foreach ( $response as $answer ) {
											$result = Stop_Module::get_response_grade( $answer );
											if( 0 < $result['grade'] ) {
												self::record_gradable_result( $student_id, $field_id, $stop_id, $module_id, $result['grade'], $data );
											}
											//cp_write_log( 'Record gradable result: Module: ' . $module_id . ' Result: ' . $result['grade'] );
										}
									}

								} // End responses

							} // End input module

						} // End Modules loop

					} // End Modules

				} // End Stops loop

			}  // End Stops

			// Remove FieldPress transients
			global $wpdb;
			$table = $wpdb->prefix . 'options';
			$sql = $wpdb->prepare( "DELETE FROM {$table} WHERE `option_name` LIKE %s OR `option_name` LIKE %s", '%_transient_fieldpress_field%', '%_transient_fieldpress_stop%' );
			$wpdb->query( $sql );

			// Record the new version
			self::_update_version( $student_id, $field_id, $data, 1 );
			//cp_write_log( 'Upgraded Field: ' . $field_id . ' to version: ' . 1 );
		}

        // Upgrade to version 2.
        // This upgrade will repair DB records related to gradable results.
        public static function _version_2_upgrade( $student_id, $field_id, $data ) {

            if( !is_user_logged_in()){
                self::_update_version( $student_id, $field_id, $data, 2 );
                return;
            }

            if( !$field_id || !$student_id) return;

            //Get fresh field_progress. $data object might contain out-dated information from session.
            //$field_progress = get_user_option( '_field_' . $field_id . '_progress', $student_id );
            $field_progress = $data;

            if(!empty($field_progress['stop'])){
                foreach($field_progress['stop'] as $stop_key => $stop){
                    if(!empty($stop['gradable_results'])){
                        foreach($stop['gradable_results'] as $result_key => $results){
                            //Remove redundant records. Keep only the amount defined by CP_GRADABLE_RESULTS_HISTORY_LENGTH.
                            $field_progress['stop'][$stop_key]['gradable_results'][$result_key] = array_slice($results,count($results)-CP_GRADABLE_RESULTS_HISTORY_LENGTH);
                        }
                    }
                }
            }

            $global_setting = ! is_multisite();
            update_user_option( $student_id, '_field_' . $field_id . '_progress', $field_progress, $global_setting );
            $session_data[ $student_id ]['field_completion'][ $field_id ] = $field_progress;
            FieldPress_Session::session( 'fieldpress_student', $session_data );

            // Record the new version
            self::_update_version( $student_id, $field_id, $field_progress, 2 );
        }

        public static function refresh_stop_completion( $stop_id ){

            $modules       = Stop_Module::get_modules( $stop_id, 0, true );
            $input_modules = Stop_Module::get_input_module_types();

            if ( ! empty( $modules ) ) {

                // Traverse modules
                foreach ($modules as $module_id) {

                    $module_type     = Stop_Module::get_module_type( $module_id );
                    $module_is_input = in_array( $module_type, $input_modules );

					// Only for input modules
					if ( $module_is_input ) {
						$module_meta = Stop_Module::get_module_meta( $module_id );
						self::refresh_module_completion($stop_id, $module_id, $module_type, $module_meta);
					}
				}
			}

        }

        public static function refresh_module_completion( $stop_id, $module_id, $module_type, $meta){
            $stop_object = new Stop( $stop_id );
            $stop = $stop_object->get_stop();
            $field_id = $stop->post_parent;

            $students = Field::get_field_students_ids( $field_id );

            foreach( $students as $idx => $student_id){
                // Did the student answer it?
                $response = call_user_func( $module_type . '::get_response', $student_id, $module_id, 'inherit', - 1, true );

                // Yes
                if ( ! empty( $response ) ) {

                    if ( 'yes' == $meta['mandatory_answer'] ) {
                        self::record_mandatory_answer( $student_id, $field_id, $stop_id, $module_id );
                        //cp_write_log( 'Record mandatory answer: Module: ' . $module_id );
                    }

                    if ( 'yes' == $meta['gradable_answer'] ) {
                        foreach ( $response as $answer ) {
                            $result = Stop_Module::get_response_grade( $answer );
                            if( 0 < $result['grade'] ) {
                                self::record_gradable_result( $student_id, $field_id, $stop_id, $module_id, $result['grade'] );
                            }
                            //cp_write_log( 'Record gradable result: Module: ' . $module_id . ' Result: ' . $result['grade'] );
                        }
                    }

                } // End responses
            }
        }

	}

    $cp_student_completion = new Student_Completion();

}