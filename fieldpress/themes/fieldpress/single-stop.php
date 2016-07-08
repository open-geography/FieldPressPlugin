<?php
/**
 * The Template for displaying single stop posts with modules
 *
 * @package FieldPress
 */
global $fieldpress, $wp, $wp_query;

$field_id = do_shortcode('[get_parent_field_id]');

add_thickbox();

$paged = ! empty( $wp->query_vars['paged'] ) ? absint($wp->query_vars['paged']) : 1;
//redirect to the parent field page if not enrolled or not preview stop/page
while ( have_posts() ) : the_post();
    $fieldpress->check_access($field_id, get_the_ID());
endwhile;

get_header();

$post = $stop->details;
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h3 class="entry-title field-title"><?php echo do_shortcode('[field_title field_id="' . $field_id . '"]'); ?></h3>
                    <?php
                    //echo do_shortcode('[field_stop_details stop_id="' . get_the_ID() . '" field="parent_field"]');
                    ?>
                </header><!-- .entry-header -->
                <div class="instructors-content"></div>
                <?php
                echo do_shortcode('[field_stop_archive_submenu field_id="' . $field_id . '"]');
                ?>

                <div class="clearfix"></div>

                <?php echo do_shortcode( '[field_stop_page_title stop_id="' . $stop->details->ID . '" title_tag="h3" show_stop_title="yes"]' ); ?>

                <?php
                Stop_Module::get_modules_front($stop->details->ID);
                ?>
            </article>
        <?php endwhile; // end of the loop. ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>