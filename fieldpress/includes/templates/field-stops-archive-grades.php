<?php
//echo do_shortcode( '[field_breadcrumbs type="stop_archive"]' );
$field_id			 = do_shortcode( '[get_parent_field_id]' );
$field_id			 = (int) $field_id;
FieldPress::instance()->check_access( $field_id );
do_shortcode( '[field_stops_loop]' ); //required for getting stop results
?>

<?php
echo do_shortcode( '[field_stop_archive_submenu]' );
?>
<h2><?php _e( 'Field Grades', 'cp' ); ?></h2>

<div class="stops-archive">
	<ul class="stops-archive-list">
		<?php if ( have_posts() ) { ?>
			<?php
			$grades = 0;
			$stops  = 0;
			while ( have_posts() ) : the_post();
				$grades = $grades + do_shortcode( '[field_stop_details field="student_stop_grade" stop_id="' . get_the_ID() . '"]' );
				?>
				<li>
					<span class="percentage"><?php echo do_shortcode( '[field_stop_details field="student_stop_grade" stop_id="' . get_the_ID() . '" format="true"]' ); ?></span><a href="<?php echo do_shortcode( '[field_stop_details field="permalink" stop_id="' . get_the_ID() . '"]' ); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php if ( do_shortcode( '[field_stop_details field="input_modules_count"]' ) > 0 ) { ?>
						<span class="stop-archive-single-module-status"><?php echo do_shortcode( '[field_stop_details field="student_module_responses"]' ); ?> <?php _e( 'of', 'cp' ); ?> <?php echo do_shortcode( '[field_stop_details field="input_modules_count"]' ); ?> <?php _e( 'elements completed', 'cp' ); ?></span>
					<?php } else { ?>
						<span class="stop-archive-single-module-status read-only-module"><?php _e( 'Read-only', 'cp' ); ?></span>
					<?php } ?>
				</li>
				<?php
				$stops ++;
			endwhile;
		} else {
			?>
			<h1 class="zero-field-stops"><?php _e( "0 stops in the field trip currently. Please check back later.", "cp" ); ?></h1>
		<?php
		}
		?>
	</ul>

	<div class="total_grade"><?php echo apply_filters( 'fieldpress_grade_caption', __( 'TOTAL:', 'cp' ) ); ?> <?php echo esc_html( apply_filters( 'fieldpress_grade_total', ( $grades > 0 ? ( round( $grades / $stops, 0 ) ) : 0 ) . '%' ) ); ?></div>

</div>