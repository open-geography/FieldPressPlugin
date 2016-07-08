<?php

class CP_Field_Stop extends WP_Widget {

	function CP_Field_Stop() {
		$widget_ops = array( 'classname'   => 'cp_field_strucutre_widget',
		                     'description' => __( 'Displays a selected Field Trip Stop', 'cp' )
		);
		parent::__construct( 'CP_Field_Stop', __( 'Field Trip Stop', 'cp' ), $widget_ops );
	}

	function form( $instance ) {
		$instance        = wp_parse_args( ( array ) $instance, array( 'title' => '' ) );
		$title           = $instance['title'];
		$selected_field = !empty($instance['field']) ? $instance['field'] : '';

		$args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'field',
			'post_status'    => 'publish'
		);

		$fields = get_posts( $args );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'cp' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/></label>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'field' ); ?>"><?php _e( 'Field Trip', 'cp' ); ?><br/>
				<select name="<?php echo $this->get_field_name( 'field' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'field' ); ?>">
					<option value="false" <?php selected( $selected_field, "false", true ); ?>><?php _e( '- current -', 'cp' ); ?></option>
					<?php
					foreach ( $fields as $field ) {
						?>
						<option value="<?php echo $field->ID; ?>" <?php selected( $selected_field, $field->ID, true ); ?>><?php echo $field->post_title; ?></option>
					<?php
					}
					?>
				</select>
			</label>
		</p>

	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Admin on single sites, Super admin on network
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['title'] = $new_instance['title'];
		} else {
			$instance['title'] = strip_tags( $new_instance['title'] );
		}
		$instance['field'] = $new_instance['field'];

		return $instance;
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args, EXTR_SKIP );

		$field_id = false;
		if ( 'false' == $instance['field'] ) {
			if ( $post && 'field' == $post->post_type ) {
				$field_id = $post->ID;
			} else {
				$parent_id = do_shortcode( '[get_parent_field_id]' );
				$field_id = 0 != $parent_id ? $parent_id : $field_id;
			}
		} else {
			$field_id = $instance['field'];
		}

		if ( ( $post && ( 'field' == $post->post_type || 'stop' == $post->post_type ) && ! is_post_type_archive( 'field' ) ) || 'false' != $instance['field'] ) {

			echo $before_widget;

			$field = new Field( $field_id );

			$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );

			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="field_stop_widget">';
			// $field->field_stop_front( __('Free', 'cp') );
			echo do_shortcode( '[field_stop field_id="' . $field_id . '" label="" show_title="no" show_divider="no"]' );
			// Strange bug.
			echo '</div>&nbsp;';

			echo $after_widget;
		}
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CP_Field_Stop");' ) );
?>