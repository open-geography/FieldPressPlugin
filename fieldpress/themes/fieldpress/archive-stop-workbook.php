<?php
/**
 * The stops archive / grades template file
 *
 * @package FieldPress
 */
global $fieldpress;
$field_id = do_shortcode('[get_parent_field_id]');
$field_id = (int) $field_id;
$progress = do_shortcode('[field_progress field_id="' . $field_id . '"]');
//redirect to the parent field page if not enrolled
$fieldpress->check_access($field_id);

get_header();

add_thickbox();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
		<h1 class="workbook-title">
			<?php echo do_shortcode('[field_title field_id="' . $field_id . '" title_tag=""]'); ?>
			<?php if ( 100 > (int) $progress ) { ?>
				<span class="workbook-field-progress"><?php echo esc_html( $progress ); ?>% <?php esc_html_e('completed', 'cp'); ?></span>
			<?php } ?>
		</h1>

        <div class="instructors-content">
            <?php
            // Flat hyperlinked list of instructors
            echo do_shortcode('[field_instructors style="list-flat" link="true" field_id="' . $field_id . '"]');
            ?>
        </div>

        <?php
        echo do_shortcode('[field_stop_archive_submenu]');
        ?>
		<?php
			if( 100 == (int) $progress) {
				echo sprintf( '<div class="stop-archive-field-complete">%s %s</div>', '<i class="fa fa-check-circle"></i>', __( 'Field Trip Complete', 'cp' ) );
			}
		?>

        <div class="clearfix"></div>

        <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                ?>
                <div class="workbook_stops">
                    <div class="stop_title">
                        <h3><?php the_title(); ?>
                            <span><?php echo do_shortcode('[field_stop_progress field_id="' . $field_id . '" stop_id="' . get_the_ID() . '"]'); ?>% <?php _e('completed', 'cp'); ?></span>
                        </h3>
                    </div>
                    <div class="accordion-inner">
                        <?php echo do_shortcode('[student_workbook_table]'); ?>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="zero-fields"><?php _e('0 Stops in the field trip', 'cp'); ?></div>
            <?php
        }
        ?>

        <!--<ul class="stops-archive-list">
        <?php if ( have_posts() ) { ?>
            <?php
            $grades = 0;
            $stops = 0;
            while ( have_posts() ) {
                the_post();
                $grades = $grades + do_shortcode('[field_stop_details field="student_stop_grade" stop_id="' . get_the_ID() . '"]');
                ?>
                <a class="stop-archive-single-title" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                <?php if ( do_shortcode('[field_stop_details field="input_modules_count"]') > 0 ) { ?>
                                                                                                                                                                                                                                                        <span class="stop-archive-single-module-status"><?php echo do_shortcode('[field_stop_details field="student_module_responses"]'); ?> <?php _e('of', 'cp'); ?> <?php echo do_shortcode('[field_stop_details field="mandatory_input_modules_count"]'); ?> <?php _e('mandatory elements completed', 'cp'); ?> | <?php echo do_shortcode('[field_stop_details field="student_stop_modules_graded" stop_id="' . get_the_ID() . '"]'); ?> <?php _e('of', 'cp'); ?> <?php echo do_shortcode('[field_stop_details field="input_modules_count"]'); ?> <?php _e('elements graded', 'cp'); ?></span>
                <?php } else { ?>
                                                                                                                                                                                                                                                        <span class="stop-archive-single-module-status read-only-module"><?php _e('Read only','cp'); ?></span>
                <?php } ?>
                                                                                                                                                                        </div>
                                                                                                                                                                    </li>
                <?php
                $stops++;
            }
            ?>
                    <div class="total_grade"><?php echo apply_filters('fieldpress_grade_caption', ( __('TOTAL:', 'cp'))); ?> <?php echo apply_filters('fieldpress_grade_total', ( $grades > 0 ? ( round($grades / $stops, 0) ) : 0 ) . '%'); ?></div>
            <?php
        } else {
            ?>
                    <h1 class="zero-field-stops"><?php _e("0 stops in the field trip currently. Please check back later.",'cp'); ?></h1>
            <?php
        }
        ?>
        </ul>-->
    </main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>