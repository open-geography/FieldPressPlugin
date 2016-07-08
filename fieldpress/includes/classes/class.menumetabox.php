<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'FieldPress_Menu_Metabox' ) ) {

	class FieldPress_Menu_Metabox {

		private static $instance = null;


		function __construct() {
			add_action( 'admin_init', array( $this, 'add_menu_metabox' ) );
		}

		public function add_menu_metabox() {
			add_meta_box(
				'fieldpress-menu-panel',
				__( 'FieldPress', 'cp' ),
				array( $this, 'nav_html' ),
				'nav-menus',
				'side',
				'default'
			);
		}

		public function nav_html() {
			global $_nav_menu_placeholder, $nav_menu_selected_id;

			$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : - 1;

			$current_tab    = 'cp-published';
			$post_type_name = 'field';

			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				// 'menu-item',
				'page-tab',
				'_wpnonce',
			);

			?>
			<div id="posttype-<?php echo $post_type_name; ?>" class="posttypediv">

				<ul id="posttype-<?php echo $post_type_name; ?>-tabs" class="posttype-tabs add-menu-item-tabs">
					<li <?php echo( 'cp-published' == $current_tab ? ' class="tabs"' : '' ); ?>>
						<a class="nav-tab-link" data-type="tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-cp-published" href="<?php if ( $nav_menu_selected_id ) {
							echo esc_url( add_query_arg( $post_type_name . '-tab', 'cp-published', remove_query_arg( $removed_args ) ) );
						} ?>#tabs-panel-posttype-<?php echo $post_type_name; ?>-cp-published">
							<?php _e( 'Field Trips', 'cp' ); ?>
						</a>
					</li>
					<li <?php echo( 'cp-special-pages' == $current_tab ? ' class="tabs"' : '' ); ?>>
						<a class="nav-tab-link" data-type="tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-cp-special-pages" href="<?php if ( $nav_menu_selected_id ) {
							echo esc_url( add_query_arg( $post_type_name . '-tab', 'cp-special-pages', remove_query_arg( $removed_args ) ) );
						} ?>#tabs-panel-posttype-<?php echo $post_type_name; ?>-cp-special-pages">
							<?php _e( 'Pages', 'cp' ); ?>
						</a>
					</li>
				</ul>
				<!-- .posttype-tabs -->

				<div id="tabs-panel-posttype-<?php echo $post_type_name; ?>-cp-published" class="tabs-panel <?php
				echo( 'cp-published' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
				?>">
					<ul id="<?php echo $post_type_name; ?>checklist-cp-published" class="categorychecklist form-no-clear">
						<?php

						$args = array(
							'order'          => 'ASC',
							'post_type'      => 'field',
							'post_mime_type' => '',
							'post_parent'    => '',
							'post_status'    => 'publish',
							'posts_per_page'  => -1,
						);

						$fields = get_posts( $args );

						foreach ( $fields as $field ) {
							$_nav_menu_placeholder = $_nav_menu_placeholder - 1;
							?>
							<li>
								<label class="menu-item-title">
									<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php echo $field->post_title; ?>
								</label>
								<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
								<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo $field->post_title; ?>">
								<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo get_permalink( $field->ID ) ?>">
								<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="fieldpress-menu-item fieldpress-menu-item-field">
							</li>
						<?php
						}
						?>
					</ul>
				</div>
				<!-- /.tabs-panel -->

				<div id="tabs-panel-posttype-<?php echo $post_type_name; ?>-cp-special-pages" class="tabs-panel <?php
				echo( 'cp-special-pages' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
				?>">
					<ul id="<?php echo $post_type_name; ?>checklist-cp-special-pages" class="categorychecklist form-no-clear">
						<?php $_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : - 1; ?>
						<?php $_nav_menu_placeholder = $_nav_menu_placeholder - 1; ?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php echo __( 'Field List', 'cp' ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo __( 'Field Trips', 'cp' ); ?>">
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo FieldPress::instance()->get_field_slug( true ); ?>">
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="fieldpress-menu-item fieldpress-menu-item-fields">
						</li>
						<?php $_nav_menu_placeholder = $_nav_menu_placeholder - 1; ?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php echo __( 'My Field Trips', 'cp' ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo __( 'My Field Trips', 'cp' ); ?>">
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo FieldPress::instance()->get_student_dashboard_slug( true ); ?>">
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="fieldpress-menu-item fieldpress-menu-item-dashboard">
						</li>
						<?php $_nav_menu_placeholder = $_nav_menu_placeholder - 1; ?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php echo __( 'My Profile', 'cp' ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo __( 'My Profile', 'cp' ); ?>">
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo FieldPress::instance()->get_student_settings_slug( true ); ?>">
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="fieldpress-menu-item fieldpress-menu-item-dashboard">
						</li>
						<?php $_nav_menu_placeholder = $_nav_menu_placeholder - 1; ?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php echo __( 'FieldPress Login', 'cp' ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo __( 'Login', 'cp' ); ?>">
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo FieldPress::instance()->get_login_slug( true ); ?>">
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="fieldpress-menu-item fieldpress-menu-item-login">
						</li>
						<?php $_nav_menu_placeholder = $_nav_menu_placeholder - 1; ?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php echo __( 'FieldPress Signup', 'cp' ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo __( 'Signup', 'cp' ); ?>">
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo FieldPress::instance()->get_signup_slug( true ); ?>">
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="fieldpress-menu-item fieldpress-menu-item-signup">
						</li>

					</ul>
				</div>
				<!-- /.tabs-panel -->

				<p class="button-controls">
					<span class="list-controls">
						<a href="<?php
						echo esc_url( add_query_arg(
							array(
								$post_type_name . '-tab' => 'cp-published',
								'selectall'              => 1,
							),
							remove_query_arg( $removed_args )
						) );
						?>#posttype-<?php echo $post_type_name; ?>" class="select-all"><?php _e( 'Select All', 'cp' ); ?></a>
					</span>
					<span class="add-to-menu">
						<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'cp' ); ?>" name="add-field-menu-item" id="<?php echo esc_attr( 'submit-posttype-' . $post_type_name ); ?>"/>
						<span class="spinner"></span>
					</span>
				</p>
			</div>
		<?php
		}

		public static function instance( $instance = null ) {
			if ( ! $instance || 'FieldPress_Menu_Metabox' != get_class( $instance ) ) {
				if ( is_null( self::$instance ) ) {
					self::$instance = new FieldPress_Menu_Metabox();
				}
			} else {
				if ( is_null( self::$instance ) ) {
					self::$instance = $instance;
				}
			}

			return self::$instance;
		}

	}

	// Initialise a new instance.
	FieldPress_Menu_Metabox::instance( new FieldPress_Menu_Metabox() );

}