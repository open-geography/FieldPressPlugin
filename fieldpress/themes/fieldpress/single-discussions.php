<?php
/**
 * The Template for displaying all single posts.
 *
 * @package FieldPress
 */
global $fieldpress;

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        while ( have_posts() ) : the_post();
            $field_id = get_post_meta( get_the_ID(), 'field_id', true );
            $fieldpress->check_access( $field_id );
            ?>
            <h1><?php echo do_shortcode( '[field_title field_id="' . $field_id . '"]' ); ?></h1>
            <div class="instructors-content">
                <?php echo do_shortcode( '[field_instructors style="list-flat" field_id="' . $field_id . '"]' ); ?>
            </div>

            <?php
            echo do_shortcode( '[field_stop_archive_submenu field_id="' . $field_id . '"]' );
            ?>

            <div class="clearfix"></div>

            <?php get_template_part( 'content-discussion', 'single' ); ?>

            <?php fieldpress_post_nav(); ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template
            /* if ( comments_open() || '0' != get_comments_number() ) :
              comments_template();
              endif; */
            ?>


        <?php endwhile; // end of the loop.  ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar( 'footer' ); ?>
<?php get_footer(); ?>