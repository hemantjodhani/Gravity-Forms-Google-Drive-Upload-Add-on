<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GF_Google_Drive_Bootstrap {
	public static function load() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}
		GF_Google_Drive_Settings::get_instance();
		GF_Fields::register( new GF_Field_Google_Drive() );
	}
}