<?php
/**
 * The notifications archive template file
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
	<h2><?php _e( 'Notifications', 'cp' ); ?></h2>
	<div class="clearfix"></div>

	<ul class="notification-archive-list">
		<?php
		$page = ( isset( $wp->query_vars['paged'] ) ) ? (int) $wp->query_vars['paged'] : 1;
		do_shortcode( '[field_notifications_loop]' );
		?>
		<?php if ( have_posts() ) { ?>
			<?php
			while ( have_posts() ) {
				the_post();
				remove_filter( 'the_content', 'wpautop' );
				?>
				<li>
					<div class="notification-archive-single-meta">
						<div class="notification-date">
							<span class="date-part-one"><?php echo get_the_date( 'M' ); ?></span><span class="date-part-two"><?php echo get_the_date( 'j' ); ?></span>
						</div>
						<span class="notification-meta-divider"></span>

						<div class="notification-time"><?php the_time(); ?></div>
					</div>
					<div class="notification-archive-single">
						<h1 class="notification-title"><?php the_title(); ?></h1>

						<div class="notification_author"><?php the_author(); ?></div>
						<div class="notification-content"><?php the_content(); ?></div>
					</div>
					<div class="clearfix"></div>
				</li>
			<?php
			}
		} else {
			?>
			<h1 class="zero-field-stops"><?php _e( "0 notifications. Please check back later.", "cp" ); ?></h1>
		<?php
		}
		?>
	</ul>
	<br clear="all"/>
<?php cp_numeric_posts_nav( 'navigation-pagination' ); ?>