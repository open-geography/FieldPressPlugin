<?php
global $wp;
$field_id			 = do_shortcode( '[get_parent_field_id]' );
$field_id			 = (int) $field_id;
$stop = new Stop();
$stop_id = $stop->get_stop_id_by_name( $wp->query_vars['stopname'], $field_id );
FieldPress::instance()->check_access( $field_id, $stop_id );
$paged = isset( $wp->query_vars['paged'] ) ? absint( $wp->query_vars['paged'] ) : 1;

echo do_shortcode( '[field_stop_archive_submenu]' );
?>
<h2><?php echo get_the_title( (int) $stop_id ); ?></h2>

<?php
echo do_shortcode( '[field_stop_page_title stop_id="' . $stop_id . '"]' );
?>

<?php
Stop_Module::get_modules_front( (int) $stop_id );
?>