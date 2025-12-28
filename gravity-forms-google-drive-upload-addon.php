<?php

/**
 * Plugin Name: Gravity Forms Google Drive Upload Add-on
 * Description: Modern drag & drop Google Drive upload field (Single File Only).
 * Version: 1.5
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bootstrap
 */
add_action('gform_loaded', ['GF_Google_Drive_Bootstrap', 'load'], 5);

class GF_Google_Drive_Bootstrap
{
    public static function load()
    {
        if (!method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        // Initialize the Settings Add-On
        GF_Google_Drive_Settings::get_instance();

        // Register the custom field
        GF_Fields::register(new GF_Field_Google_Drive());
    }
}

/**
 * ============================
 * Plugin Settings (GLOBAL)
 * ============================
 */
class GF_Google_Drive_Settings extends GFAddOn
{
    protected $_version = '1.5';
    protected $_min_gravityforms_version = '2.5';
    protected $_slug = 'gfgd';
    protected $_path = __FILE__;
    protected $_full_path = __FILE__;
    protected $_title = 'Google Drive';
    protected $_short_title = 'Google Drive';

    private static $_instance = null;

    public static function get_instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected $_capabilities_settings_page = 'gravityforms_edit_settings';

    public function init()
    {
        parent::init();
    }

    public function plugin_settings_fields()
    {
        return [
            [
                'title'  => 'Google Drive API Settings',
                'fields' => [
                    [
                        'name'  => 'client_id',
                        'label' => 'Client ID',
                        'type'  => 'text',
                        'class' => 'large',
                    ],
                    [
                        'name'  => 'client_secret',
                        'label' => 'Client Secret',
                        'type'  => 'text',
                        'class' => 'large',
                    ],
                    [
                        'name'  => 'refresh_token',
                        'label' => 'Refresh Token',
                        'type'  => 'text',
                        'class' => 'large',
                    ],
                    [
                        'name'  => 'folder_id',
                        'label' => 'Folder ID',
                        'type'  => 'text',
                        'class' => 'large',
                    ],
                ],
            ],
        ];
    }
}

/**
 * ============================
 * Custom Upload Field
 * ============================
 */
class GF_Field_Google_Drive extends GF_Field_FileUpload
{
    public $type = 'google_drive_upload';

    public function get_form_editor_field_title()
    {
        return esc_html__('Google Drive Upload', 'gfgd');
    }

    public function get_form_editor_button()
    {
        return [
            'group' => 'advanced_fields',
            'text'  => esc_html__('Google Drive Upload', 'gfgd'),
        ];
    }

    /**
     * Removed 'multiple_files_setting' to prevent users from enabling it.
     */
    public function get_form_editor_field_settings()
    {
        return [
            'label_setting',
            'rules_setting',
            'required_setting',
            'error_message_setting',
            'css_class_setting',
            'file_extensions_setting',
            'file_size_setting',
        ];
    }

    public function get_field_input($form, $value = '', $entry = null)
    {
        $allowed_extensions = trim((string) $this->allowedExtensions);
        $accept = '';

        if ($allowed_extensions) {
            $types  = array_map('trim', explode(',', $allowed_extensions));
            $accept = implode(',', array_map(fn($ext) => '.' . strtolower($ext), $types));
        }

        $form_id     = absint($form['id']);
        $field_id    = absint($this->id);
        $input_id    = "input_{$form_id}_{$field_id}";
        $name        = 'input_' . $field_id; // Single file naming convention

        ob_start();
?>
        <div class="gfgd-upload-container">
            <div class="gfgd-drop-zone">
                <div class="gfgd-drop-zone__content">
                    <div class="gfgd-drop-zone__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <span class="gfgd-drop-zone__prompt">
                        Drop file or <strong>browse</strong>
                    </span>
                    <?php if ($allowed_extensions) : ?>
                        <p class="gfgd-drop-zone__note">
                            Allowed: <?php echo esc_html(strtoupper($allowed_extensions)); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="gfgd-file-details" style="display:none;">
                    <div class="gfgd-files-list"></div>
                    <button type="button" class="gfgd-clear-btn">Clear</button>
                </div>
                <input type="file"
                    name="<?php echo esc_attr($name); ?>"
                    id="<?php echo esc_attr($input_id); ?>"
                    class="gfgd-drop-zone__input"
                    <?php echo $accept ? 'accept="' . esc_attr($accept) . '"' : ''; ?> />
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

/**
 * Assets
 */
add_action('wp_enqueue_scripts', 'gfgd_enqueue_assets');
add_action('admin_enqueue_scripts', 'gfgd_enqueue_assets');

function gfgd_enqueue_assets()
{
    wp_enqueue_style(
        'gfgd-upload',
        plugin_dir_url(__FILE__) . 'assets/css/gfgd-upload.css',
        [],
        '1.5'
    );

    wp_enqueue_script(
        'gfgd-upload',
        plugin_dir_url(__FILE__) . 'assets/js/gfgd-upload.js',
        ['jquery'],
        '1.5',
        true
    );
}
