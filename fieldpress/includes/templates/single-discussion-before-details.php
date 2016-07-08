<?php
global $post, $fieldpress;
$field_id = get_post_meta( $post->ID, 'field_id', true );
//redirect to the parent field page if not enrolled
$fieldpress->check_access( $field_id );

echo do_shortcode( '[field_stop_archive_submenu field_id="' . $field_id . '"]' );
?>