<?php
/**
 * Plugin Name: Gravity Forms Google Drive Upload Add-on
 * Description: Adds a custom Gravity Forms field that uploads a single file to Google Drive.
 * Version: 2.1
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load Google API Client FIRST - before anything else
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Now load all plugin files
require_once __DIR__ . '/includes/class-gfgd-bootstrap.php';
require_once __DIR__ . '/includes/class-gfgd-settings.php';
require_once __DIR__ . '/includes/class-gfgd-field.php';
require_once __DIR__ . '/includes/functions-validation.php';
require_once __DIR__ . '/includes/functions-upload.php';

// Register bootstrap
add_action( 'gform_loaded', array( 'GF_Google_Drive_Bootstrap', 'load' ), 5 );

// Enqueue assets
add_action( 'wp_enqueue_scripts', 'gfgd_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'gfgd_enqueue_assets' );

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