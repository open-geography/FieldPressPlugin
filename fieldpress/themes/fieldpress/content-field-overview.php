<?php
/**
 * @package FieldPress
 */
?>
<?php
$field = new Field( get_the_ID() );

$field_category_id	 = $field->details->field_category;
$field_category	 = get_term_by( 'ID', $field_category_id, 'field_category' );

$field_language = $field->details->field_language;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="instructors-content">
			<?php echo do_shortcode( '[field_instructors list="true" link="true"]' ); ?>
        </div>
    </header><!-- .entry-header -->

    <section id="field-summary">


		<?php
		$field_media	 = do_shortcode( '[field_media]' );
		if ( $field_media !== '' ) {
			?>
			<div class="field-video">
				<?php
				// Show field media
				echo $field_media;
				?>
			</div>
		<?php } ?>

        <div class="entry-content-excerpt <?php echo ($field_media == '' ? 'entry-content-excerpt-right' : '' ); ?>">
			<?php //the_excerpt();   ?>
            <div class="field-box">
				<?php echo do_shortcode( '[field_dates show_alt_display="yes"]' ); //change to yes for 'Open-ended' ?>
				<?php echo do_shortcode( '[field_enrollment_dates show_alt_display="no"]' ); //change to yes for 'Open-ended' ?>
				<?php echo do_shortcode( '[field_class_size]' ); ?>
				<?php echo do_shortcode( '[field_enrollment_type label="'.__('Who can Register: ', 'cp').'"]' ); ?>
				<?php echo do_shortcode( '[field_language]' ); ?>
				<?php echo do_shortcode( '[field_cost]' ); ?>

            </div><!--field-box-->
            <div class="quick-field-info">
				<?php // echo do_shortcode('[field_details field="button"]'); ?>
				<?php echo do_shortcode( '[field_join_button]' ); ?>
            </div>
        </div>
    </section>

    <section id="additional-summary">
        <div class="social-shares">
            <span><?php _e( 'SHARE', 'cp' ); ?></span>
            <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php the_permalink(); ?>&p[images][0]=&p[title]=<?php the_title(); ?>&p[summary]=<?php echo urlencode( strip_tags( get_the_excerpt() ) ); ?>" class="facebook-share" target="_blank"></a>
            <a href="http://twitter.com/home?status=<?php the_title(); ?> <?php the_permalink(); ?>" class="twitter-share" target="_blank"></a>
            <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" class="google-share" target="_blank"></a>
            <a href="mailto:?subject=<?php the_title(); ?>&body=<?php echo strip_tags( get_the_excerpt() ); ?>" target="_top" class="email-share"></a>
        </div><!--social shares-->
    </section>

    <br clear="all" />

	<?php
	$instructors = Field::get_field_instructors( $field->details->ID );
	?>
    <div class="entry-content <?php echo( count( $instructors ) > 0 ? 'left-content' : '' ); ?>">
        <h1 class="h1-about-field"><?php _e( 'About the Field Trip', 'cp' ); ?></h1>
		<?php the_content(); ?>
		<?php if ( $field->details->field_stop_options == 'on' ) { ?>
			<h1 class = "h1-about-field"><?php
				_e( 'Field Trip Stop', 'cp' );
				?></h1>
			<?php
			// $field->field_stop_front();
			echo do_shortcode( '[field_stop label="" show_title="no" show_divider="yes"]' );
		}
		?>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'cp' ),
			'after'	 => '</div>',
		) );
		?>
    </div><!-- .entry-content -->

	<?php if ( count( $instructors ) > 0 ) { ?>
		<div class="field-instructors right-content">
			<h1 class="h1-instructors"><?php _e( 'Instructors', 'cp' ); ?></h1>
			<script>
				jQuery( function() {
					jQuery( "#instructor-profiles" ).accordion( {
						heightStyle: "content"
					} );
				} );
			</script>
			<div id="instructor-profiles">
				<?php
				foreach ( $instructors as $instructor ) {
					?>

					<h3><?php echo $instructor->display_name; ?></h3>

					<?php
					$doc		 = new DOMDocument();
					$doc->loadHTML( get_avatar( $instructor->ID, 235 ) );
					$imageTags	 = $doc->getElementsByTagName( 'img' );

					foreach ( $imageTags as $tag ) {
						$avatar_url = $tag->getAttribute( 'src' );
					}
					?>

					<div>
						<img src="<?php echo $avatar_url; ?>" />
						<p>
							<?php echo author_description_excerpt( $instructor->ID, 50 ); ?>
						</p>
						<a href="<?php echo do_shortcode( '[instructor_profile_url instructor_id="' . $instructor->ID . '"]' ); ?>" class="full-instructor-profile"><?php _e( 'View Full Profile', 'cp' ); ?></a>
					</div>
				<?php } ?>
			</div>

		</div><!--field-instructors right-content-->
	<?php } ?>
    <br clear="all" />

    <footer class="entry-meta">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$category_list = get_the_category_list( __( ', ', 'cp' ) );

		/* translators: used between list items, there is a space after the comma */
		$tag_list = get_the_tag_list( '', __( ', ', 'cp' ) );

		if ( !fieldpress_categorized_blog() ) {
			// This blog only has 1 category so we just need to worry about tags in the meta text
			if ( '' != $tag_list ) {
				//$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cp' );
			} else {
				//$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cp' );
				//$meta_text = '';
			}
		} else {
			// But this blog has loads of categories so we should probably display them here
//			if ( '' != $tag_list ) {
//				$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cp' );
//			} else {
//				$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cp' );
//			}
		} // end check for categories on this blog

		printf(
		$meta_text, $category_list, $tag_list, get_permalink()
		);
		?>

		<?php edit_post_link( __( 'Edit', 'cp' ), '<span class="edit-link">', '</span>' ); ?>
    </footer><!-- .entry-meta -->
</article><!-- #post-## -->