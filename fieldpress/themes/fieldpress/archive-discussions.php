<?php
/**
 * The discussion archive template file
 *
 * @package FieldPress
 */
global $fieldpress, $wp;
$field_id = do_shortcode('[get_parent_field_id]');
//redirect to the parent field page if not enrolled
$fieldpress->check_access($field_id);
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <h1><?php echo do_shortcode('[field_title field_id="' . $field_id . '"]'); ?></h1>
        <div class="instructors-content">
            <?php
            // Flat hyperlinked list of instructors
            echo do_shortcode('[field_instructors style="list-flat" link="true" field_id="' . $field_id . '"]');
            ?>
        </div>

        <?php
        echo do_shortcode('[field_stop_archive_submenu]');
        ?>

        <div class="discussion-controls">
            <a class="button_submit" href="<?php echo get_permalink($field_id); ?><?php echo $fieldpress->get_discussion_slug() . '/' . $fieldpress->get_discussion_slug_new(); ?>/"><?php _e('Ask a Question', 'cp'); ?></a>
        </div>

        <div class="clearfix"></div>

        <ul class="discussion-archive-list">
            <?php
            //do_shortcode( '[field_discussion_loop]' ); //required to get good results

            $page = ( isset($wp->query_vars['paged']) ) ? $wp->query_vars['paged'] : 1;
            $query_args = array(
                'order' => 'DESC',
                'post_type' => 'discussions',
                'post_status' => 'publish',
                'meta_key' => 'field_id',
                'meta_value' => $field_id,
                'paged' => $page,
            );

            query_posts($query_args);

            if ( have_posts() ) {
                ?>
                <?php
                while ( have_posts() ) : the_post();
                    $discussion = new Discussion(get_the_ID());
                    ?>
                    <li>
                        <div class="discussion-archive-single-meta">
                            <div class="<?php
            if ( get_comments_number() > 0 ) {
                echo 'discussion-answer-circle';
            } else {
                echo 'discussion-comments-circle';
            }
                    ?>"><span class="comments-count"><?php echo get_comments_number(); ?></span></div>
                        </div>
                        <div class="discussion-archive-single">
                            <h1 class="discussion-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                            <div class="discussion-meta">
                                <?php
                                if ( $discussion->details->stop_id == '' ) {
                                    $discussion_stop = $discussion->get_stop_name();
                                } else {
                                    $discussion_stop = '<a href="' . Stop::get_permalink( $discussion->details->stop_id ) . '">' . $discussion->get_stop_name() . '</a>';
                                }
                                ?>
                                <span><?php echo get_the_date(); ?></span> | <span><?php the_author(); ?></span> | <span><?php echo $discussion_stop; ?></span> | <span><?php echo get_comments_number(); ?> <?php _e('Comments', 'cp'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </li>
                    <?php
                endwhile;
            } else {
                ?>
                <h1 class="zero-field-stops"><?php _e("0 discussions. Start one, ask a question.", "cp"); ?></h1>
                <?php
            }
            ?>
        </ul>
        <br clear="all" />
        <?php cp_numeric_posts_nav('navigation-pagination'); ?>
    </main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>