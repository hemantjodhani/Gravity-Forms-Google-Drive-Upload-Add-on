<?php

/**
 * Plugin Name: Gravity Forms Google Drive Upload Add-on
 * Description: Modern drag & drop Google Drive upload field for Gravity Forms.
 * Version: 1.4
 */

if (! defined('ABSPATH')) {
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
        if (! method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        GFAddOn::register('GF_Google_Drive_Settings');
        GF_Fields::register(new GF_Field_Google_Drive());
    }
}

/**
 * Plugin Settings
 */
class GF_Google_Drive_Settings extends GFAddOn
{

    protected $_version = '1.4';
    protected $_min_gravityforms_version = '2.5';
    protected $_slug = 'gfgd';
    protected $_path = __FILE__;
    protected $_title = 'Google Drive Settings';
    protected $_short_title = 'Google Drive';

    public function plugin_settings_fields()
    {
        return [
            [
                'title'  => 'Google Drive API',
                'fields' => [
                    ['name' => 'client_id',     'label' => 'Client ID',     'type' => 'text'],
                    ['name' => 'client_secret', 'label' => 'Client Secret', 'type' => 'text'],
                    ['name' => 'refresh_token', 'label' => 'Refresh Token', 'type' => 'text'],
                    ['name' => 'folder_id',     'label' => 'Folder ID',     'type' => 'text'],
                ],
            ],
        ];
    }
}

/**
 * Custom Field
 */
class GF_Field_Google_Drive extends GF_Field
{

    public $type = 'google_drive_upload';

    /* ---------- ADMIN ---------- */

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
     * THIS enables Required toggle
     */
    public function get_form_editor_field_settings()
    {
        return [
            'label_setting',
            'description_setting',
            'rules_setting',
            'required_setting',
            'error_message_setting',
            'css_class_setting',
            'visibility_setting',
        ];
    }

    /**
     *  Tell GF this is a file upload field
     */
    public function get_input_type()
    {
        return 'fileupload';
    }

    /**
     *  Let Gravity Forms handle required validation
     */
    public function is_value_submission_empty($form_id)
    {
        $input_name = 'input_' . $this->id;

        return (
            ! isset($_FILES[$input_name]) ||
            empty($_FILES[$input_name]['name']) ||
            (is_array($_FILES[$input_name]['name']) && empty($_FILES[$input_name]['name'][0]))
        );
    }


    public function get_field_input($form, $value = '', $entry = null)
    {

        $form_id  = absint($form['id']);
        $field_id = absint($this->id);
        $input_id = "input_{$form_id}_{$field_id}";

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
                        Drop files or <strong>browse</strong>
                    </span>

                    <p class="gfgd-drop-zone__note">
                        PNG, JPG or PDF (MAX. 5MB)
                    </p>
                </div>

                <div class="gfgd-file-details" style="display:none;">
                    <div class="gfgd-files-list"></div>
                    <button type="button" class="gfgd-clear-btn">Clear</button>
                </div>

                <input type="file"
                    name="input_<?php echo esc_attr($field_id); ?>"
                    id="<?php echo esc_attr($input_id); ?>"
                    class="gfgd-drop-zone__input"
                    multiple />
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

/**
 * Assets
 */
add_action('init', function () {

    wp_enqueue_style(
        'gfgd-upload',
        plugin_dir_url(__FILE__) . 'assets/css/gfgd-upload.css',
        [],
        '1.4'
    );

    wp_enqueue_script(
        'gfgd-upload',
        plugin_dir_url(__FILE__) . 'assets/js/gfgd-upload.js',
        [],
        '1.4',
        true
    );
});
