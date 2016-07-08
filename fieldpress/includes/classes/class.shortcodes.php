<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/*
  FieldPress Shortcodes
 */

if ( ! class_exists( 'FieldPress_Shortcodes' ) ) {

	class FieldPress_Shortcodes extends FieldPress {
		/* function FieldPress_Shortcodes() {
		  $this->__construct();
		  } */

		public static $instance = null;
		private $args = array();

		function __construct() {
			//register plugin shortcodes
			add_shortcode( 'field_instructors', array( &$this, 'field_instructors' ) );
			add_shortcode( 'fieldfield_media_instructor_avatar', array( &$this, 'field_instructor_avatar' ) );
			add_shortcode( 'field_instructor_avatar', array( &$this, 'field_instructor_avatar' ) );
			add_shortcode( 'instructor_profile_url', array( &$this, 'instructor_profile_url' ) );
			add_shortcode( 'field_details', array( &$this, 'field_details' ) );
			add_shortcode( 'fields_student_dashboard', array( &$this, 'fields_student_dashboard' ) );
			add_shortcode( 'fields_student_settings', array( &$this, 'fields_student_settings' ) );
			add_shortcode( 'student_registration_form', array( &$this, 'student_registration_form' ) );
			add_shortcode( 'fields_urls', array( &$this, 'fields_urls' ) );
			add_shortcode( 'field_stops', array( &$this, 'field_stops' ) );
			add_shortcode( 'field_stops_loop', array( &$this, 'field_stops_loop' ) );
			add_shortcode( 'field_notifications_loop', array( &$this, 'field_notifications_loop' ) );
			add_shortcode( 'fields_loop', array( &$this, 'fields_loop' ) );
			add_shortcode( 'field_discussion_loop', array( &$this, 'field_discussion_loop' ) );
			add_shortcode( 'field_stop_single', array( &$this, 'field_stop_single' ) );
			add_shortcode( 'field_stop_details', array( &$this, 'field_stop_details' ) );
			add_shortcode( 'field_stop_archive_submenu', array( &$this, 'field_stop_archive_submenu' ) );
			add_shortcode( 'field_breadcrumbs', array( &$this, 'field_breadcrumbs' ) );
			add_shortcode( 'field_discussion', array( &$this, 'field_discussion' ) );
			add_shortcode( 'get_parent_field_id', array( &$this, 'get_parent_field_id' ) );
			add_shortcode( 'stops_dropdown', array( &$this, 'stops_dropdown' ) );
			add_shortcode( 'field_list', array( &$this, 'field_list' ) );
			add_shortcode( 'field_calendar', array( &$this, 'field_calendar' ) );
			add_shortcode( 'field_featured', array( &$this, 'field_featured' ) );
			add_shortcode( 'field_stop', array( &$this, 'field_stop' ) );
			add_shortcode( 'module_status', array( &$this, 'module_status' ) );
			add_shortcode( 'student_workbook_table', array( &$this, 'student_workbook_table' ) );
			add_shortcode( 'field', array( &$this, 'field' ) );
			add_shortcode( 'field_stop_title', array( &$this, 'field_stop_title' ) );
			add_shortcode( 'field_stop_page_title', array( &$this, 'field_stop_page_title' ) );

			// Sub-shortcodes
			add_shortcode( 'field_title', array( &$this, 'field_title' ) );
			add_shortcode( 'field_link', array( &$this, 'field_link' ) );
			add_shortcode( 'field_summary', array( &$this, 'field_summary' ) );
			add_shortcode( 'field_description', array( &$this, 'field_description' ) );
			add_shortcode( 'field_start', array( &$this, 'field_start' ) );
			add_shortcode( 'field_end', array( &$this, 'field_end' ) );
			add_shortcode( 'field_dates', array( &$this, 'field_dates' ) );
			add_shortcode( 'field_enrollment_start', array( &$this, 'field_enrollment_start' ) );
			add_shortcode( 'field_enrollment_end', array( &$this, 'field_enrollment_end' ) );
			add_shortcode( 'field_enrollment_dates', array( &$this, 'field_enrollment_dates' ) );
			add_shortcode( 'field_enrollment_type', array( &$this, 'field_enrollment_type' ) );
			add_shortcode( 'field_class_size', array( &$this, 'field_class_size' ) );
			add_shortcode( 'field_cost', array( &$this, 'field_cost' ) );
			add_shortcode( 'field_language', array( &$this, 'field_language' ) );
			add_shortcode( 'field_category', array( &$this, 'field_category' ) );
			add_shortcode( 'field_list_image', array( &$this, 'field_list_image' ) );
			add_shortcode( 'field_featured_video', array( &$this, 'field_featured_video' ) );
			add_shortcode( 'field_join_button', array( &$this, 'field_join_button' ) );
			add_shortcode( 'field_thumbnail', array( &$this, 'field_thumbnail' ) );
			add_shortcode( 'field_media', array( &$this, 'field_media' ) );
			add_shortcode( 'field_action_links', array( &$this, 'field_action_links' ) );
			add_shortcode( 'field_random', array( &$this, 'field_random' ) );
			add_shortcode( 'field_time_estimation', array( $this, 'field_time_estimation' ) );
			// Field-progress
			add_shortcode( 'field_progress', array( &$this, 'field_progress' ) );
			add_shortcode( 'field_stop_progress', array( &$this, 'field_stop_progress' ) );
			add_shortcode( 'field_mandatory_message', array( &$this, 'field_mandatory_message' ) );
			add_shortcode( 'field_stop_percent', array( &$this, 'field_stop_percent' ) );
			// Other shortcodes
			//add_shortcode( 'stop_discussion', array( &$this, 'stop_discussion' ) );
			// Page Shortcodes
			add_shortcode( 'field_signup', array( &$this, 'field_signup' ) );
			add_shortcode( 'cp_pages', array( &$this, 'cp_pages' ) );

			$GLOBALS['stops_breadcrumbs'] = '';

			//Messaging shortcodes
			add_shortcode( 'messaging_submenu', array( &$this, 'messaging_submenu' ) );
		}

		/**
		 *
		 * Field Trip Details SHORTCODES
		 * =========================
		 *
		 */

		/**
		 * Creates a [field] shortcode.
		 *
		 * This is just a wrapper shortcode for several other shortcodes.
		 *
		 * @since 1.0.0
		 */
		function field( $atts ) {

			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'show'            => 'summary',
				'date_format'     => get_option( 'date_format' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'show_title'      => 'no',
			), $atts, 'field' ) );

			$field_id       = (int) $field_id;
			$show            = sanitize_text_field( $show );
			$date_format     = sanitize_text_field( $date_format );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$show_title      = sanitize_html_class( $show_title );

			$field = new Field( $field_id );

			// needs some more work...
			// $encoded = object_encode( $field );
			$encoded = false;

			$sections = explode( ',', $show );

			$content = '';

			foreach ( $sections as $section ) {
				$section = strtolower( $section );
				// [field_title]
				if ( 'title' == trim( $section ) && 'yes' == $show_title ) {
					$content .= do_shortcode( '[field_title title_tag="h3" field_id="' . $field_id . '" field_id="' . $field_id . '"]' );
				}

				// [field_summary]
				if ( 'summary' == trim( $section ) ) {
					$content .= do_shortcode( '[field_summary field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_description]
				if ( 'description' == trim( $section ) ) {
					$content .= do_shortcode( '[field_description field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_start]
				if ( 'start' == trim( $section ) ) {
					$content .= do_shortcode( '[field_start field="' . $encoded . '" date_format="' . $date_format . '" label_tag="' . $label_tag . '" label_delimeter="' . $label_delimeter . '" field_id="' . $field_id . '"]' );
				}

				// [field_end]
				if ( 'end' == trim( $section ) ) {
					$content .= do_shortcode( '[field_end field="' . $encoded . '" date_format="' . $date_format . '" label_tag="' . $label_tag . '" label_delimeter="' . $label_delimeter . '" field_id="' . $field_id . '"]' );
				}

				// [field_dates]
				if ( 'dates' == trim( $section ) ) {
					$content .= do_shortcode( '[field_dates field="' . $encoded . '" date_format="' . $date_format . '" label_tag="' . $label_tag . '" label_delimeter="' . $label_delimeter . '" field_id="' . $field_id . '"]' );
				}

				// [field_enrollment_start]
				if ( 'enrollment_start' == trim( $section ) ) {
					$content .= do_shortcode( '[field_enrollment_start field="' . $encoded . '" date_format="' . $date_format . '" label_tag="' . $label_tag . '" label_delimeter="' . $label_delimeter . '" field_id="' . $field_id . '"]' );
				}

				// [field_enrollment_end]
				if ( 'enrollment_end' == trim( $section ) ) {
					$content .= do_shortcode( '[field_enrollment_end field="' . $encoded . '" date_format="' . $date_format . '" label_tag="' . $label_tag . '" label_delimeter="' . $label_delimeter . '" field_id="' . $field_id . '"]' );
				}

				// [field_enrollment_dates]
				if ( 'enrollment_dates' == trim( $section ) ) {
					$content .= do_shortcode( '[field_enrollment_dates field="' . $encoded . '" date_format="' . $date_format . '" label_tag="' . $label_tag . '" label_delimeter="' . $label_delimeter . '" field_id="' . $field_id . '"]' );
				}

				// [field_summary]
				if ( 'class_size' == trim( $section ) ) {
					$content .= do_shortcode( '[field_class_size field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_cost]
				if ( 'cost' == trim( $section ) ) {
					$content .= do_shortcode( '[field_cost field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_language]
				if ( 'language' == trim( $section ) ) {
					$content .= do_shortcode( '[field_language field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_category]
				if ( 'category' == trim( $section ) ) {
					$content .= do_shortcode( '[field_category field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_enrollment_type]
				if ( 'enrollment_type' == trim( $section ) ) {
					$content .= do_shortcode( '[field_enrollment_type field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_instructors]
				if ( 'instructors' == trim( $section ) ) {
					$content .= do_shortcode( '[field_instructors field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_list_image]
				if ( 'image' == trim( $section ) ) {
					$content .= do_shortcode( '[field_list_image field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_featured_video]
				if ( 'video' == trim( $section ) ) {
					$content .= do_shortcode( '[field_featured_video field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_join_button]
				if ( 'button' == trim( $section ) ) {
					$content .= do_shortcode( '[field_join_button field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_thumbnail]
				if ( 'thumbnail' == trim( $section ) ) {
					$content .= do_shortcode( '[field_thumbnail field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_action_links]
				if ( 'action_links' == trim( $section ) ) {
					$content .= do_shortcode( '[field_action_links field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_media]
				if ( 'media' == trim( $section ) ) {
					$content .= do_shortcode( '[field_media field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}

				// [field_calendar]
				if ( 'calendar' == trim( $section ) ) {
					$content .= do_shortcode( '[field_calendar field="' . $encoded . '" field_id="' . $field_id . '"]' );
				}
			}

			return $content;
		}

		/**
		 * Shows the field trip title.
		 *
		 * @since 1.0.0
		 */
		function field_title( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'title_tag' => 'h3',
				'link'      => 'no',
				'class'     => '',
			), $atts, 'field_title' ) );

			$field_id = (int) $field_id;
			$title_tag = sanitize_html_class( $title_tag );
			$link      = sanitize_html_class( $link );
			$class     = sanitize_html_class( $class );

			$title = get_the_title( $field_id );

			$content = ! empty( $title_tag ) ? '<' . $title_tag . ' class="field-title field-title-' . $field_id . ' ' . $class . '">' : '';
			$content .= 'yes' == $link ? '<a href="' . get_permalink( $field_id ) . '" title="' . $title . '">' : '';
			$content .= $title;
			$content .= 'yes' == $link ? '</a>' : '';
			$content .= ! empty( $title_tag ) ? '</' . $title_tag . '>' : '';

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip title.
		 *
		 * @since 1.0.0
		 */
		function field_link( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'element'   => 'span',
				'class'     => '',
			), $atts, 'field_link' ) );

			$field_id = (int) $field_id;
			$element   = sanitize_html_class( $element );
			$class     = sanitize_html_class( $class );

			$title = get_the_title( $field_id );

			$content = do_shortcode( '[field_title field_id="' . $field_id . '" title_tag="' . $element . '" link="yes" class="' . $class . '"]' );

			return $content;
		}

		/**
		 * Shows the field trip summary/excerpt.
		 *
		 * @since 1.0.0
		 */
		function field_summary( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => '',
				'class'     => '',
				'length'    => ''
			), $atts, 'field_summary' ) );

			$field_id = (int) $field_id;
			$class     = sanitize_html_class( $class );
			$length    = (int) $length;
			$field    = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			ob_start();
			?>
			<div class="field-summary field-summary-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php
				if ( is_numeric( $length ) ) {
					echo cp_length( do_shortcode( $field->details->post_excerpt ), $length );
				} else {
					echo do_shortcode( $field->details->post_excerpt );
				}
				?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the Field Trip Description.
		 *
		 * @since 1.0.0
		 */
		function field_description( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => false,
				'class'     => '',
			), $atts, 'field_description' ) );

			$field_id = (int) $field_id;
			$class     = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			ob_start();
			?>
			<div class="field-description field-description-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php echo do_shortcode( $field->details->post_content ); ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip start date.
		 *
		 * @since 1.0.0
		 */
		function field_start( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'date_format'     => get_option( 'date_format' ),
				'label'           => __( 'Field Start Date: ', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'class'           => '',
			), $atts, 'field_start' ) );

			$field_id       = (int) $field_id;
			$date_format     = sanitize_text_field( $date_format );
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$start_date = get_post_meta( $field_id, 'field_start_date', true );
			$start_time = get_post_meta( $field_id, 'field_start_time', true );
			ob_start();
			?>
			<div class="field-start-date field-start-date-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
				<?php endif; ?>
			<?php echo cp_sp2nbsp( date_i18n( $date_format, strtotime( $start_date ) ) ); ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip end date.
		 *
		 * If the field trip has no end date, the no_date_text will be displayed instead of the date.
		 *
		 * @since 1.0.0
		 */
		function field_end( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'date_format'     => get_option( 'date_format' ),
				'label'           => __( 'Field End Date: ', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'no_date_text'    => __( 'No End Date', 'cp' ),
				'class'           => '',
			), $atts, 'field_end' ) );

			$field_id       = (int) $field_id;
			$date_format     = sanitize_text_field( $date_format );
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$no_date_text    = sanitize_text_field( $no_date_text );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$end_date   = get_post_meta( $field_id, 'field_end_date', true );
			$open_ended = 'off' == get_post_meta( $field_id, 'open_ended_field', true ) ? false : true;
			ob_start();
			?>
			<div class="field-end-date field-end-date-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
				<?php endif; ?>
			<?php echo $open_ended ? $no_date_text : cp_sp2nbsp( date_i18n( $date_format, strtotime( $end_date ) ) ); ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip start and end date.
		 *
		 * If the field trip has no end date, the no_date_text will be displayed instead of the date.
		 *
		 * @since 1.0.0
		 */
		function field_dates( $atts ) {
			extract( shortcode_atts( array(
				'field_id'        => in_the_loop() ? get_the_ID() : '',
				'field'           => false,
				'date_format'      => get_option( 'date_format' ),
				'label'            => __( 'Field Trip Date and Time: ', 'cp' ),
				'label_tag'        => 'strong',
				'label_delimeter'  => ':',
				'no_date_text'     => __( 'No End Date', 'cp' ),
				'alt_display_text' => __( 'Open-ended', 'cp' ),
				'show_alt_display' => 'no',
				'class'            => '',
			), $atts, 'field_dates' ) );

			$field_id        = (int) $field_id;
			$date_format      = sanitize_text_field( $date_format );
			$label            = sanitize_text_field( $label );
			$label_tag        = sanitize_html_class( $label_tag );
			$label_delimeter  = sanitize_html_class( $label_delimeter );
			$no_date_text     = sanitize_text_field( $no_date_text );
			$alt_display_text = sanitize_text_field( $alt_display_text );
			$show_alt_display = sanitize_html_class( $show_alt_display );
			$class            = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$start_date       = get_post_meta( $field_id, 'field_start_date', true );
			$end_date         = get_post_meta( $field_id, 'field_end_date', true );
			$start_time       = get_post_meta( $field_id, 'field_start_time', true );
            $end_time         = get_post_meta( $field_id, 'field_end_time', true );
			$open_ended       = 'off' == get_post_meta( $field_id, 'open_ended_field', true ) ? false : true;
			$end_output       = $open_ended ? $no_date_text : cp_sp2nbsp( date_i18n( $date_format, strtotime( $end_date ) ) );
			$show_alt_display = 'no' == $show_alt_display || 'false' == $show_alt_display ? false : $show_alt_display;

			ob_start();
			?>
			<div class="field-dates field-dates-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?><<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>><?php endif; ?>
			<?php if ( ( 'yes' == strtolower( $show_alt_display ) || $show_alt_display ) && $open_ended ) : ?><?php echo $alt_display_text; ?><?php else: ?><?php echo cp_sp2nbsp( date_i18n( $date_format, strtotime( $start_date ) ) ). ' '. $start_time.  ' - ' .  $end_output. ' '. $end_time; ?><?php endif; ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the enrollment start date.
		 *
		 * If it is an open ended enrollment the no_date_text will be displayed.
		 *
		 * @since 1.0.0
		 */
		function field_enrollment_start( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'date_format'     => get_option( 'date_format' ),
				'label'           => __( 'Enrollment Start Date: ', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'no_date_text'    => __( 'Enroll Anytime', 'cp' ),
				'class'           => '',
			), $atts, 'field_enrollment_start' ) );

			$field_id       = (int) $field_id;
			$date_format     = sanitize_text_field( $date_format );
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$no_date_text    = sanitize_text_field( $no_date_text );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$start_date = get_post_meta( $field_id, 'enrollment_start_date', true );
			$open_ended = 'off' == get_post_meta( $field_id, 'open_ended_enrollment', true ) ? false : true;
			ob_start();
			?>
			<div class="enrollment-start-date enrollment-start-date-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
				<?php endif; ?>
			<?php echo $open_ended ? $no_date_text : cp_sp2nbsp( date_i18n( $date_format, strtotime( $start_date ) ) ); ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the enrollment end date.
		 *
		 * By default this will not show for open ended enrollments.
		 * Set show_all_dates="yes" to make it display.
		 * If it is an open ended enrollment the no_date_text will be displayed.
		 *
		 * @since 1.0.0
		 */
		function field_enrollment_end( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'date_format'     => get_option( 'date_format' ),
				'label'           => __( 'Enrollment End Date: ', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'no_date_text'    => __( 'Enroll Anytime: ', 'cp' ),
				'show_all_dates'  => 'no',
				'class'           => '',
			), $atts, 'field_enrollment_end' ) );

			$field_id       = (int) $field_id;
			$date_format     = sanitize_text_field( $date_format );
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$no_date_text    = sanitize_text_field( $no_date_text );
			$show_all_dates  = sanitize_html_class( $show_all_dates );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$end_date   = get_post_meta( $field_id, 'enrollment_end_date', true );
			$open_ended = 'off' == get_post_meta( $field_id, 'open_ended_enrollment', true ) ? false : true;
			ob_start();
			?>
			<div class="enrollment-end-date enrollment-end-date-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
				<?php endif; ?>
			<?php echo $open_ended ? $no_date_text : cp_sp2nbsp( date_i18n( $date_format, strtotime( $end_date ) ) ); ?>
			</div>
			<?php
			$content = '';
			if ( ! $open_ended || 'yes' == $show_all_dates ) {
				$content = ob_get_clean();
			} else {
				ob_clean();
			}

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the enrollment start and end date.
		 *
		 * If it is an open ended enrollment the no_date_text will be displayed.
		 *
		 * @since 1.0.0
		 */
		function field_enrollment_dates( $atts ) {
			extract( shortcode_atts( array(
				'field_id'             => in_the_loop() ? get_the_ID() : '',
				'field'                => false,
				'date_format'           => get_option( 'date_format' ),
				'label'                 => __( 'Enrollment Dates: ', 'cp' ),
				'label_enrolled'        => __( 'You Enrolled on: ', 'cp' ),
				'show_enrolled_display' => 'yes',
				'label_tag'             => 'strong',
				'label_delimeter'       => ':',
				'no_date_text'          => __( 'Enroll Anytime', 'cp' ),
				'alt_display_text'      => __( 'Open-ended', 'cp' ),
				'show_alt_display'      => 'false',
				'class'                 => '',
			), $atts, 'field_enrollment_dates' ) );

			$field_id             = (int) $field_id;
			$date_format           = sanitize_text_field( $date_format );
			$label                 = sanitize_text_field( $label );
			$label_enrolled        = sanitize_text_field( $label_enrolled );
			$show_enrolled_display = sanitize_html_class( $show_enrolled_display );
			$label_tag             = sanitize_html_class( $label_tag );
			$label_delimeter       = sanitize_html_class( $label_delimeter );
			$no_date_text          = sanitize_text_field( $no_date_text );
			$alt_display_text      = sanitize_text_field( $alt_display_text );
			$show_alt_display      = sanitize_text_field( $show_alt_display );
			$class                 = sanitize_html_class( $class );

			$show_alt_display = 'true' == $show_alt_display ? true : false;

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );
			$class  = sanitize_html_class( $class );

			$start_date       = get_post_meta( $field_id, 'enrollment_start_date', true );
			$end_date         = get_post_meta( $field_id, 'enrollment_end_date', true );
			$open_ended       = 'off' == get_post_meta( $field_id, 'open_ended_enrollment', true ) ? false : true;
			$show_alt_display = 'no' == $show_alt_display || 'false' == $show_alt_display ? false : $show_alt_display;

			$is_enrolled = false;

			if ( 'yes' == strtolower( $show_enrolled_display ) ) {
				$student         = new Student( get_current_user_id() );
				$is_enrolled     = $student->has_access_to_field( $field_id );
				$enrollment_date = '';
				if ( $is_enrolled ) {
					$enrollment_date = get_user_option( 'enrolled_field_date_' . $field_id );
					$enrollment_date = date_i18n( $date_format, strtotime( $enrollment_date ) );
					$label           = $label_enrolled;
				}
			}

			ob_start();
			?>
			<div class="enrollment-dates enrollment-dates-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?><<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>><?php endif; ?>
			<?php
			if ( ! $is_enrolled ) {
				if ( ( 'yes' == strtolower( $show_alt_display ) || $show_alt_display ) && $open_ended ) :
					?>
					<?php echo $alt_display_text; ?><?php else: ?><?php echo $open_ended ? $no_date_text : cp_sp2nbsp( date_i18n( $date_format, strtotime( $start_date ) ) ) . ' - ' . cp_sp2nbsp( date_i18n( $date_format, strtotime( $end_date ) ) ); ?>
				<?php endif; ?>
			<?php
			} else {
				echo $enrollment_date;
			}
			?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip field trip group size.
		 *
		 * If there is no limit set on the field trip nothing will be displayed.
		 * You can make the no_limit_text display by setting show_no_limit="yes".
		 *
		 * By default it will show the remaining places,
		 * turn this off by setting show_remaining="no".
		 *
		 * @since 1.0.0
		 */
		function field_class_size( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'show_no_limit'   => 'no',
				'show_remaining'  => 'yes',
				'label'           => __( 'Field Trip Group Size: ', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'no_limit_text'   => __( 'Unlimited', 'cp' ),
				'remaining_text'  => __( '(%d places left)', 'cp' ),
				'class'           => '',
			), $atts, 'field_class_size' ) );

			$field_id       = (int) $field_id;
			$show_no_limit   = sanitize_html_class( $show_no_limit );
			$show_remaining  = sanitize_html_class( $show_remaining );
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$no_limit_text   = sanitize_text_field( $no_limit_text );
			$remaining_text  = sanitize_text_field( $remaining_text );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$content = '';

			$is_limited = get_post_meta( $field_id, 'limit_class_size', true ) == 'on' ? true : false;
			$class_size = (int) get_post_meta( $field_id, 'class_size', true );

			if ( $is_limited ) {
				$content .= $class_size;

				if ( 'yes' == $show_remaining ) {
					$remaining = $class_size - $field->get_number_of_students();
					$content .= ' ' . sprintf( $remaining_text, $remaining );
				}
			} else {
				if ( 'yes' == $show_no_limit ) {
					$content .= $no_limit_text;
				}
			}

			if ( ! empty( $content ) ) {
				ob_start();
				?>
				<div class="field-class-size field-class-size-<?php echo $field_id; ?> <?php echo $class; ?>">
					<?php if ( ! empty( $label ) ) : ?>
					<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
					<?php endif; ?>
				<?php echo $content; ?>
				</div>
				<?php
				$content = ob_get_clean();
			}

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip cost.
		 *
		 * @since 1.0.0
		 */
		function field_cost( $atts ) {
			global $fieldpress;

			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'label'           => __( 'Price:&nbsp;', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ': ',
				'no_cost_text'    => __( 'FREE', 'cp' ),
				'show_icon'       => 'false',
				'class'           => '',
			), $atts, 'field_cost' ) );

			$field_id       = (int) $field_id;
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$no_cost_text    = sanitize_text_field( $no_cost_text );
			$show_icon       = sanitize_text_field( $show_icon );
			$class           = sanitize_html_class( $class );

			$show_icon = 'true' == $show_icon ? true : false;

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );


			$is_paid = get_post_meta( $field_id, 'paid_field', true ) == 'on' ? true : false;

			$content = '';

			if ( cp_use_woo() ) {
				if ( $is_paid ) {

					$woo_product = get_post_meta( $field_id, 'woo_product', true );
					$wc_product  = new WC_Product( $woo_product );

					$content .= $wc_product->get_price_html();
				} else {
					if ( $show_icon ) {
						$content .= '<span class="mp_product_price">' . $no_cost_text . '</span>';
					} else {
						$content .= $no_cost_text;
					}
				}
			} else {
				if ( $is_paid && FieldPress::instance()->marketpress_active ) {

					$mp_product = get_post_meta( $field_id, 'marketpress_product', true );

					$content .= do_shortcode( '[mp_product_price product_id="' . $mp_product . '" label=""]' );
				} else {
					if ( $show_icon ) {
						$content .= '<span class="mp_product_price">' . $no_cost_text . '</span>';
					} else {
						$content .= $no_cost_text;
					}
				}
			}

			if ( ! empty( $content ) ) {
				ob_start();
				?>
				<div class="field-cost field-cost-<?php echo $field_id; ?> <?php echo $class; ?>">
					<?php if ( ! empty( $label ) ) : ?><<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>><?php endif; ?><?php echo $content; ?>
				</div>
				<?php
				$content = ob_get_clean();
			}

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the Field Trip Language.
		 *
		 * @since 1.0.0
		 */
		function field_language( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'label'           => __( 'Field Trip Language: ', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ':',
				'class'           => '',
			), $atts, 'field_language' ) );

			$field_id       = (int) $field_id;
			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );


			$language = get_post_meta( $field_id, 'field_language', true );
			ob_start();
			?>
			<?php if ( isset( $language ) && $language !== '' ) : ?>
				<div class="field-language field-language-<?php echo $field_id; ?> <?php echo $class; ?>">
					<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
					<?php endif; ?>
				<?php echo $language; ?>
				</div>
			<?php endif; ?>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the Field Trip Category.
		 *
		 * @since 1.0.0
		 */
		function field_category( $atts ) {
			extract( shortcode_atts( array(
				'field_id'        => in_the_loop() ? get_the_ID() : '',
				'field'           => false,
				'label'            => __( 'Field Trip Category: ', 'cp' ),
				'label_tag'        => 'strong',
				'label_delimeter'  => ':',
				'no_category_test' => __( 'None', 'cp' ),
				'class'            => '',
			), $atts, 'field_category' ) );

			$field_id        = (int) $field_id;
			$label            = sanitize_text_field( $label );
			$label_tag        = sanitize_html_class( $label_tag );
			$label_delimeter  = sanitize_html_class( $label_delimeter );
			$no_category_test = sanitize_text_field( $no_category_test );
			$class            = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$content = '';

			$categories = Field::get_categories( $field_id );
			foreach ( $categories as $key => $category ) {
				$content .= $category->name;
				$content .= count( $categories ) - 1 < $key ? ', ' : '';
			}
			// $category = get_category( $category );

			if ( ! $categories || 0 == count( $categories ) ) {
				$content .= $no_category_text;
			}

			ob_start();
			?>
			<div class="field-category field-category-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
				<?php endif; ?>
			<?php echo $content; ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the estimated field time.
		 *
		 * @since 1.0.0
		 */
		function field_time_estimation( $atts ) {
			$content = '';

			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'label'           => __( 'Estimated Duration:&nbsp;', 'cp' ),
				'label_tag'       => 'strong',
				'label_delimeter' => ': ',
				'wrapper'         => 'no',
				'class'           => '',
			), $atts, 'field_time_estimation' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			} else {
				return;
			}

			$label           = sanitize_text_field( $label );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$class           = sanitize_html_class( $class );
			$wrapper         = sanitize_text_field( $wrapper );

			// Convert text 'yes' into true.
			$wrapper = true === $wrapper || ( ! empty( $wrapper ) && 'yes' == $wrapper ) ? true : false;

			if ( $wrapper ) {
				$content .= '<div class="field-time-estimate field-time-estimate-' . $field_id . ' ' . $class . '">';
				if ( ! empty( $label ) ) {
					$content .= '<' . $label_tag . ' class="label">' . esc_html( $label ) . esc_html( $label_delimeter ) . '</' . $label_tag . '>';
				}
			}

			$content .= Field::get_field_time_estimation( $field_id );

			if ( $wrapper ) {
				$content .= '</div>';
			}


			return $content;
		}

		/**
		 * Shows a friendly field enrollment type message.
		 *
		 * @since 1.0.0
		 */
		function field_enrollment_type( $atts ) {
			extract( shortcode_atts( array(
				'field_id'         => in_the_loop() ? get_the_ID() : '',
				'field'            => false,
				'label'             => __( 'Who can Register: ', 'cp' ),
				'label_tag'         => 'strong',
				'label_delimeter'   => ':',
				'manual_text'       => __( 'Students are added by instructors.', 'cp' ),
				'prerequisite_text' => __( 'Students need to complete "%s" first.', 'cp' ),
				'passcode_text'     => __( 'A passcode is required to enroll.', 'cp' ),
				'anyone_text'       => __( 'Anyone', 'cp' ),
				'registered_text'   => __( 'Registered users', 'cp' ),
				'class'             => '',
			), $atts, 'field_enrollment_type' ) );

			$field_id         = (int) $field_id;
			$label             = sanitize_text_field( $label );
			$label_tag         = sanitize_html_class( $label_tag );
			$label_delimeter   = sanitize_html_class( $label_delimeter );
			$manual_text       = sanitize_text_field( $manual_text );
			$prerequisite_text = sanitize_text_field( $prerequisite_text );
			$passcode_text     = sanitize_text_field( $passcode_text );
			$anyone_text       = sanitize_text_field( $anyone_text );
			$registered_text   = sanitize_text_field( $registered_text );
			$class             = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$enrollment_type = get_post_meta( $field_id, 'enroll_type', true );

			$content = '';

			switch ( $enrollment_type ) {
				case 'anyone':
					$content = $anyone_text;
					break;
				case 'registered':
					$content = $registered_text;
					break;
				case 'passcode':
					$content = $passcode_text;
					break;
				case 'prerequisite':
					$prereq   = get_post_meta( $field_id, 'prerequisite', true );
					$pretitle = '<a href="' . get_permalink( $prereq ) . '">' . get_the_title( $prereq ) . '</a>';
					$content  = sprintf( $prerequisite_text, $pretitle );
					break;
				case 'manually':
					$content = $manual_text;
					break;
			}

			// For non-standard enrolment types.
			$content = apply_filters( 'fieldpress_field_enrollment_type_text', $content );

			ob_start();
			?>
			<div class="field-enrollment-type field-enrollment-type-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php if ( ! empty( $label ) ) : ?>
				<<?php echo $label_tag; ?> class="label"><?php echo $label ?><?php echo $label_delimeter; ?></<?php echo $label_tag; ?>>
				<?php endif; ?>
			<?php echo $content; ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip list image.
		 *
		 * @since 1.0.0
		 */
		function field_list_image( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => false,
				'width'     => 'default',
				'height'    => 'default',
				'class'     => '',
			), $atts, 'field_list_image' ) );

			$field_id = (int) $field_id;
			$width     = sanitize_html_class( $width );
			$height    = sanitize_html_class( $height );
			$class     = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$image_src = get_post_meta( $field_id, 'featured_url', true );

			if ( ! empty( $image_src ) ) {
				list( $img_w, $img_h ) = getimagesize( $image_src );

				// Note: by using both it usually reverts to the width
				$width  = 'default' == $width ? $img_w : $width;
				$height = 'default' == $height ? $img_h : $height;

				ob_start();
				?>
				<div class="field-list-image field-list-image-<?php echo $field_id; ?> <?php echo $class; ?>">
					<img width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="<?php echo $image_src; ?>" alt="<?php echo $field->details->post_title; ?>" title="<?php echo $field->details->post_title; ?>"/>
				</div>
				<?php
				$content = ob_get_clean();

				// Return the html in the buffer.
				return $content;
			}
		}

		/**
		 * Shows the field trip featured video.
		 *
		 * @since 1.0.0
		 */
		function field_featured_video( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => false,
				'width'     => 'default',
				'height'    => 'default',
				'class'     => '',
			), $atts, 'field_featured_video' ) );

			$field_id = (int) $field_id;
			$width     = sanitize_text_field( $width );
			$height    = sanitize_html_class( $height );
			$class     = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$video_src = get_post_meta( $field_id, 'field_video_url', true );

			$video_extension = pathinfo( $video_src, PATHINFO_EXTENSION );

			$content = '';

			if ( ! empty( $video_extension ) ) {//it's file, most likely on the server
				$attr = array(
					'src' => $video_src,
				);

				if ( 'default' != $width ) {
					$attr['width'] = $width;
				}

				if ( 'default' != $height ) {
					$attr['height'] = $height;
				}

				$content .= wp_video_shortcode( $attr );
			} else {

				$embed_args = array();

				if ( 'default' != $width ) {
					$embed_args['width'] = $width;
				}

				if ( 'default' != $height ) {
					$embed_args['height'] = $height;
				}

				$content .= wp_oembed_get( $video_src, $embed_args );
			}

			ob_start();
			?>
			<div class="field-featured-video field-featured-video-<?php echo $field_id; ?> <?php echo $class; ?>">
				<?php echo $content; ?>
			</div>
			<?php
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		/**
		 * Shows the field trip join button.
		 *
		 * @since 1.0.0
		 */
		function field_join_button( $atts ) {
			global $fieldpress;
			extract( shortcode_atts( array(
				'field_id'                => in_the_loop() ? get_the_ID() : '',
				'field'                   => false,
				'field_full_text'         => __( 'Field Trip Full', 'cp' ),
				'field_expired_text'      => __( 'Not available', 'cp' ),
				'enrollment_finished_text' => __( 'Registration Finished', 'cp' ),
				'enrollment_closed_text'   => __( 'Registration Closed', 'cp' ),
				'enroll_text'              => __( 'Register Now', 'cp' ),
				'signup_text'              => __( 'Signup!', 'cp' ),
				'details_text'             => __( 'Details', 'cp' ),
				'prerequisite_text'        => __( 'Pre-requisite Required', 'cp' ),
				'passcode_text'            => __( 'Passcode Required', 'cp' ),
				'not_started_text'         => __( 'Not Available', 'cp' ),
				'access_text'              => __( 'Start Field Trip', 'cp' ),
				'continue_learning_text'   => __( 'Continue Field Trip', 'cp' ),
				'list_page'                => false,
				'class'                    => '',
			), $atts, 'field_join_button' ) );

			$field_id = (int) $field_id;
			$list_page = sanitize_text_field( $list_page );
			$list_page = "true" == $list_page || 1 == (int) $list_page ? true : false;
			$class     = sanitize_html_class( $class );

			global $enrollment_process_url, $signup_url;

			// Saves some overhead by not loading the post again if we don't need to.
			$field  = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );
			$student = false;

			$field->enrollment_details();

			$button        = '';
			$button_option = '';
			$button_url    = $enrollment_process_url;
			$is_form       = false;

			$buttons = apply_filters( 'fieldpress_field_enrollment_button_options', array(
				'full'                => array(
					'label' => sanitize_text_field( $field_full_text ),
					'attr'  => array(
						'class' => 'apply-button apply-button-full ' . $class,
					),
					'type'  => 'label',
				),
				'expired'             => array(
					'label' => sanitize_text_field( $field_expired_text ),
					'attr'  => array(
						'class' => 'apply-button apply-button-finished ' . $class,
					),
					'type'  => 'label',
				),
				'enrollment_finished' => array(
					'label' => sanitize_text_field( $enrollment_finished_text ),
					'attr'  => array(
						'class' => 'apply-button apply-button-enrollment-finished ' . $class,
					),
					'type'  => 'label',
				),
				'enrollment_closed'   => array(
					'label' => sanitize_text_field( $enrollment_closed_text ),
					'attr'  => array(
						'class' => 'apply-button apply-button-enrollment-closed ' . $class,
					),
					'type'  => 'label',
				),
				'enroll'              => array(
					'label' => sanitize_text_field( $enroll_text ),
					'attr'  => array(
						'class'          => 'apply-button enroll ' . $class,
						'data-link-old'  => esc_url( $signup_url . '?field_id=' . $field_id ),
						'data-field-id' => $field_id,
					),
					'type'  => 'form_button',
				),
				'signup'              => array(
					'label' => sanitize_text_field( $signup_text ),
					'attr'  => array(
						'class'          => 'apply-button signup ' . $class,
						'data-link-old'  => esc_url( $signup_url . '?field_id=' . $field_id ),
						'data-field-id' => $field_id,
					),
					'type'  => 'form_button',
				),
				'details'             => array(
					'label' => sanitize_text_field( $details_text ),
					'attr'  => array(
						'class'     => 'apply-button apply-button-details ' . $class,
						'data-link' => esc_url( get_permalink( $field_id ) ),
					),
					'type'  => 'button',
				),
				'prerequisite'        => array(
					'label' => sanitize_text_field( $prerequisite_text ),
					'attr'  => array(
						'class' => 'apply-button apply-button-prerequisite ' . $class,
					),
					'type'  => 'label',
				),
				'passcode'            => array(
					'label'      => sanitize_text_field( $passcode_text ),
					'button_pre' => '<div class="passcode-box"><label>' . esc_html( $passcode_text ) . ' <input type="password" name="passcode" /></label></div>',
					'attr'       => array(
						'class' => 'apply-button apply-button-passcode ' . $class,
					),
					'type'       => 'form_submit',
				),
				'not_started'         => array(
					'label' => sanitize_text_field( $not_started_text ),
					'attr'  => array(
						'class' => 'apply-button apply-button-not-started  ' . $class,
					),
					'type'  => 'label',
				),
				'access'              => array(
					'label' => sanitize_text_field( $access_text ),
					'attr'  => array(
						'class'     => 'apply-button apply-button-enrolled apply-button-first-time ' . $class,
						'data-link' => esc_url( trailingslashit( get_permalink( $field_id ) ) . trailingslashit( FieldPress::instance()->get_stops_slug() ) ),
					),
					'type'  => 'button',
				),
				'continue'            => array(
					'label' => sanitize_text_field( $continue_learning_text ),
					'attr'  => array(
						'class'     => 'apply-button apply-button-enrolled ' . $class,
						'data-link' => esc_url( trailingslashit( get_permalink( $field_id ) ) . trailingslashit( FieldPress::instance()->get_stops_slug() ) ),
					),
					'type'  => 'button',
				),
			) );

			if ( is_user_logged_in() ) {
				$student           = new Student( get_current_user_id() );
				$student->enrolled = $student->user_enrolled_in_field( $field_id );
			}

			// Determine the button option
			if ( empty( $student ) || ! $student->enrolled ) {

				// For vistors and non-enrolled students
				if ( $field->full ) {
					// FIELD FULL
					$button_option = 'full';
				} elseif ( $field->field_expired && ! $field->open_ended_field ) {
					// FIELD EXPIRED
					$button_option = 'expired';
				} elseif ( ! $field->enrollment_started && ! $field->open_ended_enrollment && ! $field->enrollment_expired ) {
					// ENROLMENTS NOT STARTED (CLOSED)
					$button_option = 'enrollment_closed';
				} elseif ( $field->enrollment_expired && ! $field->open_ended_enrollment ) {
					// ENROLMENTS FINISHED
					$button_option = 'enrollment_finished';
				} elseif ( 'prerequisite' == $field->enroll_type ) {
					// PREREQUISITE REQUIRED
					if ( ! empty( $student ) ) {
						$pre_field   = ! empty( $field->prerequisite ) ? $field->prerequisite : false;
						$enrolled_pre = false;
						if ( $student->enroll_in_field( $field->prerequisite ) ) {
							$enrolled_pre = true;
						}

						if ( $enrolled_pre && ! empty( $pre_field ) && Student_Completion::is_field_complete( get_current_user_id(), (int) $pre_field ) ) {
							$button_option = 'enroll';
						} else {
							$button_option = 'prerequisite';
						}
					} else {
						$button_option = 'prerequisite';
					}
				}

				$user_can_register = cp_user_can_register();

				if ( empty( $student ) && $user_can_register && empty( $button_option ) ) {

					// If the user is allowed to signup, let them sign up
					$button_option = 'signup';
				} elseif ( ! empty( $student ) && empty( $button_option ) ) {

					// If the user is not enrolled, then see if they can enroll
					switch ( $field->enroll_type ) {
						case 'anyone':
						case 'registered':
							$button_option = 'enroll';
							break;
						case 'passcode':
							$button_option = 'passcode';
							break;
						case 'prerequisite':
							$pre_field   = ! empty( $field->prerequisite ) ? $field->prerequisite : false;
							$enrolled_pre = false;
							if ( $student->enroll_in_field( $field->prerequisite ) ) {
								//								$pre_field = new Field_Completion( $field->prerequisite );
								//								$pre_field->init_student_status();
								$enrolled_pre = true;
							}

							if ( $enrolled_pre && ! empty( $pre_field ) && Student_Completion::is_field_complete( get_current_user_id(), (int) $pre_field ) ) {
								//							if ( !empty( $pre_field ) && $pre_field->is_field_complete() ) {
								$button_option = 'enroll';
							} else {
								$button_option = 'prerequisite';
							}

							break;
					}
				}
			} else {


				// For already enrolled students.

				$progress = Student_Completion::calculate_field_completion( get_current_user_id(), $field_id, false );

				if ( $field->field_expired && ! $field->open_ended_field ) {
					// FIELD EXPIRED
					$button_option = 'expired';
				} elseif ( ! $field->field_started && ! $field->open_ended_field ) {
					// FIELD HASN'T STARTED
					$button_option = 'not_started';
				} elseif ( ! is_single() && false === strpos( $_SERVER['REQUEST_URI'], FieldPress::instance()->get_student_dashboard_slug() ) ) {
					// SHOW DETAILS | Dashboard
					$button_option = 'details';
				} else {
					if ( 0 < $progress ) {
						$button_option = 'continue';
					} else {
						$button_option = 'access';
					}
				}
			}

			// Make the option extendable
			$button_option = apply_filters( 'fieldpress_field_enrollment_button_option', $button_option );

			// Prepare the button
			if ( ( ! is_single() && ! is_page() ) || 'yes' == $list_page ) {
				$button_url = get_permalink( $field_id );
				$button     = '<button data-link="' . esc_url( $button_url ) . '" class="apply-button apply-button-details ' . esc_attr( $class ) . '">' . esc_html( $details_text ) . '</button>';
			} else {
				//$button = apply_filters( 'fieldpress_enroll_button_content', '', $field );
				if ( empty( $button_option ) || ( 'manually' == $field->enroll_type && ! ( 'access' == $button_option || 'continue' == $button_option ) ) ) {
					return apply_filters( 'fieldpress_enroll_button', $button, $field, $student );
				}

				$button_attributes = '';
				foreach ( $buttons[ $button_option ]['attr'] as $key => $value ) {
					$button_attributes .= $key . '="' . esc_attr( $value ) . '" ';
				}
				$button_pre  = isset( $buttons[ $button_option ]['button_pre'] ) ? $buttons[ $button_option ]['button_pre'] : '';
				$button_post = isset( $buttons[ $button_option ]['button_post'] ) ? $buttons[ $button_option ]['button_post'] : '';

				switch ( $buttons[ $button_option ]['type'] ) {
					case 'label':
						$button = '<span ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</span>';
						break;
					case 'form_button':
						$button  = '<button ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</button>';
						$is_form = true;
						break;
					case 'form_submit':
						$button  = '<input type="submit" ' . $button_attributes . ' value="' . esc_attr( $buttons[ $button_option ]['label'] ) . '" />';
						$is_form = true;
						break;
					case 'button':
						$button = '<button ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</button>';
						break;
				}

				$button = $button_pre . $button . $button_post;
			}

			// Wrap button in form if needed
			if ( $is_form ) {
				$button = '<form name="enrollment-process" method="post" action="' . $button_url . '">' . $button;
				$button .= wp_nonce_field( 'enrollment_process' );
				$button .= '<input type="hidden" name="field_id" value="' . $field_id . '" />';
				$button .= '</form>';
			}

			// Return button for rendering
			return apply_filters( 'fieldpress_enroll_button', $button, $field, $student );
		}

		/**
		 * Shows the field trip thumbnail.
		 *
		 * @since 1.0.0
		 */
		function field_thumbnail( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => false,
				'wrapper'   => 'figure',
				'class'     => '',
			), $atts, 'field_thumbnail' ) );

			$field_id = (int) $field_id;
			$wrapper   = sanitize_html_class( $wrapper );
			$class     = sanitize_html_class( $class );

			return do_shortcode( '[field_media field_id="' . $field_id . '" wrapper="' . $wrapper . '" class="' . $class . '" type="thumbnail"]' );
			/**
			 * @todo: Remove below redundant code
			 */
			//			// Saves some overhead by not loading the post again if we don't need to.
			//			$field	 = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );
			//			$class	 = sanitize_html_class( $class );
			//
			//			$thumbnail = Field::get_field_thumbnail( $field_id );
			//
			//			$content = '';
			//
			//			if ( !empty( $thumbnail ) ) {
			//				ob_start();
			//
			//				if ( !empty( $wrapper ) ) {
			//					$content = '<' . $wrapper . ' class="field-thumbnail field-thumbnail-' . $field_id . ' ' . $class . '">';
			//				}
			//
			?>
			<!--				<img src="--><?php //echo $thumbnail;              ?><!--" class="field-thumbnail-img"></img>-->
			<!--				--><?php
			//				$content .= trim( ob_get_clean() );
			//
			//				if ( !empty( $wrapper ) ) {
			//					$content .= '</' . $wrapper . '>';
			//				}
			//			}
			//
			//			return $content;
		}

		/**
		 * Shows the Field Trip Stop.
		 *
		 * @since 1.0.0
		 */
		function field_stop( $atts ) {
			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'free_text'       => __( 'Free', 'cp' ),
				'free_show'       => 'true',
				'show_title'      => 'no',
				'show_label'      => 'no',
				'label_delimeter' => ': ',
				'label_tag'       => 'h2',
				'show_divider'    => 'yes',
				'label'           => __( 'Field Trip Stop', 'cp' ),
				'class'           => '',
			), $atts, 'field_stop' ) );

			$field_id       = (int) $field_id;
			$free_text       = sanitize_text_field( $free_text );
			$free_show       = sanitize_text_field( $free_show );
			$free_show       = 'true' == $free_show ? true : false;
			$show_title      = sanitize_html_class( $show_title );
			$show_label      = sanitize_html_class( $show_label );
			$label_delimeter = sanitize_html_class( $label_delimeter );
			$label_tag       = sanitize_html_class( $label_tag );
			$show_divider    = sanitize_html_class( $show_divider );
			$label           = sanitize_text_field( $label );
			$class           = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field          = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );
			$class           = sanitize_html_class( $class );
			$label_tag       = sanitize_html_class( $label_tag );
			$label_delimeter = sanitize_html_class( $label_delimeter );

			if ( $field->details->field_stop_options == 'on' ) {
				$content = '';

				$student          = new Student( get_current_user_id() );
				$existing_student = $student->has_access_to_field( $field_id );

				$show_stop    = $field->details->show_stop_boxes;
				$preview_stop = $field->details->preview_stop_boxes;

				$show_page    = $field->details->show_page_boxes;
				$preview_page = $field->details->preview_page_boxes;

				$current_time      = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
				$field_start_date = $field->details->field_start_date;

                $enable_links = true;
				//$enable_links = false;
				//if ( $current_time >= $field_start_date ) {
				//	$enable_links = true;
				//}

				$stops = $field->get_stops();

				$content .= '<div class="field-stop-block field-stop-block-' . $field_id . '">';

				if ( ! empty( $label ) ) {
					$content .= '<' . $label_tag . ' class="label">' . $label . $label_delimeter . '</' . $label_tag . '>';
				}

				$content .= 'yes' == $show_title ? '<label>' . $this->details->post_title . '</label>' : '';

				if ( $stops ) {
					ob_start();
					?>
					<ul class="tree">
						<li>
							<ul>
								<?php
								foreach ( $stops as $stop ) {
									$stop_id = $stop['post']->ID;
									$stop_post = $stop['post'];
									$stop_class = new Stop( $stop_id );

									$stop_pagination = cp_stop_uses_new_pagination( $stop_id );

									if ( $stop_pagination ) {
										$stop_pages = fieldpress_stop_pages( $stop_id, $stop_pagination );
									} else {
										$stop_pages = fieldpress_stop_pages( $stop_id );
									}

									//$stop_pages	 = $stop_class->get_number_of_stop_pages();
									//									$modules = Stop_Module::get_modules( $stop_id );
									$stop_permalink = Stop::get_permalink( $stop_id );
									if ( isset( $show_stop[ $stop_id ] ) && $show_stop[ $stop_id ] == 'on' && $stop_post->post_status == 'publish' ) {
										?>
										<li>
											<label for="stop_<?php echo $stop_id; ?>" class="field_stop_stop_label <?php echo $existing_student ? 'single_column' : ''; ?>">
												<?php
												$title = '';
												if ( $existing_student && $enable_links ) {
													$title = '<a href="' . $stop_permalink . '">' . $stop_post->post_title . '</a>';
												} else {
													$title = $stop_post->post_title;
												}
												?>
												<div class="tree-stop-left"><?php echo $stop_post->post_title; ?></div>
												<div class="tree-stop-right">

													<?php if ( $field->details->field_stop_time_display == 'on' ) { ?>
														<span><?php echo $stop_class->get_stop_time_estimation( $stop_id ); ?></span>
													<?php } ?>

													<?php
													if ( isset( $preview_stop[ $stop_id ] ) && $preview_stop[ $stop_id ] == 'on' && $stop_permalink && ! $existing_student ) {
														?>
														<a href="<?php echo $stop_permalink; ?>?try" class="preview_option"><?php echo $free_text; ?></a>
													<?php } ?>
												</div>
											</label>

											<ul>
												<?php
												for ( $i = 1; $i <= $stop_pages; $i ++ ) {
													if ( isset( $show_page[ $stop_id . '_' . $i ] ) && $show_page[ $stop_id . '_' . $i ] == 'on' ) {
														?>
														<li class="field_stop_page_li <?php echo $existing_student ? 'single_column' : ''; ?>">
															<?php
															$pages_num  = 1;
															$page_title = $stop_class->get_stop_page_name( $i );
															?>

															<label for="page_<?php echo $stop_id . '_' . $i; ?>">
																<?php
																$title = '';
																if ( $existing_student && $enable_links ) {
																	$p_title = isset( $page_title ) && $page_title !== '' ? $page_title : __( 'Untitled Page', 'cp' );
																	$title   = '<a href="' . trailingslashit( $stop_permalink ) . trailingslashit( 'page' ) . trailingslashit( $i ) . '">' . $p_title . '</a>';
																} else {
																	$title = isset( $page_title ) && $page_title !== '' ? $page_title : __( 'Untitled Page', 'cp' );
																}
																?>

																<div class="tree-page-left">
																	<?php echo $title; ?>
																</div>
																<div class="tree-page-right">

																	<?php if ( $field->details->field_stop_time_display == 'on' ) { ?>
																		<span><?php echo $stop_class->get_stop_page_time_estimation( $stop_id, $i ); ?></span>
																	<?php } ?>

																	<?php
																	if ( isset( $preview_page[ $stop_id . '_' . $i ] ) && $preview_page[ $stop_id . '_' . $i ] == 'on' && $stop_permalink && ! $existing_student ) {
																		?>
																		<a href="<?php echo $stop_permalink; ?>page/<?php echo $i; ?>?try" class="preview_option"><?php echo $free_text; ?></a>
																	<?php } ?>

																</div>
															</label>

															<?php ?>
														</li>
													<?php
													}
												}//page visible
												?>

											</ul>
										</li>
									<?php
									}//stop visible
								} // foreach
								?>
							</ul>
						</li>
					</ul>

					<?php if ( $show_divider == 'yes' ) { ?>
						<div class="divider"></div>
					<?php } ?>

					<?php
					$content .= trim( ob_get_clean() );
				} else {

				}

				$content .= '</div>';

				return $content;
			}
		}

		/**
		 * Shows a featured field.
		 *
		 * @since 1.0.0
		 */
		function field_featured( $atts ) {
			extract( shortcode_atts( array(
				'field_id'      => '',
				'featured_title' => __( 'Featured Field Trip', 'cp' ),
				'button_title'   => __( 'Find out more.', 'cp' ),
				'media_type'     => '', // video, image, thumbnail
				'media_priority' => 'video', // video, image
				'class'          => '',
			), $atts, 'field_featured' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}
			$featured_title = sanitize_text_field( $featured_title );
			$button_title   = sanitize_text_field( $button_title );
			$media_type     = sanitize_text_field( $media_type );
			$media_priority = sanitize_text_field( $media_priority );
			$class          = sanitize_html_class( $class );

			$content = '';

			if ( ! empty( $field_id ) ) {
				$field = new Field( $field_id );
				$class  = sanitize_html_class( $class );

				ob_start();
				?>
				<div class="featured-field featured-field-<?php echo $field_id; ?>">
					<?php if ( ! empty( $featured_title ) ) : ?>
						<h2><?php echo $featured_title; ?></h2>
					<?php endif; ?>
					<h3 class="featured-field-title"><?php echo $field->details->post_title; ?></h3>
					<?php
					echo do_shortcode( '[field_media type="' . $media_type . '" priority="' . $media_priority . '" field_id="' . $field_id . '"]' );
					?>
					<div class="featured-field-summary">
						<?php echo do_shortcode( '[field_summary field_id="' . $field_id . '" length="30"]' ); ?>
					</div>

					<div class="featured-field-link">
						<button data-link="<?php echo esc_url( $field->get_permalink( $field_id ) ); ?>"><?php echo $button_title; ?></button>
					</div>
				</div>
				<?php
				$content .= trim( ob_get_clean() );
			}

			return $content;
		}

		/**
		 * Shows the field trip media (video or image).
		 *
		 * @since 1.0.0
		 */
		function field_media( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => false,
				'type'      => '', // default, video, image
				'priority'  => '', // gives priority to video (or image)
				'list_page' => 'no',
				'class'     => '',
				'wrapper'   => '',
			), $atts, 'field_media' ) );

			$field_id = (int) $field_id;
			$type      = sanitize_text_field( $type );
			$priority  = sanitize_text_field( $priority );
			$list_page = sanitize_html_class( $list_page );
			$class     = sanitize_html_class( $class );
			$wrapper   = sanitize_html_class( $wrapper );

			if ( 'yes' != $list_page ) {
				$type     = empty( $type ) ? get_option( 'details_media_type', 'default' ) : $type;
				$priority = empty( $priority ) ? get_option( 'details_media_priority', 'video' ) : $priority;
			} else {
				$type     = empty( $type ) ? get_option( 'listings_media_type', 'default' ) : $type;
				$priority = empty( $priority ) ? get_option( 'listings_media_priority', 'image' ) : $priority;
			}

			$priority = 'default' != $type ? false : $priority;

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );
			$class  = sanitize_html_class( $class );

			$field_video = get_post_meta( $field_id, 'field_video_url', true );
			$field_image = get_post_meta( $field_id, 'featured_url', true );

			$content = '';

			// If type is thumbnail, return early
			if ( 'thumbnail' == $type ) {
				//return do_shortcode( '[field_thumbnail]' );
				$type     = "image";
				$priority = "image";
			}

			if ( ( ( 'default' == $type && 'video' == $priority ) || 'video' == $type || ( 'default' == $type && 'image' == $priority && empty( $field_image ) ) ) && ! empty( $field_video ) ) {

				ob_start();
				?>

				<div class="video_player <?php echo 'field-featured-media field-featured-media-' . $field_id . ' ' . $class; ?>">
					<?php echo( $wrapper !== '' ? '<' . $wrapper . '>' : '' ); ?>
					<?php
					$video_extension = pathinfo( $field_video, PATHINFO_EXTENSION );

					if ( ! empty( $video_extension ) ) {//it's file, most likely on the server
						$attr = array(
							'src' => $field_video,
							//'width' => $data->player_width,
							//'height' => 550//$data->player_height,
						);
						echo wp_video_shortcode( $attr );
					} else {
						$embed_args = array(
							//'width' => $data->player_width,
							//'height' => 550
						);

						echo wp_oembed_get( $field_video );
					}
					?>
					<?php echo( $wrapper !== '' ? '</' . $wrapper . '>' : '' ); ?>
				</div>

				<?php
				$content .= trim( ob_get_clean() );
			}

			if ( ( ( 'default' == $type && 'image' == $priority ) || 'image' == $type || ( 'default' == $type && 'video' == $priority && empty( $field_video ) ) ) && ! empty( $field_image ) ) {
				$content .= '<div class="field-thumbnail field-featured-media field-featured-media-' . $field_id . ' ' . $class . '">';
				ob_start();
				?>
				<?php echo( $wrapper !== '' ? '<' . $wrapper . '>' : '' ); ?>
				<img src="<?php echo $field_image; ?>" class="field-media-img"></img>
				<?php echo( $wrapper !== '' ? '</' . $wrapper . '>' : '' ); ?>
				<?php
				$content .= trim( ob_get_clean() );
				$content .= '</div>';
			}

			return $content;
		}

		/**
		 * Shows the field trip action links.
		 *
		 * @since 1.0.0
		 */
		function field_action_links( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'field'    => false,
				'class'     => '',
			), $atts, 'field_action_links' ) );

			$field_id = (int) $field_id;
			$class     = sanitize_html_class( $class );

			// Saves some overhead by not loading the post again if we don't need to.
			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$field->field_start_date = get_post_meta( $field_id, 'field_start_date', true );
			$field->field_end_date   = get_post_meta( $field_id, 'field_end_date', true );
			$field->open_ended_field = 'off' == get_post_meta( $field_id, 'open_ended_field', true ) ? false : true;

			$withdraw_link_visible = false;

			$content = '';

			$student = new Student( get_current_user_id() );

			if ( $student && $student->user_enrolled_in_field( $field_id ) ) {
				//if ( ( ( strtotime( $field->field_start_date ) <= current_time( 'timestamp', 0 ) && strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) || ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) ) || $field->open_ended_field == 'on' ) {
				if ( ( ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) || ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) ) || $field->open_ended_field == 'on' ) {
					//field is currently active or is not yet active ( will be active in the future )
					$withdraw_link_visible = true;
				}
			}

			$content = '<div class="apply-links field-action-links field-action-links-' . $field_id . ' ' . $class . '">';

			if ( $withdraw_link_visible === true ) {
				$content .= '<a href="' . wp_nonce_url( '?withdraw=' . $field_id, 'withdraw_from_field_' . $field_id, 'field_nonce' ) . '" onClick="return withdraw();">' . __( 'Withdraw', 'cp' ) . '</a> | ';
			}
			$content .= '<a href="' . get_permalink( $field_id ) . '">' . __( 'Field Trip Details', 'cp' ) . '</a>';

			// Add certificate link
			if ( FieldPress_Capabilities::is_pro() ) {
				$content .= CP_Basic_Certificate::get_certificate_link( get_current_user_id(), $field_id, __( 'Certificate', 'cp' ), ' | ' );
			}

			$content .= '</div>';

			return $content;
		}

		function field_random( $atts ) {

			extract( shortcode_atts( array(
				'number'         => 3,
				'featured_title' => 'default',
				'button_title'   => 'default',
				'media_type'     => 'default',
				'media_priority' => 'default',
				'field_class'   => 'default',
				'class'          => '',
			), $atts, 'field_random' ) );

			$number         = (int) $number;
			$featured_title = sanitize_text_field( $featured_title );
			$button_title   = sanitize_text_field( $button_title );
			$media_type     = sanitize_html_class( $media_type );
			$media_priority = sanitize_html_class( $media_priority );
			$field_class   = sanitize_html_class( $field_class );
			$class          = sanitize_html_class( $class );

			$args = array(
				'post_type'      => 'field',
				'posts_per_page' => $number,
				'orderby'        => 'rand',
				'fields'         => 'ids',
			);

			$fields = new WP_Query( $args );
			$fields = $fields->posts;
			$class   = sanitize_html_class( $class );

			$content = 0 < count( $fields ) ? '<div class="field-random ' . $class . '">' : '';

			$featured_atts = '';

			if ( 'default' != $featured_title ) {
				$featured_atts .= 'featured_title="' . $featured_title . '" ';
			}
			if ( 'default' != $button_title ) {
				$featured_atts .= 'button_title="' . $button_title . '" ';
			}
			if ( 'default' != $media_type ) {
				$featured_atts .= 'media_type="' . $media_type . '" ';
			}
			if ( 'default' != $media_priority ) {
				$featured_atts .= 'media_priority="' . $media_priority . '" ';
			}
			if ( 'default' != $field_class ) {
				$featured_atts .= 'class="' . $field_class . '" ';
			}

			foreach ( $fields as $field ) {
				$content .= '<div class="field-item field-item-' . $field . '">';
				$content .= do_shortcode( '[field_featured field_id="' . $field . '" ' . $featured_atts . ']' );
				$content .= '</div>';
			}

			$content .= 0 < count( $fields ) ? '</div>' : '';

			return $content;
		}

		/**
		 * Shows the field trip calendar.
		 *
		 * @since 1.0.0
		 */
		function field_calendar( $atts ) {
			global $post;

			extract( shortcode_atts( array(
				'field_id'      => in_the_loop() ? get_the_ID() : false,
				'month'          => false,
				'year'           => false,
				'pre'            => __( 'Â« Previous', 'cp' ),
				'next'           => __( 'Next Â»', 'cp' ),
				'date_indicator' => 'indicator_light_block',
			), $atts, 'field_calendar' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}
			$month          = sanitize_text_field( $month );
			$month          = 'true' == $month ? true : false;
			$year           = sanitize_text_field( $year );
			$year           = 'true' == $year ? true : false;
			$pre            = sanitize_text_field( $pre );
			$next           = sanitize_text_field( $next );
			$date_indicator = sanitize_text_field( $date_indicator );

			if ( empty( $field_id ) ) {
				if ( $post && 'field' == $post->post_type ) {
					$field_id = $post->ID;
				} else {
					$parent_id = do_shortcode( '[get_parent_field_id]' );
					$field_id = 0 != $parent_id ? $parent_id : $field_id;
				}
			}

			$args = array();

			if ( ! empty( $month ) && ! empty( $year ) ) {
				$args = array( 'field_id' => $field_id, 'month' => $month, 'year' => $year );
			} else {
				$args = array( 'field_id' => $field_id );
			}

			$args['date_indicator'] = $date_indicator;

			$cal = new Field_Calendar( $args );

			return $cal->create_calendar( $pre, $next );
		}

		/**
		 * Shows the field trip list.
		 *
		 * @since 1.0.0
		 */
		function field_list( $atts ) {

			extract( shortcode_atts( array(
				'field_id'                 => '',
				'status'                    => 'publish',
				'instructor'                => '',
				// Note, one or the other
				'instructor_msg'            => __( 'The Instructor does not have any fields assigned yet.', 'cp' ),
				'student'                   => '',
				// If both student and instructor is specified only student will be used
				'student_msg'               => __( 'You have not yet enrolled in a field. Browse fields %s', 'cp' ),
				'two_column'                => 'yes',
				'title_column'              => 'none',
				'left_class'                => '',
				'right_class'               => '',
				'field_class'              => '',
				'title_link'                => 'yes',
				'title_class'               => 'field-title',
				'title_tag'                 => 'h3',
				'field_status'             => 'all',
				'list_wrapper_before'       => 'div',
				'list_wrapper_before_class' => 'field-list %s',
				'list_wrapper_after'        => 'div',
				'show'                      => 'dates,enrollment_dates,class_size,cost',
				'show_button'               => 'yes',
				'show_divider'              => 'yes',
				'show_media'                => 'false',
				'show_title'                => 'yes',
				'media_type'                => get_option( 'listings_media_type', 'image' ),
				// default, image, video
				'media_priority'            => get_option( 'listings_media_priority', 'image' ),
				// image, video
				'admin_links'               => 'false',
				'manage_link_title'         => __( 'Manage Field Trip', 'cp' ),
				'finished_link_title'       => __( 'View Field Trip', 'cp' ),
				'limit'                     => - 1,
				'order'                     => 'ASC',
				'class'                     => '',
			), $atts, 'field_list' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}
			$status              = sanitize_html_class( $status );
			$instructor          = sanitize_text_field( $instructor );
			$instructor_msg      = sanitize_text_field( $instructor_msg );
			$student             = sanitize_text_field( $student );
			$student_msg         = sanitize_text_field( $student_msg );
			$two_column          = sanitize_html_class( $two_column );
			$title_column        = sanitize_text_field( $title_column );
			$left_class          = sanitize_html_class( $left_class );
			$right_class         = sanitize_html_class( $right_class );
			$field_class        = sanitize_html_class( $field_class );
			$title_link          = sanitize_html_class( $title_link );
			$title_class         = sanitize_html_class( $title_class );
			$title_tag           = sanitize_html_class( $title_tag );
			$field_status       = sanitize_text_field( $field_status );
			$list_wrapper_before = sanitize_html_class( $list_wrapper_before );
			$list_wrapper_after  = sanitize_html_class( $list_wrapper_after );
			$show                = sanitize_text_field( $show );
			$show_button         = sanitize_html_class( $show_button );
			$show_divider        = sanitize_html_class( $show_divider );
			$show_title          = sanitize_html_class( $show_title );
			$show_media          = sanitize_html_class( $show_media );
			$media_type          = ! empty( $media_type ) ? sanitize_text_field( $media_type ) : 'image';
			$media_priority      = ! empty( $media_priority ) ? sanitize_text_field( $media_priority ) : 'image';
			$admin_links         = sanitize_text_field( $admin_links );
			$admin_links         = 'true' == $admin_links ? true : false;
			$manage_link_title   = sanitize_text_field( $manage_link_title );
			$finished_link_title = sanitize_text_field( $finished_link_title );
			$limit               = (int) $limit;
			$order               = sanitize_html_class( $order );
			$class               = sanitize_html_class( $class );

			$status = 'published' == $status ? 'publish' : $status;

			// student or instructor ids provided
			$user_provided = false;
			$user_provided = empty( $student ) ? empty( $instructor ) ? false : true : true;

			$content = '';
			$fields = array();

			if ( ! empty( $instructor ) ) {
				$include_ids = array();
				$instructors = explode( ',', $instructor );
				if ( ! empty( $instructors ) ) {
					foreach ( $instructors as $ins ) {
						$ins = (int) $ins;
						if ( $ins ) {
							$ins        = new Instructor( $ins );
							$field_ids = $ins->get_assigned_fields_ids( $status );
							if ( $field_ids ) {
								$include_ids = array_unique( array_merge( $include_ids, $field_ids ) );
							}
						}
					}
				} else {
					$instructor = (int) $instructor;
					if ( $instructor ) {
						$instructor = new Instructor( $ins );
						$field_ids = $instructor->get_assigned_fields_ids( $status );
						if ( $field_ids ) {
							$include_ids = array_unique( array_merge( $include_ids, $field_ids ) );
						}
					}
				}
			}

			if ( ! empty( $student ) ) {
				$include_ids = array();

				$students = explode( ',', $student );
				if ( ! empty( $students ) ) {
					foreach ( $students as $stud ) {
						$stud = (int) $stud;
						if ( $stud ) {
							$stud       = new Student( $stud );
							$field_ids = $stud->get_assigned_fields_ids( $status );
							if ( $field_ids ) {
								$include_ids = array_unique( array_merge( $include_ids, $field_ids ) );
							}
						}
					}
				} else {
					$student = (int) $student;
					if ( $student ) {
						$student    = new Student( $student );
						$field_ids = $student->get_assigned_fields_ids( $status );
						if ( $field_ids ) {
							$include_ids = array_unique( array_merge( $include_ids, $field_ids ) );
						}
					}
				}
			}

			$post_args = array(
				'order'          => $order,
				'post_type'      => 'field',
				'meta_key'       => 'enroll_type',
				'post_status'    => $status,
				'posts_per_page' => $limit
			);

			if ( ! empty( $include_ids ) ) {
				$post_args = wp_parse_args( array( 'include' => $include_ids ), $post_args );
			}


			if ( $user_provided && ! empty( $include_ids ) || ! $user_provided ) {
				$fields = get_posts( $post_args );
			}

			//<div class="field-list %s">
			$content .= 0 < count( $fields ) && ! empty( $list_wrapper_before ) ? '<' . $list_wrapper_before . ' class=' . $list_wrapper_before_class . '>' : '';

			foreach ( $fields as $field ) {

				if ( ! empty( $student ) && 'all' != strtolower( $field_status ) && ! is_array( $student ) ) {
					//					$completion			 = new Field_Completion( $field->ID );
					//					$completion->init_student_status( $student );
					$field->completed = Student_Completion::is_field_complete( $student, $field->ID );

					// Skip if we wanted a completed field but got an incomplete
					if ( 'completed' == strtolower( $field_status ) && ! $field->completed ) {
						continue;
					}
					// Skip if we wanted an incompleted field but got a completed
					if ( 'incomplete' == strtolower( $field_status ) && $field->completed ) {
						continue;
					}
				}

				$content .= '<div class="field-list-item ' . $field_class . '">';
				if ( 'yes' == $show_media ) {
					$content .= do_shortcode( '[field_media field_id="' . $field->ID . '" type="' . $media_type . '" priority="' . $media_priority . '"]' );
				}

				if ( 'none' == $title_column ) {
					$content .= do_shortcode( '[field_title field_id="' . $field->ID . '" link="' . $title_link . '" class="' . $title_class . '" title_tag="' . $title_tag . '"]' );
				}

				if ( 'yes' == $two_column ) {
					$content .= '<div class="field-list-box-left ' . $left_class . '">';
				}


				if ( 'left' == $title_column ) {
					$content .= do_shortcode( '[field_title field_id="' . $field->ID . '" link="' . $title_link . '" class="' . $title_class . '" title_tag="' . $title_tag . '"]' );
				}
				// One liner..
				$content .= do_shortcode( '[field show="' . $show . '" show_title="yes" field_id="' . $field->ID . '"]' );

				if ( 'yes' == $two_column ) {
					$content .= '</div>';
					$content .= '<div class="field-list-box-right ' . $right_class . '">';
				}

				if ( 'right' == $title_column ) {
					$content .= do_shortcode( '[field_title field_id="' . $field->ID . '" link="' . $title_link . '" class="' . $title_class . '" title_tag="' . $title_tag . '"]' );
				}

				if ( 'yes' == $show_button ) {
					if ( ! empty( $field->completed ) ) {
						$content .= do_shortcode( '[field_join_button field_id="' . $field->ID . '" continue_learning_text="' . $finished_link_title . '"]' );
					} else {
						$content .= do_shortcode( '[field_join_button field_id="' . $field->ID . '"]' );
					}
				}

				if ( $admin_links ) {
					$content .= '<button class="manage-field" data-link="' . admin_url( 'admin.php?page=field_details&field_id=' . $field->ID ) . '">' . $manage_link_title . '</button>';
				}

				// Add action links if student
				if ( ! empty( $student ) ) {
					$content .= do_shortcode( '[field_action_links field_id="' . $field->ID . '"]' );
				}

				if ( 'yes' == $two_column ) {
					$content .= '</div>';
				}

				if ( 'yes' == $show_divider ) {
					$content .= '<div class="divider" ></div>';
				}

				$content .= '</div>';  //field-list-item
			} // foreach

			if ( ( ! $fields || 0 == count( $fields ) ) && ! empty( $instructor ) ) {
				$content .= $instructor_msg;
			}

			if ( ( ! $fields || 0 == count( $fields ) ) && ! empty( $student ) ) {
				$content .= sprintf( $student_msg, '<a href="' . trailingslashit( home_url() . '/' . FieldPress::instance()->get_field_slug() ) . '">' . __( 'here', 'cp' ) . '</a>' );
			}

			// </div> field-list
			$content .= 0 < count( $fields ) && ! empty( $list_wrapper_before ) ? '</' . $list_wrapper_after . '>' : '';

			return $content;
		}

		/**
		 * FIELD PROGRESS SHORTCODES
		 *
		 */

		/**
		 * Field Progress
		 *
		 * @since 1.0.0
		 */
		function field_progress( $atts ) {
			extract( shortcode_atts( array(
				'field_id'      => in_the_loop() ? get_the_ID() : '',
				'decimal_places' => '0',
			), $atts, 'field_progress' ) );
			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}

			$decimal_places = sanitize_text_field( $decimal_places );
			//			$completion = new Field_Completion( $field_id );
			//			$completion->init_student_status();
			//			return $completion->field_progress();
			return number_format_i18n( Student_Completion::calculate_field_completion( get_current_user_id(), $field_id ), $decimal_places );
		}

		/**
		 * Field Trip Stop Progress
		 *
		 * @since 1.0.0
		 */
		function field_stop_progress( $atts ) {
			extract( shortcode_atts( array(
				'field_id'      => in_the_loop() ? get_the_ID() : '',
				'stop_id'        => false,
				'decimal_places' => '0',
			), $atts, 'field_stop_progress' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}
			$stop_id = (int) $stop_id;

			$decimal_places = sanitize_text_field( $decimal_places );

			//			$completion = new Field_Completion( $field_id );
			//			$completion->init_student_status();
			//			return $completion->stop_progress( $stop_id );
			$progress = number_format_i18n( Student_Completion::calculate_stop_completion( get_current_user_id(), $field_id, $stop_id ), $decimal_places );

			return $progress;
		}

		/**
		 * Field Mandatory Message
		 *
		 * x of y mandatory elements completed
		 *
		 * @since 1.0.0
		 */
		function field_mandatory_message( $atts ) {
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'stop_id'   => false,
				//'message'   => __( '%d of %d mandatory elements completed.', 'cp' ),
				'message'   => __( '', 'cp' ),
			), $atts, 'field_mandatory_message' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}

			$stop_id = (int) $stop_id;
			$message = sanitize_text_field( $message );

			//			$completion = new Field_Completion( $field_id );
			//			$completion->init_student_status();

			$mandatory_required = Student_Completion::get_mandatory_steps_required( $stop_id );

			if ( 0 == $mandatory_required ) {
				return false;
			}

			$mandatory_completed = Student_Completion::get_mandatory_steps_completed( get_current_user_id(), $field_id, $stop_id );

			//			return sprintf( $message, $completion->stop_completed_mandatory_steps( $stop_id ), $completion->stop_mandatory_steps( $stop_id ) );
			return sprintf( $message, $mandatory_completed, $mandatory_required );
		}

		function field_stop_percent( $atts ) {

			extract( shortcode_atts( array(
				'field_id'           => false,
				'stop_id'             => false,
				'format'              => false,
				'style'               => 'flat',
				'decimal_places'      => '0',
				'tooltip_alt'         => __( 'Percent of the stop completion', 'cp' ),
				'knob_fg_color'       => '#24bde6',
				'knob_bg_color'       => '#e0e6eb',
				'knob_data_thickness' => '.35',
				'knob_data_width'     => '70',
				'knob_data_height'    => '70',
			), $atts, 'field_stop_percent' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}

			$stop_id             = (int) $stop_id;
			$format              = sanitize_text_field( $format );
			$decimal_places      = sanitize_text_field( $decimal_places );
			$style               = sanitize_text_field( $style );
			$tooltip_alt         = sanitize_text_field( $tooltip_alt );
			$knob_fg_color       = sanitize_text_field( $knob_fg_color );
			$knob_bg_color       = sanitize_text_field( $knob_bg_color );
			$knob_data_thickness = sanitize_text_field( $knob_data_thickness );
			$knob_data_width     = (int) $knob_data_width;
			$knob_data_height    = (int) $knob_data_height;

			if ( empty( $stop_id ) || empty( $field_id ) ) {
				$percent_value = 0;
			} else {
				$percent_value = number_format_i18n( Student_Completion::calculate_stop_completion( get_current_user_id(), $field_id, $stop_id ), $decimal_places );
				$percent_value = $percent_value > 100 ? 100 : $percent_value;
			}

			$content = '';
			if ( $style == 'flat' ) {
				$content = '<span class="percentage">' . ( $format == 'true' ? $percent_value . '%' : $percent_value ) . '</span>';
			} elseif ( $style == 'none' ) {
				$content = $percent_value;
			} else {
				$content = '<a class="tooltip" alt="' . $tooltip_alt . '"><input class="knob" data-fgColor="' . $knob_fg_color . '" data-bgColor="' . $knob_bg_color . '" data-thickness="' . $knob_data_thickness . '" data-width="' . $knob_data_width . '" data-height="' . $knob_data_height . '" data-readOnly=true value="' . $percent_value . '"></a>';
			}

			return $content;
		}

		/**
		 *
		 * INSTRUCTOR DETAILS SHORTCODES
		 * =========================
		 *
		 */

		/**
		 * Shows all the instructors of the given field.
		 *
		 * Four styles are supported:
		 *
		 * * style="block" - List profile blocks including name, avatar, description (optional) and profile link. You can choose to make the entire block clickable ( link_all="yes" ) or only the profile link ( link_all="no", Default).
		 * * style="list"  - Lists instructor display names (separated by list_separator).
		 * * style="link"  - Same as 'list', but returns hyperlinks to instructor profiles.
		 * * style="count" - Outputs a simple integer value with the total of instructors for the field trip.
		 *
		 * @since 1.0.0
		 */
		function field_instructors( $atts ) {
			global $wp_query;
			global $instructor_profile_slug;

			extract( shortcode_atts( array(
				'field_id'       => in_the_loop() ? get_the_ID() : '',
				'field'          => false,
				'label'           => __( 'Instructor', 'cp' ),
				'label_plural'    => __( 'Instructors', 'cp' ),
				'label_delimeter' => ':&nbsp;',
				'label_tag'       => '',
				'count'           => false, // deprecated
				'list'            => false, // deprecated
				'link'            => 'false',
				'link_text'       => __( 'View Full Profile', 'cp' ),
				'show_label'      => 'no', // yes, no
				'summary_length'  => 50,
				'style'           => 'block', //list, list-flat, block, count
				'list_separator'  => ', ',
				'avatar_size'     => 80,
				'default_avatar'  => '',
				'show_divider'    => 'yes',
				'link_all'        => 'no',
				'class'           => '',
			), $atts, 'field_instructors' ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}
			$label           = sanitize_text_field( $label );
			$label_plural    = sanitize_text_field( $label_plural );
			$label_delimeter = sanitize_text_field( $label_delimeter );
			$label_tag       = sanitize_html_class( $label_tag );
			$link            = sanitize_text_field( $link );
			$link            = 'true' == $link ? true : false;
			$link_text       = sanitize_text_field( $link_text );
			$show_label      = sanitize_text_field( $show_label );
			$summary_length  = (int) $summary_length;
			$style           = sanitize_html_class( $style );
			$list_separator  = sanitize_text_field( $list_separator );
			$avatar_size     = (int) $avatar_size;
			$show_divider    = sanitize_html_class( $show_divider );
			$link_all        = sanitize_html_class( $link_all );
			$class           = sanitize_html_class( $class );

			// Support previous arguments
			$style = $count ? 'count' : $style;
			$style = $list ? 'list-flat' : $style;

			$show_label = 'list-flat' == $style && ! $show_label ? 'yes' : $show_label;

			$field = empty( $field ) ? new Field( $field_id ) : object_decode( $field, 'Field' );

			$instructors = Field::get_field_instructors( $field_id );
			$list        = array();
			$content     = '';

			if ( 0 < count( $instructors ) && 'yes' == $show_label ) {
				if ( ! empty( $label_tag ) ) {
					$content .= '<' . $label_tag . '>';
				}

				$content .= count( $instructors ) > 1 ? $label_plural . $label_delimeter : $label . $label_delimeter;

				if ( ! empty( $label_tag ) ) {
					$content .= '</' . $label_tag . '>';
				}
			}

			if ( 'count' != $style ) {
				if ( ! empty( $instructors ) ) {
					foreach ( $instructors as $instructor ) {

						$profile_href = trailingslashit( home_url() ) . trailingslashit( $instructor_profile_slug );
						$profile_href .= get_option( 'show_instructor_username', 1 ) == 1 ? trailingslashit( $instructor->user_login ) : trailingslashit( md5( $instructor->user_login ) );

						switch ( $style ) {

							case 'block':
								ob_start();
								?>
								<div class="instructor-profile <?php echo $class; ?>">
									<?php if ('yes' == $link_all) { ?>
									<a href="<?php echo $profile_href ?>">
										<?php } ?>
										<div class="profile-name"><?php echo $instructor->display_name; ?></div>
										<div class="profile-avatar">
											<?php echo get_avatar( $instructor->ID, $avatar_size, $default_avatar, $instructor->display_name ); ?>
										</div>
										<div class="profile-description"><?php echo $this->author_description_excerpt( $instructor->ID, $summary_length ); ?></div>
										<div class="profile-link">
											<?php if ('no' == $link_all) { ?>
											<a href="<?php echo $profile_href ?>">
												<?php } ?>
												<?php echo $link_text; ?>
												<?php if ('no' == $link_all) { ?>
												</a>
										<?php } ?>
										</div>
										<?php if ('yes' == $link_all) { ?>
										</a>
								<?php } ?>
								</div>
								<?php
								$content .= ob_get_clean();
								break;

							case 'link':
							case 'list':
							case 'list-flat':
								$list[] = ( $link ? '<a href="' . $profile_href . '">' . $instructor->display_name . '</a>' : $instructor->display_name );

								break;
						}
					}
				}
			}

			switch ( $style ) {

				case 'block':
					$content = '<div class="instructor-block ' . $class . '">' . $content . '</div>';
					if ( $show_divider == 'yes' && ( 0 < count( $instructors ) ) ) {
						$content .= '<div class="divider"></div>';
					}
					break;

				case 'list-flat':
					// $content = '';
					// if( 0 < count( $instructors ) && ! empty( $label ) ) {
					// 	$content = count( $instructors ) > 1 ? $label_plural . $label_delimeter : $label . $label_delimeter;
					// }
					$content .= implode( $list_separator, $list );
					$content = '<div class="instructor-list instructor-list-flat ' . $class . '">' . $content . '</div>';
					break;

				case 'list':
					// $content = '';
					// if( 0 < count( $instructors ) && ! empty( $label ) ) {
					// 	$content = count( $instructors ) > 1 ? $label_plural . $label_delimeter : $label . $label_delimeter;
					// }

					$content .= '<ul>';
					foreach ( $list as $instructor ) {
						$content .= '<li>' . $instructor . '</li>';
					}
					$content .= '</ul>';
					$content = '<div class="instructor-list ' . $class . '">' . $content . '</div>';
					break;

				case 'count':
					$content = count( $instructors );
					break;
			}

			return $content;
		}

		function field_instructor_avatar( $atts ) {
			global $wp_query;

			extract( shortcode_atts( array(
				'instructor_id' => 0,
				'thumb_size'    => 80,
				'class'         => 'small-circle-profile-image'
			), $atts ) );

			$instructor_id = (int) $instructor_id;
			$thumb_size    = (int) $thumb_size;
			$class         = sanitize_html_class( $class );

			$content = '';

			if ( get_avatar( $instructor_id, $thumb_size ) != '' ) {
				$doc = new DOMDocument();
				$doc->loadHTML( get_avatar( $instructor_id, $thumb_size ) );
				$imageTags = $doc->getElementsByTagName( 'img' );
				foreach ( $imageTags as $tag ) {
					$avatar_url = $tag->getAttribute( 'src' );
				}
				?>
				<?php
				$content .= '<div class="instructor-avatar">';
				$content .= '<div class="' . $class . '" style="background: url( ' . $avatar_url . ' );"></div>';
				$content .= '</div>';
			}

			return $content;
		}

		function instructor_profile_url( $atts ) {
			global $instructor_profile_slug;

			extract( shortcode_atts( array(
				'instructor_id' => 0
			), $atts ) );

			$instructor_id = (int) $instructor_id;

			$instructor = get_userdata( $instructor_id );

			if ( $instructor_id ) {
				if ( ( get_option( 'show_instructor_username', 1 ) == 1 ) ) {
					$username = trailingslashit( $instructor->user_login );
				} else {
					$username = trailingslashit( md5( $instructor->user_login ) );
				}

				return trailingslashit( home_url() ) . trailingslashit( $instructor_profile_slug ) . $username;
			}
		}

		/**
		 *
		 * MESSAGING PLUGIN SUBMENU SHORTCODE
		 * =========================
		 *
		 */
		function messaging_submenu( $atts ) {
			global $fieldpress;

			extract( shortcode_atts( array(), $atts ) );

			if ( isset( $fieldpress->inbox_subpage ) ) {
				$subpage = $fieldpress->inbox_subpage;
			} else {
				$subpage = '';
			}

			$unread_count = '';

			if ( get_option( 'show_messaging', 0 ) == 1 ) {
				$unread_count = cp_messaging_get_unread_messages_count();
				if ( $unread_count > 0 ) {
					$unread_count = ' (' . $unread_count . ')';
				} else {
					$unread_count = '';
				}
			}

			ob_start();
			?>

			<div class="submenu-main-container submenu-messaging">
				<ul id="submenu-main" class="submenu nav-submenu">
					<li class="submenu-item submenu-inbox <?php echo( isset( $subpage ) && $subpage == 'inbox' ? 'submenu-active' : '' ); ?>"><a href="<?php echo $fieldpress->get_inbox_slug( true ); ?>"><?php
							_e( 'Inbox', 'cp' );
							echo $unread_count;
							?></a></li>
					<li class="submenu-item submenu-sent-messages <?php echo( isset( $subpage ) && $subpage == 'sent_messages' ? 'submenu-active' : '' ); ?>"><a href="<?php echo $fieldpress->get_sent_messages_slug( true ); ?>"><?php _e( 'Sent', 'cp' ); ?></a></li>
					<li class="submenu-item submenu-new-message <?php echo( isset( $subpage ) && $subpage == 'new_message' ? 'submenu-active' : '' ); ?>"><a href="<?php echo $fieldpress->get_new_message_slug( true ); ?>"><?php _e( 'New Message', 'cp' ); ?></a></li>
				</ul><!--submenu-main-->
			</div><!--submenu-main-container-->
			<br clear="all"/>
			<?php
			$content = ob_get_clean();

			return $content;
		}

		/**
		 *
		 * STOP DETAILS SHORTCODES
		 * =========================
		 *
		 */
		function field_stop_archive_submenu( $atts ) {
			global $fieldpress;

			extract( shortcode_atts( array(
				'field_id' => ''
			), $atts ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}

			if ( $field_id == '' ) {
				$field_id = do_shortcode( '[get_parent_field_id]' );
			}

			if ( isset( $fieldpress->stops_archive_subpage ) ) {
				$subpage = $fieldpress->stops_archive_subpage;
			} else {
				$subpage = '';
			}
			ob_start();
			?>
			<div class="submenu-main-container">
				<ul id="submenu-main" class="submenu nav-submenu">
					<li class="submenu-item submenu-stops <?php echo( isset( $subpage ) && $subpage == 'stops' ? 'submenu-active' : '' ); ?>"><a href="<?php echo get_permalink( $field_id ) . $fieldpress->get_stops_slug(); ?>/"><?php _e( 'Stops', 'cp' ); ?></a></li>
					<li class="submenu-item submenu-notifications <?php echo( isset( $subpage ) && $subpage == 'notifications' ? 'submenu-active' : '' ); ?>"><a href="<?php echo get_permalink( $field_id ) . $fieldpress->get_notifications_slug(); ?>/"><?php _e( 'Notifications', 'cp' ); ?></a></li>
					<?php
					$pages = Field::get_allowed_pages( $field_id );

					if ( $pages['field_discussion'] == 'on' ) {
						?>
						<li class="submenu-item submenu-discussions <?php echo( isset( $subpage ) && $subpage == 'discussions' ? 'submenu-active' : '' ); ?>"><a href="<?php echo get_permalink( $field_id ) . $fieldpress->get_discussion_slug(); ?>/"><?php _e( 'Discussions', 'cp' ); ?></a></li>
					<?php
					}
					/* if ( $field->allow_field_grades_page == 'on' ) {
					  ?>
					  <li class="submenu-item submenu-grades <?php echo( isset( $subpage ) && $subpage == 'grades' ? 'submenu-active' : '' ); ?>"><a href="<?php echo get_permalink( $field_id ) . $fieldpress->get_grades_slug(); ?>/"><?php _e( 'Grades', 'cp' ); ?></a></li>
					  <?php
					  } */
					if ( $pages['workbook'] == 'on' ) {
						?>
						<li class="submenu-item submenu-workbook <?php echo( isset( $subpage ) && $subpage == 'workbook' ? 'submenu-active' : '' ); ?>"><a href="<?php echo get_permalink( $field_id ) . $fieldpress->get_workbook_slug(); ?>/"><?php _e( 'Workbook', 'cp' ); ?></a></li>
					<?php } ?>
					<li class="submenu-item submenu-info"><a href="<?php echo get_permalink( $field_id ); ?>"><?php _e( 'Field Trip Details', 'cp' ); ?></a></li>
					<?php
					$show_link = false;
					if ( FieldPress_Capabilities::is_pro() ) {
						$show_link = CP_Basic_Certificate::option( 'basic_certificate_enabled' );
						$show_link = ! empty( $show_link ) ? true : false;
					}
					if ( is_user_logged_in() && $show_link ) {

						if ( Student_Completion::is_field_complete( get_current_user_id(), $field_id ) ) {
							$certificate = CP_Basic_Certificate::get_certificate_link( get_current_user_id(), $field_id, __( 'Certificate', 'cp' ) );
							?>
							<li class="submenu-item submenu-certificate <?php echo( isset( $subpage ) && $subpage == 'certificate' ? 'submenu-active' : '' ); ?>"><?php echo $certificate; ?></li>
						<?php
						}
					}
					?>
				</ul><!--submenu-main-->
			</div><!--submenu-main-container-->
			<?php
			$content = ob_get_clean();

			return $content;
		}

		function fields_urls( $atts ) {
			global $enrollment_process_url, $signup_url;

			extract( shortcode_atts( array(
				'url' => ''
			), $atts ) );

			$url = esc_url_raw( $url );

			if ( $url == 'enrollment-process' ) {
				return $enrollment_process_url;
			}

			if ( $url == 'signup' ) {
				return $signup_url;
			}
		}

		function stops_dropdown( $atts ) {
			global $wp_query;
			extract( shortcode_atts( array(
				'field_id'       => ( isset( $wp_query->post->ID ) ? $wp_query->post->ID : 0 ),
				'include_general' => 'false',
				'general_title'   => ''
			), $atts ) );

			$field_id       = (int) $field_id;
			$include_general = sanitize_text_field( $include_general );
			$include_general = 'true' == $include_general ? true : false;
			$general_title   = sanitize_text_field( $general_title );

			$field_obj = new Field( $field_id );
			$stops      = $field_obj->get_stops();

			$dropdown = '<div class="stops_dropdown_holder"><select name="stops_dropdown" class="stops_dropdown">';
			if ( $include_general ) {
				if ( $general_title == '' ) {
					$general_title = __( '-- General --', 'cp' );
				}

				$dropdown .= '<option value="">' . esc_html( $general_title ) . '</option>';
			}
			foreach ( $stops as $stop ) {
				$dropdown .= '<option value="' . esc_attr( $stop['post']->ID ) . '">' . esc_html( $stop_post->post_title ) . '</option>';
			}
			$dropdown .= '</select></div>';

			return $dropdown;
		}

		function field_details( $atts ) {
			global $wp_query, $signup_url, $fieldpress;

			$student = new Student( get_current_user_id() );

			extract( shortcode_atts( array(
				'field_id' => ( isset( $wp_query->post->ID ) ? $wp_query->post->ID : 0 ),
				'field'     => 'field_start_date'
			), $atts ) );

			$field_id = (int) $field_id;
			$field     = sanitize_html_class( $field );

			$field_obj = new Field( $field_id );

			if ( $field_obj->is_open_ended() ) {
				$open_ended = true;
			} else {
				$open_ended = false;
			}

			$field = $field_obj->get_field();

			if ( $field == 'action_links' ) {

				$withdraw_link_visible = false;

				if ( $student->user_enrolled_in_field( $field_id ) ) {
					//if ( ( ( strtotime( $field->field_start_date ) <= current_time( 'timestamp', 0 ) && strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) || ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) ) || $field->open_ended_field == 'on' ) {//field is currently active or is not yet active ( will be active in the future )
					if ( ( ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) || ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) ) || $field->open_ended_field == 'on' ) {//field is currently active or is not yet active ( will be active in the future )
							$withdraw_link_visible = true;
					}
				}

				$field->action_links = '<div class="apply-links">';

				if ( $withdraw_link_visible === true ) {
					$field->action_links .= '<a href="?withdraw=' . $field->ID . '" onClick="return withdraw();">' . __( 'Withdraw', 'cp' ) . '</a> | ';
				}
				$field->action_links .= '<a href="' . get_permalink( $field->ID ) . '">' . __( 'Field Trip Details', 'cp' ) . '</a></div>';
			}

			if ( $field == 'class_size' ) {
				if ( $field->class_size == '0' || $field->class_size == '' ) {
					$field->class_size = __( 'Infinite', 'cp' );
				} else {
					$count_left         = $field->class_size - $field_obj->get_number_of_students();
					$field->class_size = $field->class_size . ' ' . sprintf( __( '( %d left )', 'cp' ), $count_left );
				}
			}

			$passcode_box_visible = false;

			if ( ! isset( $field->enroll_type ) ) {
				$field->enroll_type = 'anyone';
			} else {
				if ( $field->enroll_type == 'passcode' ) {
					$field->enroll_type  = __( 'Anyone with a Passcode', 'cp' );
					$passcode_box_visible = true;
				}

				if ( $field->enroll_type == 'prerequisite' ) {
					$field->init_enroll_type = 'prerequisite';
					$field->enroll_type      = sprintf( __( 'Anyone who attanded to the %1s', 'cp' ), '<a href="' . get_permalink( $field->prerequisite ) . '">' . __( 'prerequisite field', 'cp' ) . '</a>' ); //__( 'Anyone who attended to the ', 'cp' );
				}
			}

			if ( $field == 'enroll_type' ) {

				if ( $field->enroll_type == 'anyone' ) {
					$field->enroll_type = __( 'Anyone', 'cp' );
				}


				if ( $field->enroll_type == 'manually' ) {
					$field->enroll_type = __( 'Public enrollments are disabled', 'cp' );
				}
			}

			if ( $field == 'field_start_date' or $field == 'field_end_date' or $field == 'enrollment_start_date' or $field == 'enrollment_end_date' ) {
				$date_format = get_option( 'date_format' );
				if ( $field->open_ended_field == 'on' ) {
					$field->$field = __( 'Open-ended', 'cp' );
				} else {
					if ( $field->$field == '' ) {
						$field->$field = __( 'N/A', 'cp' );
					} else {
						$field->$field = cp_sp2nbsp( date_i18n( $date_format, strtotime( $field->$field ) ) );
					}
				}
			}

			if ( $field == 'price' ) {
				global $fieldpress;

				$is_paid = get_post_meta( $field_id, 'paid_field', true ) == 'on' ? true : false;

				if ( $is_paid && isset( $field->marketpress_product ) && $field->marketpress_product != '' && ( $fieldpress->marketpress_active ) ) {
					echo do_shortcode( '[mp_product_price product_id="' . $field->marketpress_product . '" label=""]' );
				} else {
					$field->price = __( 'FREE', 'cp' );
				}
			}

			if ( $field == 'button' ) {

				$field->button = '<form name="enrollment-process" method="post" action="' . do_shortcode( "[fields_urls url='enrollment-process']" ) . '">';

				if ( is_user_logged_in() ) {

					if ( ! $student->user_enrolled_in_field( $field_id ) ) {
						if ( ! $field_obj->is_populated() ) {
							if ( $field->enroll_type != 'manually' ) {
								if ( strtotime( $field->field_end_date ) + 86400 < current_time( 'timestamp', 0 ) && $field->open_ended_field == 'off' ) {//Field is no longer active
									$field->button .= '<span class="apply-button-finished">' . __( 'Finished', 'cp' ) . '</span>';
								} else {
									if ( ( $field->enrollment_start_date !== '' && $field->enrollment_end_date !== '' && strtotime( $field->enrollment_start_date ) <= current_time( 'timestamp', 0 ) && strtotime( $field->enrollment_end_date ) >= current_time( 'timestamp', 0 ) ) || $field->open_ended_field == 'on' ) {
										if ( ( $field->init_enroll_type == 'prerequisite' && $student->user_enrolled_in_field( $field->prerequisite ) ) || $field->init_enroll_type !== 'prerequisite' ) {
											$field->button .= '<input type="submit" class="apply-button" value="' . __( 'Register Now', 'cp' ) . '" />';
											$field->button .= '<div class="passcode-box">' . do_shortcode( '[field_details field="passcode_input"]' ) . '</div>';
										} else {
											$field->button .= '<span class="apply-button-finished">' . __( 'Prerequisite Required', 'cp' ) . '</span>';
										}
									} else {
										if ( strtotime( $field->enrollment_end_date ) <= current_time( 'timestamp', 0 ) ) {
											$field->button .= '<span class="apply-button-finished">' . __( 'Not available', 'cp' ) . '</span>';
										} else {
											$field->button .= '<span class="apply-button-finished">' . __( 'Not available', 'cp' ) . '</span>';
										}
									}
								}
							} else {
								//don't show any button because public enrollments are disabled with manuall enroll type
							}
						} else {
							$field->button .= '<span class="apply-button-finished">' . __( 'Populated', 'cp' ) . '</span>';
						}
					} else {
						if ( ( $field->field_start_date !== '' && $field->field_end_date !== '' ) || $field->open_ended_field == 'on' ) {//Field is currently active
							//if ( ( strtotime( $field->field_start_date ) <= current_time( 'timestamp', 0 ) && strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) || $field->open_ended_field == 'on' ) {//Field is currently active
							if ( ( strtotime( $field->field_end_date ) >= current_time( 'timestamp', 0 ) ) || $field->open_ended_field == 'on' ) {//Field is currently active
								$field->button .= '<a href="' . trailingslashit( get_permalink( $field->ID ) ) . trailingslashit( $fieldpress->get_stops_slug() ) . '" class="apply-button-enrolled">' . __( 'Go to Field Trip', 'cp' ) . '</a>';
							} else {

								//if ( strtotime( $field->field_start_date ) >= current_time( 'timestamp', 0 ) ) {//Waiting for a field to start
								//	$field->button .= '<span class="apply-button-pending">' . __( 'You are enrolled', 'cp' ) . '</span>';
								//}
								if ( strtotime( $field->field_end_date ) + 86400 < current_time( 'timestamp', 0 ) ) {//Field is no longer active
									$field->button .= '<span class="apply-button-finished">' . __( 'Finished', 'cp' ) . '</span>';
								}
							}
						} else {//Field is inactive or pending
							$field->button .= '<span class="apply-button-finished">' . __( 'Not availablse', 'cp' ) . '</span>';
						}
					}
				} else {

					if ( $field->enroll_type != 'manually' ) {
						if ( ! $field_obj->is_populated() ) {
							if ( ( strtotime( $field->field_end_date ) + 86400 < current_time( 'timestamp', 0 ) ) && $field->open_ended_field == 'off' ) {//Field is no longer active
								$field->button .= '<span class="apply-button-finished">' . __( 'Finished', 'cp' ) . '</span>';
							} else if ( ( $field->field_start_date == '' || $field->field_end_date == '' ) && $field->open_ended_field == 'off' ) {
								$field->button .= '<span class="apply-button-finished">' . __( 'Not available', 'cp' ) . '</span>';
							} else {


								if ( ( strtotime( $field->enrollment_end_date ) <= current_time( 'timestamp', 0 ) ) && $field->open_ended_field == 'off' ) {
									$field->button .= '<span class="apply-button-finished">' . __( 'Not available', 'cp' ) . '</span>';
								} else {
									$field->button .= '<a href="' . $signup_url . '?field_id=' . $field->ID . '" class="apply-button">' . __( 'Signup', 'cp' ) . '</a>';
								}
							}
						} else {
							$field->button .= '<span class="apply-button-finished">' . __( 'Populated', 'cp' ) . '</span>';
						}
					}
				}
				$field->button .= '<div class="clearfix"></div>';
				$field->button .= wp_nonce_field( 'enrollment_process' );
				$field->button .= '<input type="hidden" name="field_id" value="' . $field_id . '" />';
				$field->button .= '</form>';
			}

			if ( $field == 'passcode_input' ) {
				if ( $passcode_box_visible ) {
					$field->passcode_input = '<label>' . __( "Passcode: ", "cp" ) . '<input type="password" name="passcode" /></label>';
				}
			}

			if ( ! isset( $field->$field ) ) {
				$field->$field = '';
			}

			return $field->$field;
		}

		function get_parent_field_id( $atts ) {
			global $wp;

			//if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
			if ( is_array( $wp->query_vars ) && array_key_exists( 'fieldname', $wp->query_vars ) ) {
				$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
			} else {
				$field_id = 0;
			}

			return $field_id;
		}

		function fields_student_dashboard( $atts ) {
			global $plugin_dir;
			// load_template( $plugin_dir . 'includes/templates/student-dashboard.php', false );
			ob_start();
			require( $plugin_dir . 'includes/templates/student-dashboard.php' );
			$content = ob_get_clean();

			return $content;
		}

		function fields_student_settings( $atts ) {
			global $plugin_dir;
			// load_template( $plugin_dir . 'includes/templates/student-settings.php', false );
			ob_start();
			require( $plugin_dir . 'includes/templates/student-settings.php' );
			$content = ob_get_clean();

			return $content;
		}

		function field_stop_single( $atts ) {
			global $wp;

			extract( shortcode_atts( array( 'stop_id' => 0 ), $atts ) );

			$stop_id = (int) $stop_id;

			if ( empty( $stop_id ) ) {
				if ( array_key_exists( 'stopname', $wp->query_vars ) ) {
					$stop    = new Stop();
					$stop_id = $stop->get_stop_id_by_name( $wp->query_vars['stopname'] );
				} else {
					$stop_id = 0;
				}
			}

			//echo $stop_id;

			$args = array(
				'post_type'   => 'stop',
				//'post_id'		 => $stop_id,
				'post__in'    => array( $stop_id ),
				'post_status' => cp_can_see_stop_draft() ? 'any' : 'publish',
			);

			ob_start();
			query_posts( $args );
			ob_clean();
		}

		function field_stops_loop( $atts ) {
			global $wp;

			extract( shortcode_atts( array( 'field_id' => 0 ), $atts ) );

			$field_id = (int) $field_id;

			if ( empty( $field_id ) ) {
				if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
					$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
				} else {
					$field_id = 0;
				}
			}

			$current_date = date_i18n( 'Y-m-d', current_time( 'timestamp', 0 ) );

			$args = array(
				'order'          => 'ASC',
				'post_type'      => 'stop',
				'post_status'    => ( cp_can_see_stop_draft() ? 'any' : 'publish' ),
				'meta_key'       => 'stop_order',
				'orderby'        => 'meta_value_num',
				'posts_per_page' => '-1',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => 'field_id',
						'value' => $field_id
					),
				)
			);

			query_posts( $args );
		}

		function fields_loop( $atts ) {
			global $wp;
			if ( array_key_exists( 'field_category', $wp->query_vars ) ) {
				$page       = ( isset( $wp->query_vars['paged'] ) ) ? $wp->query_vars['paged'] : 1;
				$query_args = array(
					'post_type'   => 'field',
					'post_status' => 'publish',
					'paged'       => $page,
					'tax_query'   => array(
						array(
							'taxonomy' => 'field_category',
							'field'    => 'slug',
							'terms'    => array( $wp->query_vars['field_category'] ),
						)
					)
				);

				$selected_field_order_by_type = get_option( 'field_order_by_type', 'DESC' );
				$selected_field_order_by      = get_option( 'field_order_by', 'post_date' );

				if ( $selected_field_order_by == 'field_order' ) {
					$query_args['meta_key']   = 'field_order';
					$query_args['meta_query'] = array(
						'relation' => 'OR',
						array(
							'key'     => 'field_order',
							'compare' => 'NOT EXISTS'
						),
					);
					$query_args['orderby']    = 'meta_value';
					$query_args['order']      = $selected_field_order_by_type;
				} else {
					$query_args['orderby'] = $selected_field_order_by;
					$query_args['order']   = $selected_field_order_by_type;
				}

				query_posts( $query_args );
			}
		}

		function field_notifications_loop( $atts ) {
			global $wp;

			extract( shortcode_atts( array( 'field_id' => 0 ), $atts ) );

			$field_id = (int) $field_id;

			if ( empty( $field_id ) ) {
				if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
					$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
				} else {
					$field_id = 0;
				}
			}

			$args = array(
				'category'       => '',
				'order'          => 'ASC',
				'post_type'      => 'notifications',
				'post_mime_type' => '',
				'post_parent'    => '',
				'post_status'    => 'publish',
				'orderby'        => 'meta_value_num',
				'posts_per_page' => '-1',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'   => 'field_id',
						'value' => $field_id
					),
					array(
						'key'   => 'field_id',
						'value' => ''
					),
				)
			);

			query_posts( $args );
		}

		function field_discussion_loop( $atts ) {
			global $wp;

			extract( shortcode_atts( array( 'field_id' => 0 ), $atts ) );

			$field_id = (int) $field_id;

			if ( empty( $field_id ) ) {
				if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
					$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
				} else {
					$field_id = 0;
				}
			}

			$args = array(
				'order'          => 'DESC',
				'post_type'      => 'discussions',
				'post_mime_type' => '',
				'post_parent'    => '',
				'post_status'    => 'publish',
				'posts_per_page' => '-1',
				'meta_key'       => 'field_id',
				'meta_value'     => $field_id
			);

			query_posts( $args );
		}

		function field_stops( $atts ) {
			global $wp, $fieldpress;

			$content = '';

			extract( shortcode_atts( array( 'field_id' => $field_id ), $atts ) );

			if ( ! empty( $field_id ) ) {
				$field_id = (int) $field_id;
			}

			if ( empty( $field_id ) ) {
				if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
					$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
				} else {
					$field_id = 0;
				}
			}

			$field = new Field( $field_id );
			$stops  = $field->get_stops( $field_id, 'publish' );

			$user_id = get_current_user_id();
			$student = new Student( $user_id );
			//redirect to the parent field page if not enrolled
			if ( ! current_user_can( 'manage_options' ) ) {//If current user is not admin, check if he can access to the stops
				if ( $field->details->post_author != get_current_user_id() ) {//check if user is an author of a field ( probably instructor )
					if ( ! current_user_can( 'fieldpress_view_all_stops_cap' ) ) {//check if the instructor, even if it's not the author of the field trip, maybe has a capability given by the admin
						//if it's not an instructor who made the field trip, check if he is enrolled to field
						// Added 3rd parameter to deal with legacy meta data
						if ( ! $student->user_enrolled_in_field( $field_id, $user_id, 'update_meta' ) ) {
							// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
							//ob_start();
							wp_redirect( get_permalink( $field_id ) ); //if not, redirect him to the field trip page so he may enroll it if the enrollment is available
							exit;
						}
					}
				}
			}


			$content .= '<ol>';
			$last_stop_url = '';

			foreach ( $stops as $stop ) {
				$stop_id = $stop['post']->ID;
				$stop_post = $stop['post'];
				$content .= '<li><a href="' . Stop::get_permalink( $stop_id, $field_id ) . '">' . $stop_post->post_title . '</a></li>';
				$last_stop_url = Stop::get_permalink( $stop_id, $field_id );
			}

			$content .= '</ol>';

			if ( count( $stops ) >= 1 ) {
				$content .= do_shortcode( '[field_discussion]' );
			}

			if ( count( $stops ) == 0 ) {
				$content = __( '0 Field Trip Stops prepared yet. Please check back later.', 'cp' );
			}

			if ( count( $stops ) == 1 ) {
				//ob_start();
				// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
				wp_redirect( $last_stop_url );
				exit;
			}

			return $content;
		}

		function field_stop_details( $atts ) {
			global $post_id, $wp, $fieldpress;

			extract( shortcode_atts(
				apply_filters( 'shortcode_atts_field_stop_details', array(
					'stop_id'                         => 0,
					'field'                           => 'post_title',
					'format'                          => 'true',
					'additional'                      => '2',
					'style'                           => 'flat',
					'class'                           => 'field-name-content',
					'tooltip_alt'                     => __( 'Percent of the stop completion', 'cp' ),
					'knob_fg_color'                   => '#24bde6',
					'knob_bg_color'                   => '#e0e6eb',
					'knob_data_thickness'             => '.35',
					'knob_data_width'                 => '70',
					'knob_data_height'                => '70',
					'stop_title'                      => '',
					'stop_page_title_tag'             => 'h3',
					'stop_page_title_tag_class'       => '',
					'last_visited'                    => 'false',
					'parent_field_preceding_content' => __( 'Field: ', 'cp' ),
					'student_id'                      => get_current_user_ID(),
					'decimal_places'      			  => '0',
				) ), $atts ) );

			$stop_id                         = (int) $stop_id;
			$field                           = sanitize_html_class( $field );
			$format                          = sanitize_text_field( $format );
			$format                          = 'true' == $format ? true : false;
			$additional                      = sanitize_text_field( $additional );
			$style                           = sanitize_html_class( $style );
			$tooltip_alt                     = sanitize_text_field( $tooltip_alt );
			$knob_fg_color                   = sanitize_text_field( $knob_fg_color );
			$knob_bg_color                   = sanitize_text_field( $knob_bg_color );
			$knob_data_thickness             = sanitize_text_field( $knob_data_thickness );
			$knob_data_width                 = (int) $knob_data_width;
			$knob_data_height                = (int) $knob_data_height;
			$stop_title                      = sanitize_text_field( $stop_title );
			$stop_page_title_tag             = sanitize_html_class( $stop_page_title_tag );
			$stop_page_title_tag_class       = sanitize_html_class( $stop_page_title_tag_class );
			$parent_field_preceding_content = sanitize_text_field( $parent_field_preceding_content );
			$student_id                      = (int) $student_id;
			$last_visited                    = sanitize_text_field( $last_visited );
			$last_visited                    = 'true' == $last_visited ? true : false;
			$class                           = sanitize_html_class( $class );
			$decimal_places      			 = sanitize_text_field( $decimal_places );

			if ( $stop_id == 0 ) {
				$stop_id = get_the_ID();
			}

			$stop = new Stop( $stop_id );

			if( ! isset( $stop->details ) ) {
				$stop->details = new stdClass();
			}
			$student = new Student( get_current_user_id() );
			$class   = sanitize_html_class( $class );

			if ( $field == 'is_stop_available' ) {
				$stop->details->$field = Stop::is_stop_available( $stop_id );
			}

			if ( $field == 'stop_page_title' ) {
				$paged     = isset( $wp->query_vars['paged'] ) ? absint( $wp->query_vars['paged'] ) : 1;
				$page_name = $stop->get_stop_page_name( $paged );
				if ( $stop_title !== '' ) {
					$page_title_prepend = $stop_title . ': ';
				} else {
					$page_title_prepend = '';
				}

				$show_title_array = get_post_meta( $stop_id, 'show_page_title', true );
				$show_title       = false;
				if ( isset( $show_title_array[ $paged - 1 ] ) && 'yes' == $show_title_array[ $paged - 1 ] ) {
					$show_title = true;
				}

				if ( ! empty( $page_name ) && $show_title ) {
					$stop->details->$field = '<' . $stop_page_title_tag . '' . ( $stop_page_title_tag_class !== '' ? ' class="' . $stop_page_title_tag_class . '"' : '' ) . '>' . $page_title_prepend . $stop->get_stop_page_name( $paged ) . '</' . $stop_page_title_tag . '>';
				} else {
					$stop->details->$field = '';
				}

			}

			if ( $field == 'parent_field' ) {
				$field                = new Field( $stop->field_id );
				$stop->details->$field = $parent_field_preceding_content . '<a href="' . $field->get_permalink() . '" class="' . $class . '">' . $field->details->post_title . '</a>';
			}

			/* ------------ */

			$front_save_count = 0;

			$modules           = Stop_Module::get_modules( $stop_id );
			$mandatory_answers = 0;
			$mandatory         = 'no';

			foreach ( $modules as $mod ) {

				$mandatory = get_post_meta( $mod->ID, 'mandatory_answer', true );

				if ( $mandatory == 'yes' ) {
					$mandatory_answers ++;
				}

				$class_name = $mod->module_type;

				if ( class_exists( $class_name ) ) {
					if ( constant( $class_name . '::FRONT_SAVE' ) ) {
						$front_save_count ++;
					}
				}
			}

			$input_modules_count = $front_save_count;
			/* ------------ */
			//$input_modules_count = do_shortcode( '[field_stop_details field="input_modules_count" stop_id="' . $stop_id . '"]' );

			$responses_count = 0;

			$modules = Stop_Module::get_modules( $stop_id );
			foreach ( $modules as $module ) {
				if ( Stop_Module::did_student_respond( $module->ID, $student_id ) ) {
					$responses_count ++;
				}
			}
			$student_modules_responses_count = $responses_count;

			//$student_modules_responses_count = do_shortcode( '[field_stop_details field="student_module_responses" stop_id="' . $stop_id . '"]' );

			if ( $student_modules_responses_count > 0 ) {
				$percent_value = $mandatory_answers > 0 ? ( round( ( 100 / $mandatory_answers ) * $student_modules_responses_count, 0 ) ) : 0;
				$percent_value = ( $percent_value > 100 ? 100 : $percent_value ); //in case that student gave answers on all mandatory plus optional questions
			} else {
				$percent_value = 0;
			}

			if ( $input_modules_count == 0 ) {

				$grade              = 0;
				$front_save_count   = 0;
				$assessable_answers = 0;
				$responses          = 0;
				$graded             = 0;
				//$input_modules_count = do_shortcode( '[field_stop_details field="input_modules_count" stop_id="' . get_the_ID() . '"]' );
				$modules = Stop_Module::get_modules( $stop_id );

				if ( $input_modules_count > 0 ) {
					foreach ( $modules as $mod ) {

						$class_name = $mod->module_type;
						$assessable = get_post_meta( $mod->ID, 'gradable_answer', true );

						if ( class_exists( $class_name ) ) {

							if ( constant( $class_name . '::FRONT_SAVE' ) ) {

								if ( $assessable == 'yes' ) {
									$assessable_answers ++;
								}

								$front_save_count ++;
								$response = call_user_func( $class_name . '::get_response', $student_id, $mod->ID );

								if ( isset( $response->ID ) ) {
									$grade_data = Stop_Module::get_response_grade( $response->ID );
									$grade      = $grade + $grade_data['grade'];

									if ( get_post_meta( $response->ID, 'response_grade' ) ) {
										$graded ++;
									}

									$responses ++;
								}
							} else {
								//read only module
							}
						}
					}
					$percent_value = ( $format == true ? ( $responses == $graded && $responses == $front_save_count ? '<span class="grade-active">' : '<span class="grade-inactive">' ) . ( $grade > 0 ? round( ( $grade / $assessable_answers ), 0 ) : 0 ) . '</span>' : ( $grade > 0 ? round( ( $grade / $assessable_answers ), 0 ) : 0 ) );
				} else {
					$student = new Student( $student_id );
					if ( $student->is_stop_visited( $stop_id, $student_id ) ) {
						$grade         = 100;
						$percent_value = ( $format == true ? '<span class="grade-active">' . $grade . '</span>' : $grade );
					} else {
						$grade         = 0;
						$percent_value = ( $format == true ? '<span class="grade-inactive">' . $grade . '</span>' : $grade );
					}
				}

				//$percent_value = do_shortcode( '[field_stop_details field="student_stop_grade" stop_id="' . get_the_ID() . '"]' );
			}

			//redirect to the parent field page if not enrolled
			if ( ! current_user_can( 'manage_options' ) ) {
				if ( ! $fieldpress->check_access( $stop->field_id, $stop_id ) ) {
					// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
					//ob_start();
					wp_redirect( get_permalink( $stop->field_id ) );
					exit;
				}
			}

			if ( $field == 'percent' ) {

				//				$completion		 = new Field_Completion( $stop->field_id );
				//				$completion->init_student_status();
				//				$percent_value	 = $completion->stop_progress( $stop_id );
				$percent_value = number_format_i18n( Student_Completion::calculate_stop_completion( $student_id, $stop->field_id, $stop_id ), $decimal_places );
				$assessable_input_modules_count = do_shortcode( '[field_stop_details field="assessable_input_modules_count"]' );

				if ( $style == 'flat' ) {
					$stop->details->$field = '<span class="percentage">' . ( $format == true ? $percent_value . '%' : $percent_value ) . '</span>';
				} elseif ( $style == 'none' ) {
					$stop->details->$field = $percent_value;
				} else {
					$stop->details->$field = '<a class="tooltip" alt="' . $tooltip_alt . '"><input class="knob" data-fgColor="' . $knob_fg_color . '" data-bgColor="' . $knob_bg_color . '" data-thickness="' . $knob_data_thickness . '" data-width="' . $knob_data_width . '" data-height="' . $knob_data_height . '" data-readOnly=true value="' . $percent_value . '"></a>';
				}
			}

			if ( $field == 'permalink' ) {
				if ( $last_visited ) {
					$last_visited_page     = cp_get_last_visited_stop_page( $stop_id );
					$stop->details->$field = Stop::get_permalink( $stop_id, $stop->field_id ) . 'page/' . trailingslashit( $last_visited_page );
				} else {
					$stop->details->$field = Stop::get_permalink( $stop_id, $stop->field_id );
				}
			}

			if ( $field == 'input_modules_count' ) {
				$front_save_count = 0;

				$modules = Stop_Module::get_modules( $stop_id );

				foreach ( $modules as $mod ) {

					$class_name = $mod->module_type;

					if ( class_exists( $class_name ) ) {
						if ( constant( $class_name . '::FRONT_SAVE' ) ) {
							$front_save_count ++;
						}
					}
				}

				$stop->details->$field = $front_save_count;
			}

			if ( $field == 'mandatory_input_modules_count' ) {

				$front_save_count  = 0;
				$mandatory_answers = 0;

				$modules = Stop_Module::get_modules( $stop_id );

				foreach ( $modules as $mod ) {
					$mandatory_answer = get_post_meta( $mod->ID, 'mandatory_answer', true );

					$class_name = $mod->module_type;

					if ( class_exists( $class_name ) ) {
						if ( constant( $class_name . '::FRONT_SAVE' ) ) {
							if ( $mandatory_answer == 'yes' ) {
								$mandatory_answers ++;
							}
							//$front_save_count++;
						}
					}
				}

				$stop->details->$field = $mandatory_answers;
			}

			if ( $field == 'assessable_input_modules_count' ) {
				$front_save_count   = 0;
				$assessable_answers = 0;

				$modules = Stop_Module::get_modules( $stop_id );

				foreach ( $modules as $mod ) {
					$assessable = get_post_meta( $mod->ID, 'gradable_answer', true );

					$class_name = $mod->module_type;

					if ( class_exists( $class_name ) ) {
						if ( constant( $class_name . '::FRONT_SAVE' ) ) {
							if ( $assessable == 'yes' ) {
								$assessable_answers ++;
							}
							//$front_save_count++;
						}
					}
				}

				if ( isset( $stop->details->$field ) ) {
					$stop->details->$field = $assessable_answers;
				}
			}

			if ( $field == 'student_module_responses' ) {
				$responses_count   = 0;
				$mandatory_answers = 0;
				$modules           = Stop_Module::get_modules( $stop_id );
				foreach ( $modules as $module ) {

					$mandatory = get_post_meta( $module->ID, 'mandatory_answer', true );

					if ( $mandatory == 'yes' ) {
						$mandatory_answers ++;
					}

					if ( Stop_Module::did_student_respond( $module->ID, $student_id ) ) {
						$responses_count ++;
					}
				}

				if ( $additional == 'mandatory' ) {
					if ( $responses_count > $mandatory_answers ) {
						$stop->details->$field = $mandatory_answers;
					} else {
						$stop->details->$field = $responses_count;
					}
					//so we won't have 7 of 6 mandatory answered but mandatory number as a max number
				} else {
					$stop->details->$field = $responses_count;
				}
			}

			if ( $field == 'student_stop_grade' ) {
				$grade               = 0;
				$front_save_count    = 0;
				$responses           = 0;
				$graded              = 0;
				$input_modules_count = do_shortcode( '[field_stop_details field="input_modules_count" stop_id="' . get_the_ID() . '"]' );
				$modules             = Stop_Module::get_modules( $stop_id );
				$mandatory_answers   = 0;
				$assessable_answers  = 0;

				if ( $input_modules_count > 0 ) {
					foreach ( $modules as $mod ) {

						$class_name = $mod->module_type;

						if ( class_exists( $class_name ) ) {

							if ( constant( $class_name . '::FRONT_SAVE' ) ) {
								$front_save_count ++;
								$response   = call_user_func( $class_name . '::get_response', $student_id, $mod->ID );
								$assessable = get_post_meta( $mod->ID, 'gradable_answer', true );
								$mandatory  = get_post_meta( $mod->ID, 'mandatory_answer', true );


								if ( $assessable == 'yes' ) {
									$assessable_answers ++;
								}

								if ( isset( $response->ID ) ) {

									if ( $assessable == 'yes' ) {

										$grade_data = Stop_Module::get_response_grade( $response->ID );
										$grade      = $grade + $grade_data['grade'];

										if ( get_post_meta( $response->ID, 'response_grade' ) ) {
											$graded ++;
										}

										$responses ++;
									}
								}
							} else {
								//read only module
							}
						}
					}

					$stop->details->$field = ( $format == true ? ( $responses == $graded && $responses == $front_save_count ? '<span class="grade-active">' : '<span class="grade-inactive">' ) . ( $grade > 0 ? round( ( $grade / $assessable_answers ), 0 ) : 0 ) . '%</span>' : ( $grade > 0 ? round( ( $grade / $assessable_answers ), 0 ) : 0 ) );
				} else {
					$student = new Student( $student_id );
					if ( $student->is_stop_visited( $stop_id, $student_id ) ) {
						$grade                 = 100;
						$stop->details->$field = ( $format == true ? '<span class="grade-active">' . $grade . '%</span>' : $grade );
					} else {
						$grade                 = 0;
						$stop->details->$field = ( $format == true ? '<span class="grade-inactive">' . $grade . '%</span>' : $grade );
					}
				}
			}

			if ( $field == 'student_stop_modules_graded' ) {
				$grade            = 0;
				$front_save_count = 0;
				$responses        = 0;
				$graded           = 0;

				$modules = Stop_Module::get_modules( $stop_id );

				foreach ( $modules as $mod ) {

					$class_name = $mod->module_type;

					if ( class_exists( $class_name ) ) {

						if ( constant( $class_name . '::FRONT_SAVE' ) ) {
							$front_save_count ++;
							$response = call_user_func( $class_name . '::get_response', $student_id, $mod->ID );

							if ( isset( $response->ID ) ) {
								$grade_data = Stop_Module::get_response_grade( $response->ID );
								$grade      = $grade + $grade_data['grade'];

								if ( get_post_meta( $response->ID, 'response_grade' ) ) {
									$graded ++;
								}

								$responses ++;
							}
						} else {
							//read only module
						}
					}
				}

				$stop->details->$field = $graded;
			}

			if ( isset( $stop->details->$field ) ) {
				return $stop->details->$field;
			}
		}

		function field_breadcrumbs( $atts ) {
			global $field_slug, $stops_slug, $stops_breadcrumbs, $wp;

			extract( shortcode_atts( array(
				'type'      => 'stop_archive',
				'field_id' => 0,
				'position'  => 'shortcode'
			), $atts ) );

			$field_id = (int) $field_id;
			$type      = sanitize_html_class( $type );
			$position  = sanitize_html_class( $position );

			if ( empty( $field_id ) ) {
				if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
					$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
				} else {
					$field_id = 0;
				}
			}

			$field = new Field( $field_id );

			if ( $type == 'stop_archive' ) {
				$stops_breadcrumbs = '<div class="stops-breadcrumbs"><a href="' . trailingslashit( get_option( 'home' ) ) . $field_slug . '/">' . __( 'Field Trips', 'cp' ) . '</a> Â» <a href="' . $field->get_permalink() . '">' . $field->details->post_title . '</a></div>';
			}

			if ( $type == 'stop_single' ) {
				$stops_breadcrumbs = '<div class="stops-breadcrumbs"><a href="' . trailingslashit( get_option( 'home' ) ) . $field_slug . '/">' . __( 'Field Trips', 'cp' ) . '</a> Â» <a href="' . $field->get_permalink() . '">' . $field->details->post_title . '</a> Â» <a href="' . $field->get_permalink() . $stops_slug . '/">' . __( 'Stops', 'cp' ) . '</a></div>';
			}

			if ( $position == 'shortcode' ) {
				return $stops_breadcrumbs;
			}
		}

		function field_discussion( $atts ) {
			global $wp;

			if ( array_key_exists( 'fieldname', $wp->query_vars ) ) {
				$field_id = Field::get_field_id_by_name( $wp->query_vars['fieldname'] );
			} else {
				$field_id = 0;
			}

			$field = new Field( $field_id );

			if ( $field->details->allow_field_discussion == 'on' ) {

				$comments_args = array(
					// change the title of send button
					'label_submit'        => __( 'Send', 'cp' ),
					// change the title of the reply section
					'title_reply'         => __( 'Write a Reply or Comment', 'cp' ),
					// remove "Text or HTML to be displayed after the set of comment fields"
					'comment_notes_after' => '',
					// redefine your own textarea ( the comment body )
					'comment_field'       => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
				);

				$defaults = array(
					'author_email' => '',
					'ID'           => '',
					'karma'        => '',
					'number'       => '',
					'offset'       => '',
					'orderby'      => '',
					'order'        => 'DESC',
					'parent'       => '',
					'post_id'      => $field_id,
					'post_author'  => '',
					'post_name'    => '',
					'post_parent'  => '',
					'post_status'  => '',
					'post_type'    => '',
					'status'       => '',
					'type'         => '',
					'user_id'      => '',
					'search'       => '',
					'count'        => false,
					'meta_key'     => '',
					'meta_value'   => '',
					'meta_query'   => '',
				);

				$wp_list_comments_args = array(
					'walker'            => null,
					'max_depth'         => '',
					'style'             => 'ul',
					'callback'          => null,
					'end-callback'      => null,
					'type'              => 'all',
					'reply_text'        => __( 'Reply', 'cp' ),
					'page'              => '',
					'per_page'          => '',
					'avatar_size'       => 32,
					'reverse_top_level' => null,
					'reverse_children'  => '',
					'format'            => 'xhtml', //or html5 @since 3.6
					'short_ping'        => false // @since 3.6
				);

				comment_form( $comments_args = array(), $field_id );
				wp_list_comments( $wp_list_comments_args, get_comments( $defaults ) );
				//comments_template()
			}
		}

		function stop_discussion( $atts ) {
			global $wp;
			if ( array_key_exists( 'stopname', $wp->query_vars ) ) {
				$stop    = new Stop();
				$stop_id = $stop->get_stop_id_by_name( $wp->query_vars['stopname'] );
			} else {
				$stop_id = 0;
			}

			$comments_args = array(
				// change the title of send button
				'label_submit'        => 'Send',
				// change the title of the reply secpertion
				'title_reply'         => 'Write a Reply or Comment',
				// remove "Text or HTML to be displayed after the set of comment fields"
				'comment_notes_after' => '',
				// redefine your own textarea ( the comment body )
				'comment_field'       => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
			);
			ob_start();
			comment_form( $comments_args, $stop_id );
			$content = ob_get_clean();

			return $content;
		}

		function student_registration_form() {
			global $plugin_dir;
			// load_template( $plugin_dir . 'includes/templates/student-signup.php', true );
			ob_start();
			require( $plugin_dir . 'includes/templates/student-signup.php' );
			$content = ob_get_clean();

			return $content;
		}

		function author_description_excerpt( $user_id = false, $length = 100 ) {

			$excerpt = get_the_author_meta( 'description', $user_id );

			$excerpt        = strip_shortcodes( $excerpt );
			$excerpt        = apply_filters( 'the_content', $excerpt );
			$excerpt        = str_replace( ']]>', ']]&gt;', $excerpt );
			$excerpt        = strip_tags( $excerpt );
			$excerpt_length = apply_filters( 'excerpt_length', $length );
			$excerpt_more   = apply_filters( 'excerpt_more', ' ' . '[...]' );

			$words = preg_split( "/[\n\r\t ]+/", $excerpt, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
			if ( count( $words ) > $excerpt_length ) {
				array_pop( $words );
				$excerpt = implode( ' ', $words );
				$excerpt = $excerpt . $excerpt_more;
			} else {
				$excerpt = implode( ' ', $words );
			}

			return $excerpt;
		}

		/* =========== PAGES SHORTCODES =============== */

		function cp_pages( $atts ) {
			ob_start();
			global $plugin_dir;
			extract( shortcode_atts( array(
				'page' => '',
			), $atts ) );

			switch ( $page ) {
				case 'enrollment_process':
					require( $plugin_dir . 'includes/templates/enrollment-process.php' );
					break;

				case 'student_login':
					require( $plugin_dir . 'includes/templates/student-login.php' );
					break;

				case 'student_signup':
					require( $plugin_dir . 'includes/templates/student-signup.php' );
					break;

				case 'student_dashboard':
					require( $plugin_dir . 'includes/templates/student-dashboard.php' );
					break;

				case 'student_settings':
					require( $plugin_dir . 'includes/templates/student-settings.php' );
					break;

				default:
					_e( 'Page cannot be found', 'cp' );
			}

			$content = wpautop( ob_get_clean(), apply_filters( 'fieldpress_pages_content_preserve_line_breaks', true ) );

			return $content;
		}

		function field_signup( $atts ) {
			ob_start();
			$allowed = array( 'signup', 'login' );

			extract( shortcode_atts( array(
				'page'               => isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '',
				'failed_login_text'  => __( 'Invalid login.', 'cp' ),
				'failed_login_class' => 'red',
				'logout_url'         => '',
				'signup_tag'         => 'h3',
				'signup_title'       => __( 'Signup', 'cp' ),
				'login_tag'          => 'h3',
				'login_title'        => __( 'Login', 'cp' ),
				'signup_url'         => '',
				'login_url'          => '',
				'redirect_url'       => '', // redirect on successful login or signup
			), $atts, 'field_signup' ) );

			$failed_login_text  = sanitize_text_field( $failed_login_text );
			$failed_login_class = sanitize_html_class( $failed_login_class );
			$logout_url         = esc_url_raw( $logout_url );
			$signup_tag         = sanitize_html_class( $signup_tag );
			$signup_title       = sanitize_text_field( $signup_title );
			$login_tag          = sanitize_html_class( $login_tag );
			$login_title        = sanitize_text_field( $login_title );
			$signup_url         = esc_url_raw( $signup_url );
			$redirect_url       = esc_url_raw( $redirect_url );


			$page = in_array( $page, $allowed ) ? $page : 'signup';

			$signup_prefix = empty( $signup_url ) ? '&' : '?';
			$login_prefix  = empty( $login_url ) ? '&' : '?';
			$signup_url    = empty( $signup_url ) ? FieldPress::instance()->get_signup_slug( true ) : $signup_url;
			$login_url     = empty( $login_url ) ? FieldPress::instance()->get_login_slug( true ) : $login_url;

			if ( ! empty( $redirect_url ) ) {
				$signup_url = $signup_url . $signup_prefix . 'redirect_url=' . urlencode( $redirect_url );
				$login_url  = $login_url . $login_prefix . 'redirect_url=' . urlencode( $redirect_url );
			}
			if ( ! empty( $_POST['redirect_url'] ) ) {
				$signup_url = FieldPress::instance()->get_signup_slug( true ) . '?redirect_url=' . $_POST['redirect_url'];
				$login_url  = FieldPress::instance()->get_login_slug( true ) . '?redirect_url=' . $_POST['redirect_url'];
			}

			//Set a cookie now to see if they are supported by the browser.
			setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN );
			if ( SITECOOKIEPATH != COOKIEPATH ) {
				setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN );
			};

			//Set a redirect for the logout form
			if ( ! empty( $logout_url ) ) {
				update_option( 'cp_custom_login_url', $logout_url );
			}

			$form_message       = '';
			$form_message_class = '';
			// Attempt a login if submitted
			if ( isset( $_POST['log'] ) && isset( $_POST['pwd'] ) ) {

				$auth = wp_authenticate_username_password( null, sanitize_user( $_POST['log'] ), $_POST['pwd'] );
				if ( ! is_wp_error( $auth ) ) {
					// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
					$user    = get_user_by( 'login', $_POST['log'] );
					$user_id = $user->ID;
					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );
					if ( ! empty( $redirect_url ) ) {
						wp_redirect( urldecode( $redirect_url ) );
					} else {
						wp_redirect( FieldPress::instance()->get_student_dashboard_slug( true ) );
					}
					exit;
				} else {
					$form_message       = $failed_login_text;
					$form_message_class = $failed_login_class;
				}
			}

			switch ( $page ) {

				case 'signup':

					if ( ! is_user_logged_in() ) {
						if ( cp_user_can_register() ) {
							?>

							<?php
							$form_message_class = '';
							$form_message       = '';

							$student = new Student( 0 );

							if ( isset( $_POST['student-settings-submit'] ) ) {

								check_admin_referer( 'student_signup' );
								$min_password_length = apply_filters( 'fieldpress_min_password_length', 6 );

								$student_data = array();
								$form_errors  = 0;

								do_action( 'fieldpress_before_signup_validation' );

								if ( $_POST['username'] != '' && $_POST['first_name'] != '' && $_POST['last_name'] != '' && $_POST['email'] != '' && $_POST['password'] != '' && $_POST['password_confirmation'] != '' ) {

									if ( ! username_exists( $_POST['username'] ) ) {

										if ( ! email_exists( $_POST['email'] ) ) {

											if ( $_POST['password'] == $_POST['password_confirmation'] ) {

												if ( ! preg_match( "#[0-9]+#", $_POST['password'] ) || ! preg_match( "#[a-zA-Z]+#", $_POST['password'] ) || strlen( $_POST['password'] ) < $min_password_length ) {
													$form_message       = sprintf( __( 'Your password must be at least %d characters long and have at least one letter and one number in it.', 'cp' ), $min_password_length );
													$form_message_class = 'red';
													$form_errors ++;
												} else {

													if ( $_POST['password_confirmation'] ) {
														$student_data['user_pass'] = $_POST['password'];
													} else {
														$form_message       = __( "Passwords don't match", 'cp' );
														$form_message_class = 'red';
														$form_errors ++;
													}
												}
											} else {
												$form_message       = __( 'Passwords don\'t match', 'cp' );
												$form_message_class = 'red';
												$form_errors ++;
											}

											$student_data['role']       = 'student';
											$student_data['user_login'] = $_POST['username'];
											$student_data['user_email'] = $_POST['email'];
											$student_data['first_name'] = $_POST['first_name'];
											$student_data['last_name']  = $_POST['last_name'];

											if ( ! is_email( $_POST['email'] ) ) {
												$form_message       = __( 'E-mail address is not valid.', 'cp' );
												$form_message_class = 'red';
												$form_errors ++;
											}

											if ( isset( $_POST['tos_agree'] ) ) {
												if ( $_POST['tos_agree'] == '0' ) {
													$form_message       = __( 'You must agree to the Terms of Service in order to signup.', 'cp' );
													$form_message_class = 'red';
													$form_errors ++;
												}
											}

											if ( $form_errors == 0 ) {
												if ( $student_id = $student->add_student( $student_data ) !== 0 ) {
													//$form_message = __( 'Account created successfully! You may now <a href="' . ( get_option( 'use_custom_login_form', 1 ) ? trailingslashit( site_url() . '/' . $this->get_login_slug() ) : wp_login_url() ) . '">log into your account</a>.', 'cp' );
													//$form_message_class = 'regular';
													$email_args['email_type']         = 'student_registration';
													$email_args['student_id']         = $student_id;
													$email_args['student_email']      = $student_data['user_email'];
													$email_args['student_first_name'] = $student_data['first_name'];
													$email_args['student_last_name']  = $student_data['last_name'];
													$email_args['student_username']   = $student_data['user_login'];
													$email_args['student_password']   = $student_data['user_pass'];

													fieldpress_send_email( $email_args );

													$creds                  = array();
													$creds['user_login']    = $student_data['user_login'];
													$creds['user_password'] = $student_data['user_pass'];
													$creds['remember']      = true;
													$user                   = wp_signon( $creds, false );

													if ( is_wp_error( $user ) ) {
														$form_message       = $user->get_error_message();
														$form_message_class = 'red';
													}

													// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
													if ( isset( $_POST['field_id'] ) && is_numeric( $_POST['field_id'] ) ) {
														$field = new Field( $_POST['field_id'] );
														wp_redirect( $field->get_permalink() );
													} else {
														if ( ! empty( $redirect_url ) ) {
															wp_redirect( apply_filters( 'fieldpress_redirect_after_signup_redirect_url', $redirect_url ) );
														} else {
															wp_redirect( apply_filters( 'fieldpress_redirect_after_signup_url', FieldPress::instance()->get_student_dashboard_slug( true ) ) );
														}
													}
													exit;
												} else {
													$form_message       = __( 'An error occurred while creating the account. Please check the form and try again.', 'cp' );
													$form_message_class = 'red';
												}
											}
										} else {
											$form_message       = __( 'Sorry, that email address is already used!', 'cp' );
											$form_message_class = 'error';
										}
									} else {
										$form_message       = __( 'Username already exists. Please choose another one.', 'cp' );
										$form_message_class = 'red';
									}
								} else {
									$form_message       = __( 'All fields are required.', 'cp' );
									$form_message_class = 'red';
								}
							} else {
								$form_message = __( 'All fields are required.', 'cp' );
							}
							?>
							<?php
							//ob_start();
							if ( ! empty( $signup_title ) ) {
								echo '<' . $signup_tag . '>' . $signup_title . '</' . $signup_tag . '>';
							}
							?>

							<p class="form-info-<?php echo apply_filters( 'signup_form_message_class', sanitize_text_field( $form_message_class ) ); ?>"><?php echo apply_filters( 'signup_form_message', sanitize_text_field( $form_message ) ); ?></p>

							<?php do_action( 'fieldpress_before_signup_form' ); ?>

							<form id="student-settings" name="student-settings" method="post" class="student-settings">

								<?php do_action( 'fieldpress_before_all_signup_fields' ); ?>

								<input type="hidden" name="field_id" value="<?php esc_attr_e( isset( $_GET['field_id'] ) ? $_GET['field_id'] : ' ' ); ?>"/>
								<input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>"/>

								<label>
									<?php _e( 'First Name', 'cp' ); ?>:
									<input type="text" name="first_name" value="<?php echo( isset( $_POST['first_name'] ) ? $_POST['first_name'] : '' ); ?>"/>
								</label>

								<?php do_action( 'fieldpress_after_signup_first_name' ); ?>

								<label>
									<?php _e( 'Last Name', 'cp' ); ?>:
									<input type="text" name="last_name" value="<?php echo( isset( $_POST['last_name'] ) ? $_POST['last_name'] : '' ); ?>"/>
								</label>

								<?php do_action( 'fieldpress_after_signup_last_name' ); ?>

								<label>
									<?php _e( 'Username', 'cp' ); ?>:
									<input type="text" name="username" value="<?php echo( isset( $_POST['username'] ) ? $_POST['username'] : '' ); ?>"/>
								</label>

								<?php do_action( 'fieldpress_after_signup_username' ); ?>

								<label>
									<?php _e( 'E-mail', 'cp' ); ?>:
									<input type="text" name="email" value="<?php echo( isset( $_POST['email'] ) ? $_POST['email'] : '' ); ?>"/>
								</label>

								<?php do_action( 'fieldpress_after_signup_email' ); ?>

								<label>
									<?php _e( 'Password', 'cp' ); ?>:
									<input type="password" name="password" value=""/>
								</label>

								<?php do_action( 'fieldpress_after_signup_password' ); ?>

								<label class="right">
									<?php _e( 'Confirm Password', 'cp' ); ?>:
									<input type="password" name="password_confirmation" value=""/>
								</label>
								<br clear="both"/><br/>

								<?php
								if ( shortcode_exists( 'signup-tos' ) ) {
									if ( get_option( 'show_tos', 0 ) == '1' ) {
										?>
										<label class="full"><?php echo do_shortcode( '[signup-tos]' ); ?></label>
									<?php
									}
								}
								?>

								<?php do_action( 'fieldpress_after_all_signup_fields' ); ?>

								<label class="full">
									<?php printf( __( 'Already have an account? %s%s%s!', 'cp' ), '<a href="' . $login_url . '">', __( 'Login to your account', 'cp' ), '</a>' ); ?>
								</label>

								<label class="full-right">
									<input type="submit" name="student-settings-submit" class="apply-button-enrolled" value="<?php _e( 'Create an Account', 'cp' ); ?>"/>
								</label>

								<?php do_action( 'fieldpress_after_submit' ); ?>

								<?php wp_nonce_field( 'student_signup' ); ?>
							</form>
							<div class="clearfix" style="clear: both;"></div>

							<?php do_action( 'fieldpress_after_signup_form' ); ?>
							<?php
							//$content = ob_get_clean();
							// Return the html in the buffer.
							//return $content;
						} else {
							_e( 'Registrations are not allowed.', 'cp' );
						}
					} else {

						if ( ! empty( $redirect_url ) ) {
							wp_redirect( urldecode( $redirect_url ) );
						} else {
							wp_redirect( FieldPress::instance()->get_student_dashboard_slug( true ) );
						}
						exit;
					}

					break;

				case 'login':
					?>
					<?php
					if ( ! empty( $login_title ) ) {
						echo '<' . $login_tag . '>' . $login_title . '</' . $login_tag . '>';
					}
					?>
					<p class="form-info-<?php echo apply_filters( 'signup_form_message_class', sanitize_text_field( $form_message_class ) ); ?>"><?php echo apply_filters( 'signup_form_message', sanitize_text_field( $form_message ) ); ?></p>
					<?php do_action( 'fieldpress_before_login_form' ); ?>
					<form name="loginform" id="student-settings" class="student-settings" method="post">
						<?php do_action( 'fieldpress_after_start_form_fields' ); ?>

						<label>
							<?php _e( 'Username', 'cp' ); ?>:
							<input type="text" name="log" value="<?php echo( isset( $_POST['log'] ) ? sanitize_user( $_POST['log'] ) : '' ); ?>"/>
						</label>

						<label>
							<?php _e( 'Password', 'cp' ); ?>:
							<input type="password" name="pwd" value=""/>
						</label>

						<?php do_action( 'fieldpress_form_fields' ); ?>

						<label class="full">
							<?php
							if ( cp_user_can_register() ) {
								printf( __( 'Don\'t have an account? %s%s%s now!', 'cp' ), '<a href="' . $signup_url . '">', __( 'Create an Account', 'cp' ), '</a>' );
							}
							?>
						</label>

						<label class="half-left">
							<a href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Forgot Password?', 'cp' ); ?></a>
						</label>
						<label class="half-right">
							<input type="submit" name="wp-submit" id="wp-submit" class="apply-button-enrolled" value="<?php _e( 'Log In', 'cp' ); ?>"><br>
						</label>
						<br clear="all"/>

						<input name="redirect_to" value="<?php echo FieldPress::instance()->get_student_dashboard_slug( true ); ?>" type="hidden">
						<input name="testcookie" value="1" type="hidden">
						<input name="field_signup_login" value="1" type="hidden">
						<?php do_action( 'fieldpress_before_end_form_fields' ); ?>
					</form>

					<?php do_action( 'fieldpress_after_login_form' ); ?>
					<?php
					break;
			}
			$content = ob_get_clean();

			// Return the html in the buffer.
			return $content;
		}

		function module_status( $atts ) {
			ob_start();
			extract( shortcode_atts( array(
				'field_id' => in_the_loop() ? get_the_ID() : '',
				'stop_id'   => false,
				//'message'   => __( '%d of %d mandatory elements completed.', 'cp' ),
				'message'   => __( '', 'cp' ),
				'format'    => 'true',
			), $atts, 'module_status' ) );

			$message = sanitize_text_field( $message );
			$format  = sanitize_text_field( $format );
			$format  = 'true' == $format ? true : false;

			if ( $field_id ) {
				$field_id = (int) $field_id;
			}
			$stop_id = (int) $stop_id;

			if ( empty( $stop_id ) ) {
				return '';
			}

			$criteria = Stop::get_module_completion_data( $stop_id );

			$stop_status                    = Stop::get_stop_availability_status( $stop_id );
			$stop_available                 = Stop::is_stop_available( $stop_id, $stop_status );
			$input_modules_count            = count( $criteria['all_input_ids'] );
			$assessable_input_modules_count = count( $criteria['gradable_modules'] );
			$mandatory_input_elements       = count( $criteria['mandatory_modules'] );
			$mandatory_responses            = Student_Completion::get_mandatory_steps_completed( get_current_user_id(), $field_id, $stop_id );
			//			$all_responses					 = do_shortcode( '[field_stop_details field="student_module_responses"]' );
			// $is_stop_available				 = do_shortcode( '[field_stop_details field="is_stop_available"]' );
			//			$input_modules_count			 = do_shortcode( '[field_stop_details field="input_modules_count"]' );
			//			$assessable_input_modules_count	 = do_shortcode( '[field_stop_details field="assessable_input_modules_count"]' );
			//			$mandatory_input_elements		 = do_shortcode( '[field_stop_details field="mandatory_input_modules_count"]' );
			//			$mandatory_responses			 = do_shortcode( '[field_stop_details field="student_module_responses" additional="mandatory"]' );
			//			$all_responses					 = do_shortcode( '[field_stop_details field="student_module_responses"]' );

			$stop         = new Stop( $stop_id );
			$stop->status = $stop_status;

			if ( $input_modules_count > 0 ) {
				?>
				<span class="stop-archive-single-module-status"><?php
					if ( $stop_available ) {
						if ( $mandatory_input_elements > 0 ) {
							echo sprintf( $message, $mandatory_responses, $mandatory_input_elements );
						}
					} else {
						if ( isset( $stop->status ) && $stop->status['mandatory_required']['enabled'] && ! $stop->status['mandatory_required']['result'] && ! $stop->status['completion_required']['enabled'] ) {
							esc_html_e( 'All mandatory answers are required in previous stop.', 'cp' );
						} elseif ( isset( $stop->status ) && $stop->status['completion_required']['enabled'] && ! $stop->status['completion_required']['result'] ) {
							esc_html_e( 'Previous stop must be completed successfully.', 'cp' );
						}
						if ( isset( $stop->status ) && ! $stop->status['date_restriction']['result'] ) {
							echo __( 'Available', 'cp' ) . ' ' . date_i18n( get_option( 'date_format' ), strtotime( do_shortcode( '[field_stop_details field="stop_availability"]' ) ) );
						}
					}
					?></span>
			<?php } else { ?>
				<span class="stop-archive-single-module-status"><?php
					if ( $stop_available ) {
						//						 _e('Read-only','cp');
					} else {
						if ( isset( $stop->status ) && $stop->status['mandatory_required']['enabled'] && ! $stop->status['mandatory_required']['result'] && ! $stop->status['completion_required']['enabled'] ) {
							esc_html_e( 'All mandatory answers are required in previous stop.', 'cp' );
						} elseif ( isset( $stop->status ) && $stop->status['completion_required']['enabled'] && ! $stop->status['completion_required']['result'] ) {
							esc_html_e( 'Previous stop must be completed successfully.', 'cp' );
						}
						if ( isset( $stop->status ) && ! empty( $stop->status ) && ! $stop->status['date_restriction']['result'] ) {
							echo __( 'Available', 'cp' ) . ' ' . date_i18n( get_option( 'date_format' ), strtotime( do_shortcode( '[field_stop_details field="stop_availability"]' ) ) );
						}
					}
					?></span>
			<?php
			}
			$content = ob_get_clean();

			return $content;
		}

		function student_workbook_table( $args ) {
			ob_start();
			extract( shortcode_atts(
				array(
					'module_column_title'               => __( 'Element', 'cp' ),
					'title_column_title'                => __( 'Title', 'cp' ),
					'submission_date_column_title'      => __( 'Submitted', 'cp' ),
					'response_column_title'             => __( 'Answer', 'cp' ),
					'grade_column_title'                => __( 'Grade', 'cp' ),
					'comment_column_title'              => __( 'Comment', 'cp' ),
					'module_response_description_label' => __( 'Description', 'cp' ),
					'comment_label'                     => __( 'Comment', 'cp' ),
					'view_link_label'                   => __( 'View', 'cp' ),
					'view_link_class'                   => 'assessment-view-response-link button button-stops',
					'comment_link_class'                => 'assessment-view-response-link button button-stops',
					'pending_grade_label'               => __( 'Pending', 'cp' ),
					'stop_unread_label'                 => __( 'Stop Unread', 'cp' ),
					'stop_read_label'                   => __( 'Stop Read', 'cp' ),
					'single_correct_label'              => __( 'Correct', 'cp' ),
					'single_incorrect_label'            => __( 'Incorrect', 'cp' ),
					'non_assessable_label'              => __( '**' ),
					'table_class'                       => 'widefat shadow-table assessment-archive-table',
					'table_labels_th_class'             => 'manage-column'
				)
				, $args ) );

			$module_column_title               = sanitize_text_field( $module_column_title );
			$title_column_title                = sanitize_text_field( $title_column_title );
			$submission_date_column_title      = sanitize_text_field( $submission_date_column_title );
			$response_column_title             = sanitize_text_field( $response_column_title );
			$grade_column_title                = sanitize_text_field( $grade_column_title );
			$comment_column_title              = sanitize_text_field( $comment_column_title );
			$module_response_description_label = sanitize_text_field( $module_response_description_label );
			$comment_label                     = sanitize_text_field( $comment_label );
			$view_link_label                   = sanitize_text_field( $view_link_label );
			$view_link_class                   = sanitize_html_class( $view_link_class );
			$comment_link_class                = sanitize_html_class( $comment_link_class );
			$pending_grade_label               = sanitize_text_field( $pending_grade_label );
			$stop_unread_label                 = sanitize_text_field( $stop_unread_label );
			$stop_read_label                   = sanitize_text_field( $stop_read_label );
			$non_assessable_label              = sanitize_text_field( $non_assessable_label );
			$table_class                       = sanitize_html_class( $table_class );
			$table_labels_th_class             = sanitize_html_class( $table_labels_th_class );
			$single_correct_label              = sanitize_text_field( $single_correct_label );
			$single_incorrect_label            = sanitize_text_field( $single_incorrect_label );

			$columns = array(
				// "module" => $module_column_title,
				"title"           => $title_column_title,
				"submission_date" => $submission_date_column_title,
				"response"        => $response_column_title,
				"grade"           => $grade_column_title,
				"comment"         => $comment_column_title,
			);


			$col_sizes = array(
				// '15',
				// 				'30',
				'45',
				'15',
				'10',
				'13',
				'5'
			);
			?>
			<table cellspacing="0" class="<?php echo $table_class; ?>">
				<thead>
					<tr>
						<?php
						$n = 0;
						foreach ( $columns as $key => $col ) {
							?>
							<th class="<?php echo $table_labels_th_class; ?> column-<?php echo $key; ?>" width="<?php echo $col_sizes[ $n ] . '%'; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
							<?php
							$n ++;
						}
						?>
					</tr>
				</thead>

				<?php
				$user_object = new Student( get_current_user_ID() );

				$modules = Stop_Module::get_modules( get_the_ID() );

				$input_modules_count = 0;

				foreach ( $modules as $mod ) {
					$class_name = $mod->module_type;
					if ( class_exists( $class_name ) ) {
						if ( constant( $class_name . '::FRONT_SAVE' ) ) {
							$input_modules_count ++;
						}
					}
				}

				$current_row = 0;
				$style       = '';
				foreach ( $modules as $mod ) {
					$class_name = $mod->module_type;

					if ( class_exists( $class_name ) ) {

						if ( constant( $class_name . '::FRONT_SAVE' ) ) {
							$response         = call_user_func( $class_name . '::get_response', $user_object->ID, $mod->ID );
							$visibility_class = ( count( $response ) >= 1 ? '' : 'less_visible_row' );

							if ( count( $response ) >= 1 ) {
								$grade_data = Stop_Module::get_response_grade( $response->ID );
							}

							if ( isset( $_GET['ungraded'] ) && $_GET['ungraded'] == 'yes' ) {
								if ( count( $response ) >= 1 && ! $grade_data ) {
									$general_col_visibility = true;
								} else {
									$general_col_visibility = false;
								}
							} else {
								$general_col_visibility = true;
							}

							$style = ( isset( $style ) && 'alternate' == $style ) ? '' : ' alternate';
							?>
							<tr id='user-<?php echo $user_object->ID; ?>' class="<?php
							echo $style;
							echo 'row-' . $current_row;
							?>">

								<?php
								if ( $general_col_visibility ) {
									?>

									<td class="<?php echo $style . ' ' . $visibility_class; ?>">
										<?php echo $mod->post_title; ?>
									</td>

									<td class="<?php echo $style . ' ' . $visibility_class; ?>">
										<?php echo( count( $response ) >= 1 ? date_i18n( 'M d, Y', strtotime( $response->post_date ) ) : __( 'Not submitted', 'cp' ) ); ?>
									</td>

									<td class="<?php echo $style . ' ' . $visibility_class; ?>">
										<?php
										if ( count( $response ) >= 1 ) {
											?>
											<div id="response_<?php echo $response->ID; ?>" style="display:none;">
												<?php if ( isset( $mod->post_content ) && $mod->post_content !== '' ) { ?>
													<div class="module_response_description">
														<label><?php echo $module_response_description_label; ?></label>
														<?php echo $mod->post_content; ?>
													</div>
												<?php } ?>
												<?php echo call_user_func( $class_name . '::get_response_form', get_current_user_ID(), $mod->ID ); ?>

												<?php
												if ( is_object( $response ) && ! empty( $response ) ) {

													$comment = Stop_Module::get_response_comment( $response->ID );
													if ( ! empty( $comment ) ) {
														?>
														<label class="comment_label"><?php echo $comment_label; ?></label>
														<div class="response_comment_front"><?php echo $comment; ?></div>
													<?php
													}
												}
												?>
											</div>

											<a class="<?php echo sanitize_html_class( $view_link_class ); ?> thickbox" href="#TB_inline?width=500&height=300&inlineId=response_<?php echo $response->ID; ?>"><?php echo sanitize_html_class( $view_link_label ); ?></a>

										<?php
										} else {
											echo '-';
										}
										?>
									</td>

									<td class="<?php echo $style . ' ' . $visibility_class; ?>">
										<?php
										if ( isset( $grade_data ) ) {
											$grade           = $grade_data['grade'];
											$instructor_id   = $grade_data['instructor'];
											$instructor_name = get_userdata( $instructor_id );
											$grade_time      = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $grade_data['time'] );
										}
										if ( count( $response ) >= 1 ) {

											if ( isset( $grade_data ) ) {
												if ( get_post_meta( $mod->ID, 'gradable_answer', true ) == 'no' ) {
													echo $non_assessable_label;
												} else {
													if ( 'radio_input_module' == $class_name ) {
														if ( 100 == $grade ) {
															echo $single_correct_label;
														} else {
															echo $single_incorrect_label;
														}
													} else {
														echo $grade . '%';
													}
												}
											} else {
												if ( get_post_meta( $mod->ID, 'gradable_answer', true ) == 'no' ) {
													echo $non_assessable_label;
												} else {
													echo $pending_grade_label;
												}
											}
										} else {
											echo '-';
										}
										?>
									</td>

									<td class="<?php echo $style . ' ' . $visibility_class; ?> td-center">
										<?php
										if ( ! empty( $response ) ) {

											$comment = Stop_Module::get_response_comment( $response->ID );
											if ( ! empty( $comment ) ) {
												?>
												<a alt="<?php echo strip_tags( $comment ); ?>" title="<?php echo strip_tags( $comment ); ?>" class="<?php echo $comment_link_class; ?> thickbox" href="#TB_inline?width=500&height=300&inlineId=response_<?php echo $response->ID; ?>"><i class="fa fa-comment"></i></a>
											<?php
											}
										} else {
											echo '<i class="fa fa-comment-o"></i>';
										}
										?>
									</td>
								<?php }//general col visibility                          ?>
							</tr>
							<?php
							$current_row ++;
						}
					}
				}


				if ( ! isset( $input_modules_count ) || isset( $input_modules_count ) && $input_modules_count == 0 ) {
					?>
					<tr>
						<td colspan="7">
							<?php
							$stop_grade = do_shortcode( '[field_stop_details field="student_stop_grade" stop_id="' . get_the_ID() . '"]' );
							_e( '0 input elements in the selected stop.', 'cp' );
							?>
							<?php
							if ( $stop_grade == 0 ) {
								echo $stop_unread_label;
							} else {
								echo $stop_read_label;
							}
							?>
						</td>
					</tr>
				<?php
				}
				?>
				<?php if ( 0 < $current_row ) : ?>
					<tfoot><tr><td colspan="6">** <?php _e( 'Non-assessable elements.', 'cp' ); ?></td></tr></tfoot>
				<?php endif; ?>
			</table>
			<?php
			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Shows the field trip title.
		 *
		 * @since 1.0.0
		 */
		function field_stop_title( $atts ) {
			extract( shortcode_atts( array(
				'stop_id'   => in_the_loop() ? get_the_ID() : '',
				'title_tag' => '',
				'link'      => 'no',
				'class'     => '',
				'last_page' => 'no',
			), $atts, 'field_stop_title' ) );

			$stop_id   = (int) $stop_id;
			$field_id = (int) get_post_field( 'post_parent', $stop_id );
			$title_tag = sanitize_html_class( $title_tag );
			$link      = sanitize_html_class( $link );
			$last_page = sanitize_html_class( $last_page );
			$class     = sanitize_html_class( $class );

			$title = get_the_title( $stop_id );

			$draft      = get_post_status($stop_id) !== 'publish';
			$show_draft = $draft && cp_can_see_stop_draft();

			if ( 'yes' == $last_page ) {
				$last_visited_page     = cp_get_last_visited_stop_page( $stop_id );
				$the_permalink = Stop::get_permalink( $stop_id, $field_id ) . 'page/' . trailingslashit( $last_visited_page );
			} else {
				$the_permalink = Stop::get_permalink( $stop_id, $field_id );
			}

			$content = '';
			if ( ! $draft || ( $draft && $show_draft ) ) {
				$content = ! empty( $title_tag ) ? '<' . $title_tag . ' class="field-stop-title field-stop-title-' . $stop_id . ' ' . $class . '">' : '';
				$content .= 'yes' == $link ? '<a href="' . esc_url( $the_permalink ) . '" title="' . $title . '" class="stop-archive-single-title">' : '';
				$content .= $title;
				$content .= 'yes' == $link ? '</a>' : '';
				$content .= ! empty( $title_tag ) ? '</' . $title_tag . '>' : '';
			}

			// Return the html in the buffer.
			return $content;
		}


		public static function field_stop_page_title( $atts ) {

			extract( shortcode_atts( array(
				'stop_id'   => in_the_loop() ? get_the_ID() : '',
				'title_tag' => '',
				'show_stop_title' => 'no',
			), $atts, 'field_stop_page_title' ) );

			$stop_id = (int) $stop_id;
			$title_tag      = sanitize_text_field( $title_tag );
			$show_stop_title = sanitize_text_field( $show_stop_title );

			global $wp;

			$paged     = isset( $wp->query_vars['paged'] ) ? absint( $wp->query_vars['paged'] ) : 1;
			$page_name = Stop::page_name( $stop_id, (int) $paged );
			$stop_name = get_post_field( 'post_title', $stop_id );

			$show_title_array = get_post_meta( $stop_id, 'show_page_title', true );
			$show_title       = false;
			if ( isset( $show_title_array[ $paged - 1 ] ) && 'yes' == $show_title_array[ $paged - 1 ] ) {
				$show_title = true;
			}

			if ( ! empty( $page_name ) && $show_title ) {

				if( 'yes' === strtolower( $show_stop_title ) ) {
					$page_name = $stop_name . ': ' . $page_name;
				}

				$content = ! empty( $title_tag ) ? '<' . $title_tag . ' class="stop-archive-single-title field-stop-page-title field-stop-page-title-' . $stop_id . '">' : '';
				$content .= $page_name;
				$content .= ! empty( $title_tag ) ? '</' . $title_tag . '>' : '';

				return $content;
			} else {
				return '';
			}

		}

		public static function instance( $instance = null ) {
			if ( ! $instance || 'FieldPress_Shortcodes' != get_class( $instance ) ) {
				if ( is_null( self::$instance ) ) {
					self::$instance = new FieldPress_Shortcodes();
				}
			} else {
				if ( is_null( self::$instance ) ) {
					self::$instance = $instance;
				}
			}

			return self::$instance;
		}

	}

}

FieldPress_Shortcodes::instance( new FieldPress_Shortcodes() );
?>