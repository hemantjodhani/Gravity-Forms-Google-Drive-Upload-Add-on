<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GF_Google_Drive_Settings extends GFAddOn {
	protected $_version                  = '2.1';
	protected $_min_gravityforms_version = '2.5';
	protected $_slug                     = 'gfgd';
	protected $_path                     = __FILE__;
	protected $_full_path                = __FILE__;
	protected $_title                    = 'Google Drive';
	protected $_short_title              = 'Google Drive';

	private static $_instance = null;

	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

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