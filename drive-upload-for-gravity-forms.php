<?php
/**
 * Plugin Name: Drive Upload for Gravity Forms (Google Drive)
 * Description: Adds a custom Gravity Forms field that uploads a single file to Google Drive.
 * Version: 2.1
 * Author: Hemant Jodhani
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: drive-upload-for-gravity-forms
 *
 * @package DUGF_Field_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Activation Check: Prevent activation if Gravity Forms is missing.
 */
function dugf_check_gravity_forms_dependency() {
    if ( ! class_exists( 'GFCommon' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        
        wp_die(
            esc_html__( 'This plugin requires Gravity Forms to be installed and activated.', 'drive-upload-for-gravity-forms' ),
            'Plugin Dependency Error',
            array( 'back_link' => true )
        );
    }
}
register_activation_hook( __FILE__, 'dugf_check_gravity_forms_dependency' );

/**
 * 2. Runtime Check: Deactivate if Gravity Forms is deactivated later.
 */
add_action( 'admin_init', 'dugf_check_runtime_dependency' );
function dugf_check_runtime_dependency() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) && ! class_exists( 'GFCommon' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="error"><p>' . esc_html__( 'Drive Upload for Gravity Forms has been disabled because Gravity Forms is not active.', 'drive-upload-for-gravity-forms' ) . '</p></div>';
        });
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/includes/dugf-class-google-drive-bootstrap.php';
require_once __DIR__ . '/includes/dugf-functions-validation.php';
require_once __DIR__ . '/includes/dugf-functions-upload.php';

add_action( 'gform_loaded', array( 'DUGF_Google_Drive_Bootstrap', 'load' ), 5 );

add_action( 'wp_enqueue_scripts', 'dugf_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'dugf_enqueue_assets' );

/**
 * Enqueues plugin assets.
 */
function dugf_enqueue_assets() {
    wp_enqueue_style(
        'dugf-upload',
        plugin_dir_url( __FILE__ ) . 'assets/css/dugf-upload.css',
        array(),
        '2.1'
    );

    wp_enqueue_script(
        'dugf-upload',
        plugin_dir_url( __FILE__ ) . 'assets/js/dugf-upload.js',
        array( 'jquery' ),
        '2.1',
        true
    );
}