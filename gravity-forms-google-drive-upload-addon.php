<?php
/**
 * Plugin Name:       Gravity Forms Google Drive Upload Add-on
 * Description:       Adds a custom Gravity Forms field that uploads a single file to Google Drive.
 * Version:           2.1
 * Author:            Hemant Jodhani
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package           GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}


require_once __DIR__ . '/includes/class-gfgd-bootstrap.php';
require_once __DIR__ . '/includes/class-gfgd-settings.php';
require_once __DIR__ . '/includes/class-gfgd-field.php';
require_once __DIR__ . '/includes/functions-validation.php';
require_once __DIR__ . '/includes/functions-upload.php';

add_action( 'gform_loaded', array( 'GF_Google_Drive_Bootstrap', 'load' ), 5 );

add_action( 'wp_enqueue_scripts', 'gfgd_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'gfgd_enqueue_assets' );

/**
 * Enqueue frontend and admin assets for the Google Drive upload field.
 *
 * Loads the CSS and JavaScript required for handling the custom
 * Gravity Forms Google Drive upload functionality on both the
 * frontend and in the WordPress admin.
 *
 *
 * @return void
 */
function gfgd_enqueue_assets() {
	wp_enqueue_style(
		'gfgd-upload',
		plugin_dir_url( __FILE__ ) . 'assets/css/gfgd-upload.css',
		array(),
        '2.1'   
    );

	wp_enqueue_script(
		'gfgd-upload',
		plugin_dir_url( __FILE__ ) . 'assets/js/gfgd-upload.js',
		array( 'jquery' ),
		'2.1',
		true
	);
}