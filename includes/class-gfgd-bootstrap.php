<?php
/**
 * Bootstrap class for the Gravity Forms Google Drive Upload Add-on.
 *
 * Responsible for loading the add-on only after Gravity Forms has
 * fully initialized and the Add-On Framework is available.
 *
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class GF_Google_Drive_Bootstrap
 *
 * Handles initialization of the Google Drive add-on by registering
 * settings and custom field types with Gravity Forms.
 *
 * @since 2.1
 */
class GF_Google_Drive_Bootstrap {

	/**
	 * Load the add-on components.
	 *
	 * Ensures the Gravity Forms Add-On Framework is available before
	 * registering settings and custom fields.
	 *
	 * @since 2.1
	 *
	 * @return void
	 */
	public static function load() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		GF_Google_Drive_Settings::get_instance();
		GF_Fields::register( new GF_Field_Google_Drive() );
	}
}
