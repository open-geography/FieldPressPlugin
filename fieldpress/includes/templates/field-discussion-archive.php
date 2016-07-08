<?php
/**
 * The discussion archive template file
 *
 * @package FieldPress
 */
global $fieldpress, $wp;
$field_id = do_shortcode( '[get_parent_field_id]' );
$field_id = (int) $field_id;
//redirect to the parent field page if not enrolled
$fieldpress->check_access( $field_id );
?>
<?php
echo do_shortcode( '[field_stop_archive_submenu]' );
?>

	<h2><?php _e( 'Discussions', 'cp' ); ?></h2>

	<div class="discussion-controls">
		<?php $url = get_permalink( $field_id ) . trailingslashit( $fieldpress->get_discussion_slug() ) . trailingslashit( $fieldpress->get_discussion_slug_new() ); ?>
		<button data-link="<?php echo esc_url( $url ); ?>"><?php _e( 'Ask a Question', 'cp' ); ?></button>
	</div>

	<ul class="discussion-archive-list">
		<?php
		$page = ( isset( $wp->query_vars['paged'] ) ) ? (int) $wp->query_vars['paged'] : 1;
		do_shortcode( '[field_discussion_loop]' );

		if ( have_posts() ) {
			?>
			<?php
			while ( have_posts() ) : the_post();
				//foreach ( $myposts as $post ) : setup_postdata($post);
				$discussion = new Discussion( get_the_ID() );
				?>
				<li>
					<div class="discussion-archive-single-meta">
						<div class="<?php
						if ( get_comments_number() > 0 ) {
							echo 'discussion-answer-circle';
						} else {
							echo 'discussion-comments-circle';
						}
						?>">
							<span class="comments-count"><?php echo get_comments_number(); ?> <?php _e( 'Comments', 'cp' ); ?></span>
						</div>
					</div>
					<div class="discussion-archive-single">
						<h1 class="discussion-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

						<div class="discussion-meta">
							<?php
							if ( $discussion->details->stop_id == '' ) {
								$discussion_stop = esc_html( $discussion->get_stop_name() );
							} else {
								$discussion_stop = sprintf( '<a href="%s">%s</a>', esc_url( Stop::get_permalink( $discussion->details->stop_id ) ), esc_html( $discussion->get_stop_name() ) );
							}
							?>
							<span><?php echo get_the_date(); ?></span> | <span><?php the_author(); ?></span> |
							<span><?php echo $discussion_stop; ?></span>
							<span><?php echo get_comments_number(); ?> <?php _e( 'Comments', 'cp' ); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>

				</li>
			<?php
			endwhile;
		} else {
			?>
			<h1 class="zero-field-stops"><?php _e( "0 discussions. Start one, ask a question.", "cp" ); ?></h1>
		<?php
		}
		?>
	</ul>
	<br clear="all"/>
<?php cp_numeric_posts_nav( 'navigation-pagination' ); ?>