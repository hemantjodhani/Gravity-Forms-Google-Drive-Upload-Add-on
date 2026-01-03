<?php
/**
 * Plugin Name: Drive Upload for Gravity Forms (Google Drive)
 * Description: Adds a custom Gravity Forms field that uploads a single file to Google Drive.
 * Version: 2.1
 * Author: Hemant Jodhani
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/includes/class-gf-google-drive-bootstrap.php';
require_once __DIR__ . '/includes/class-gf-google-drive-settings.php';
require_once __DIR__ . '/includes/class-gf-field-google-drive.php';
require_once __DIR__ . '/includes/functions-validation.php';
require_once __DIR__ . '/includes/functions-upload.php';

add_action( 'gform_loaded', array( 'GF_Google_Drive_Bootstrap', 'load' ), 5 );

add_action( 'wp_enqueue_scripts', 'gfgd_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'gfgd_enqueue_assets' );

/**
 * Enqueues plugin assets (CSS and JS) for the Google Drive upload functionality.
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
