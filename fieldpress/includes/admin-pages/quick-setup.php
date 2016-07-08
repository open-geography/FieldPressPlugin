<div class="wrap about-wrap cp-wrap">

	<h1><?php _e( 'Welcome to', 'cp' ); ?> <?php echo $this->name; ?></h1>

	<div class="about-text">
		<?php
		printf( __( '%s has done a few things to get you on your way.', 'cp' ), $this->name );
		?>
		<br/>
		<br/>
		<?php
		_e( 'It has created a submenu called "FieldPress" in your admin navigation. In the submenu you can create new field trips, change the plugin settings, and manage instructors and students. In addition, it has add a new theme called  "FieldPress" to your website. You should activate this theme as it has special features and user interfaces that allow the FieldPress plugin to function on your website.', 'cp' );
		?>
		<br/>
		<br/>
		<?php
		printf( __( 'FieldPress has also added two dynamic pages to your frontend website menu - "Field Trips" and "Dashboard". These allow students and instructors to interact with the field trips you create. If these are not visible on your site and theme, you may need to check your %s.', 'cp' ), '<a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Menu Settings', 'cp' ) . '</a>' );
		?>
		<br/>
		<br/>
		<?php
		printf( __( '%s has also packaged the Leaflet Map Marker Plugin as an option to add maps to your field trip. You can activate this plugin in the FieldPress->Settings.', 'cp' ), $this->name );
		?>
		<br/>
		<br/>

		<?php
        printf( __( 'A quick start guide and user manual for FieldPress can be found here: %s.', 'cp' ), '<a href="' . admin_url( 'https://pressbooks.bccampus.ca/fieldpress/' ) . '">' . __( 'https://pressbooks.bccampus.ca/fieldpress/', 'cp' ) . '</a>' );
        ?>
        <br/>
        <br/>

        <?php
        printf( __( 'More information about FieldPress, tutorials, tips, and links to open source code can be found at Open Geography UBC: %s.', 'cp' ), '<a href="' . admin_url( 'http://open.geog.ubc.ca/' ) . '">' . __( 'http://open.geog.ubc.ca/', 'cp' ) . '</a>' );
         ?>
        <br/>
        <br/>
	</div>

		<?php
		if ( current_user_can( 'manage_options' ) && ! get_option( 'permalink_stop' ) ) {
			// toplevel_page_fields
			$screen = get_current_screen();

			$show_warning = false;

			if ( 'toplevel_page_fields' == $screen->id && isset( $_GET['quick_setup'] ) ) {
				$show_warning = true;
			}

			if ( $show_warning ) {
				// echo '<div class="error"><p>' . __('<strong>' . $this->name . ' is almost ready</strong>. You must <a href="options-permalink.php">update your permalink stop</a> to something other than the default for it to work.', 'cp') . '</p></div>';
				?>
				<div class="permalinks-error">
					<h4><?php _e( 'Pretty permalinks are required to use FieldPress.', 'cp' ); ?></h4>

					<p><?php _e( 'Click the button below to setup your permalinks.', 'cp' ); ?></p>
					<a href="<?php echo admin_url( 'options-permalink.php' ); ?>" class="button button-stops save-stop-button setup-permalinks-button"><?php _e( 'Setup Permalinks', 'cp' ); ?></a>
				</div>
			<?php
			}
		} else {
			?>
			<a href="<?php echo admin_url( 'admin.php?page=field_details' ); ?>" class="button button-stops save-stop-button start-field-button"><?php _e( 'Start building your own field trip now &rarr;', 'cp' ); ?></a>
		<?php
		}
		?>

</div>
