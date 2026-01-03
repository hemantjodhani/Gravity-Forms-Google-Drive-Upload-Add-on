<?php
/**
 * GF Google Drive Settings
 *
 * @package drive-upload-for-gravity-forms
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles Google Drive add-on settings for Gravity Forms.
 *
 * @since 2.1
 */
class GF_Google_Drive_Settings extends GFAddOn {
	/**
	 * Version number of the add-on.
	 *
	 * @var string
	 */
	protected $_version = '2.1';
	/**
	 * Minimum required Gravity Forms version.
	 *
	 * @var string
	 */
	protected $_min_gravityforms_version = '2.5';
	/**
	 * Slug used to identify this add-on.
	 *
	 * @var string
	 */
	protected $_slug = 'drive-upload-for-gravity-forms';
	/**
	 * Path to this file.
	 *
	 * @var string
	 */
	protected $_path = __FILE__;
	/**
	 * Full path to this file.
	 *
	 * @var string
	 */
	protected $_full_path = __FILE__;
	/**
	 * The title of this add-on.
	 *
	 * @var string
	 */
	protected $_title = 'Google Drive';
	/**
	 * Short title used for menus and other places where a shorter title is appropriate.
	 *
	 * @var string
	 */
	protected $_short_title = 'Google Drive';

	/**
	 * Singleton instance of the class.
	 *
	 * @var GF_Google_Drive_Settings|null
	 */
	private static $instance = null;

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return GF_Google_Drive_Settings The singleton instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns the plugin settings fields.
	 *
	 * @return array The plugin settings fields configuration.
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				'title'  => 'Google Drive API Settings',
				'fields' => array(
					array(
						'name'  => 'client_id',
						'label' => 'Client ID',
						'type'  => 'text',
						'class' => 'large',
					),
					array(
						'name'  => 'client_secret',
						'label' => 'Client Secret',
						'type'  => 'text',
						'class' => 'large',
					),
					array(
						'name'  => 'refresh_token',
						'label' => 'Refresh Token',
						'type'  => 'text',
						'class' => 'large',
					),
					array(
						'name'  => 'folder_id',
						'label' => 'Folder ID',
						'type'  => 'text',
						'class' => 'large',
					),
				),
			),
		);
	}
}
