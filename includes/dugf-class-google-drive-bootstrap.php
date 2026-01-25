<?php
/**
 * Bootstrap file for the Gravity Forms Google Drive add-on.
 *
 * @package DUGF_Field_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bootstrap class for the Gravity Forms Google Drive add-on.
 *
 * Responsible for initializing the add-on by loading settings and registering the custom field.
 */
class DUGF_Google_Drive_Bootstrap {

	/**
	 * Loads the Google Drive add-on by initializing settings and registering the custom field.
	 *
	 * @return void
	 */
	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}


		GFForms::include_addon_framework();

		require_once __DIR__ . '/class-gf-google-drive-settings.php';
		require_once __DIR__ . '/class-gf-field-google-drive.php';

		DUGF_Google_Drive_Settings::get_instance();

		GF_Fields::register( new DUGF_Field_Google_Drive() );
	}
}