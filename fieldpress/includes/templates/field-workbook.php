<?php
$field_id			 = do_shortcode( '[get_parent_field_id]' );
$field_id			 = (int) $field_id;
$fieldpress->check_access( $field_id );
echo do_shortcode( '[field_stop_archive_submenu]' );
?>