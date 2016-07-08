<?php
global $fieldpress;

//$fieldpress->register_external_plugins();
if ( ! FieldPress_Capabilities::is_campus() ) {
	$activation = new CP_Plugin_Activation_Leaflet();
	$activation->install_plugins_page_leaflet();
}