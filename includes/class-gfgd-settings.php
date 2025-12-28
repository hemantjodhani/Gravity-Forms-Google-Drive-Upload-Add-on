<?php
/**
 * Plugin settings for the Gravity Forms Google Drive Upload Add-on.
 *
 * Defines the global settings used to configure Google Drive API
 * credentials and upload destination.
 *
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class GF_Google_Drive_Settings
 *
 * Extends the Gravity Forms Add-On Framework to provide plugin-wide
 * configuration options for Google Drive integration.
 *
 * @since 2.1
 */
class GF_Google_Drive_Settings extends GFAddOn {

	/**
	 * Plugin version.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_version = '2.1';

	/**
	 * Minimum required Gravity Forms version.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_min_gravityforms_version = '2.5';

	/**
	 * Plugin slug.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_slug = 'gravity-forms-google-drive-upload-addon';

	/**
	 * Plugin file path.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_path = __FILE__;

	/**
	 * Full plugin file path.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_full_path = __FILE__;

	/**
	 * Plugin title.
	 *
	 * Displayed in the Gravity Forms admin UI.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_title = 'Google Drive';

	/**
	 * Short plugin title.
	 *
	 * Used in compact admin UI contexts.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	protected $_short_title = 'Google Drive';

	/**
	 * Singleton instance.
	 *
	 * @since 2.1
	 *
	 * @var GF_Google_Drive_Settings|null
	 */
	private static $_instance = null;

	/**
	 * Get the singleton instance of the settings class.
	 *
	 * @since 2.1
	 *
	 * @return GF_Google_Drive_Settings
	 */
	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Define plugin settings fields.
	 *
	 * Registers the Google Drive API credentials and configuration
	 * fields displayed in the Gravity Forms settings page.
	 *
	 * @since 2.1
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				/* translators: Settings section title in Gravity Forms admin. */
				'title'  => __( 'Google Drive API Settings', 'gravity-forms-google-drive-upload-addon' ),
				'fields' => array(
					array(
						'name'  => 'client_id',
						'label' => __( 'Client ID', 'gravity-forms-google-drive-upload-addon' ),
						'type'  => 'text',
						'class' => 'large',
					),
					array(
						'name'  => 'client_secret',
						'label' => __( 'Client Secret', 'gravity-forms-google-drive-upload-addon' ),
						'type'  => 'text',
						'class' => 'large',
					),
					array(
						'name'  => 'refresh_token',
						'label' => __( 'Refresh Token', 'gravity-forms-google-drive-upload-addon' ),
						'type'  => 'text',
						'class' => 'large',
					),
					array(
						'name'  => 'folder_id',
						'label' => __( 'Folder ID', 'gravity-forms-google-drive-upload-addon' ),
						'type'  => 'text',
						'class' => 'large',
					),
				),
			),
		);
	}
}
