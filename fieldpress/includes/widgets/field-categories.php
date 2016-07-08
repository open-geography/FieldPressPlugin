<?php

/**
 * Field Trip Categories widget classz
 */
class CP_Widget_Categories extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname'   => 'cp_field_categories',
		                     'description' => __( "A list or dropdown of field categories.", 'cp' )
		);
		parent::__construct( 'CP_Widget_Categories', __( 'Field Trip Categories', 'cp' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Field Trip Categories' ) : $instance['title'], $instance, $this->id_base );
		$c     = ! empty( $instance['count'] ) ? '1' : '0';

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<ul>
			<?php
			global $fieldpress;
			$taxonomies = array(
				'field_category',
			);

			$args  = array(
				'orderby'      => 'name',
				'order'        => 'ASC',
				'hide_empty'   => true,
				'fields'       => 'all',
				'hierarchical' => true,
			);
			$terms = get_terms( $taxonomies, apply_filters( 'cp_field_categories_args', $args ) );
			foreach ( $terms as $term ) {
				?>
				<li>
					<a href="<?php echo trailingslashit( $fieldpress->get_field_slug( true ) ) . trailingslashit( $fieldpress->get_field_category_slug() ) . trailingslashit( $term->slug ); ?>"><?php echo $term->name; ?></a><?php echo isset( $c ) && $c ? ' (' . $term->count . ')' : '' ?>
				</li>
			<?php
			}
			?>
		</ul>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = esc_attr( $instance['title'] );
		$count    = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show field counts' ); ?></label><br/>
	<?php
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CP_Widget_Categories");' ) );
?>