<?php
/**
 * Plugin Name: Gravity Forms Google Drive Upload Add-on
 * Description: Modern drag & drop Google Drive upload field (Single File Only).
 * Version: 2.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Load Google API Client Library
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

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
        GF_Google_Drive_Settings::get_instance();
        GF_Fields::register(new GF_Field_Google_Drive());
    }
}

/**
 * Plugin Settings (GLOBAL)
 */
class GF_Google_Drive_Settings extends GFAddOn
{
    protected $_version = '2.1';
    protected $_min_gravityforms_version = '2.5';
    protected $_slug = 'gfgd';
    protected $_path = __FILE__;
    protected $_full_path = __FILE__;
    protected $_title = 'Google Drive';
    protected $_short_title = 'Google Drive';

    private static $_instance = null;
    public static function get_instance() {
        if (self::$_instance == null) { self::$_instance = new self(); }
        return self::$_instance;
    }

    public function plugin_settings_fields() {
        return [[
            'title'  => 'Google Drive API Settings',
            'fields' => [
                ['name' => 'client_id', 'label' => 'Client ID', 'type' => 'text', 'class' => 'large'],
                ['name' => 'client_secret', 'label' => 'Client Secret', 'type' => 'text', 'class' => 'large'],
                ['name' => 'refresh_token', 'label' => 'Refresh Token', 'type' => 'text', 'class' => 'large'],
                ['name' => 'folder_id', 'label' => 'Folder ID', 'type' => 'text', 'class' => 'large'],
            ],
        ]];
    }
}

/**
 * Custom Upload Field
 */
class GF_Field_Google_Drive extends GF_Field_FileUpload
{
    public $type = 'google_drive_upload';

    public function get_form_editor_field_title() { return esc_html__('Google Drive Upload', 'gfgd'); }

    public function get_form_editor_button() {
        return ['group' => 'advanced_fields', 'text'  => esc_html__('Google Drive Upload', 'gfgd')];
    }

    public function get_form_editor_field_settings() {
        return ['label_setting', 'rules_setting', 'required_setting', 'error_message_setting', 'css_class_setting', 'file_extensions_setting', 'file_size_setting'];
    }

    public function get_field_input($form, $value = '', $entry = null) {
        $allowed_extensions = trim((string) $this->allowedExtensions);
        $accept = $allowed_extensions ? implode(',', array_map(fn($ext) => '.' . strtolower(trim($ext)), explode(',', $allowed_extensions))) : '';

        $field_id = absint($this->id);
        $input_id = "input_{$form['id']}_{$field_id}";
        
        ob_start(); ?>
        <div class="gfgd-upload-container" id="gfgd-container-<?php echo $field_id; ?>">
            <div class="gfgd-drop-zone">
                <div class="gfgd-drop-zone__content">
                    <div class="gfgd-drop-zone__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <span class="gfgd-drop-zone__prompt">Drop file or <strong>browse</strong></span>
                    <?php if ($allowed_extensions) : ?>
                        <p class="gfgd-drop-zone__note">Allowed: <?php echo esc_html(strtoupper($allowed_extensions)); ?></p>
                    <?php endif; ?>
                </div>
                <div class="gfgd-file-details" style="display:none;">
                    <div class="gfgd-files-list"></div>
                    <button type="button" class="gfgd-clear-btn">Clear</button>
                </div>
                <input type="file" name="input_<?php echo $field_id; ?>" id="<?php echo $input_id; ?>" 
                       class="gfgd-drop-zone__input" <?php echo $accept ? 'accept="' . esc_attr($accept) . '"' : ''; ?> />
            </div>
        </div>
        <?php return ob_get_clean();
    }
}

/**
 * Server-Side Validation (The replacement for HTML 'required')
 */
add_filter('gform_field_validation', 'gfgd_validate_field', 10, 4);
function gfgd_validate_field($result, $value, $form, $field) {
    if ($field->type === 'google_drive_upload' && $field->isRequired) {
        // Check if the file is missing in the $_FILES superglobal
        if (empty($_FILES['input_' . $field->id]['name'])) {
            $result['is_valid'] = false;
            $result['message'] = empty($field->errorMessage) ? 'This field is required.' : $field->errorMessage;
        }
    }
    return $result;
}

/**
 * Google Drive Upload Logic
 */
add_action('gform_after_submission', 'gfgd_process_google_drive_upload', 10, 2);
function gfgd_process_google_drive_upload($entry, $form) {
    $settings = GF_Google_Drive_Settings::get_instance()->get_plugin_settings();
    if (empty($settings['client_id']) || empty($settings['refresh_token'])) return;

    foreach ($form['fields'] as $field) {
        if ($field->type === 'google_drive_upload') {
            $file_url = rgar($entry, (string) $field->id);
            if (empty($file_url)) continue;

            $file_path = str_replace(get_site_url(), ABSPATH, $file_url);
            if (!file_exists($file_path)) $file_path = ABSPATH . str_replace(get_site_url(), '', $file_url);
            if (!file_exists($file_path)) continue;

            try {
                $client = new Client();
                $client->setClientId($settings['client_id']);
                $client->setClientSecret($settings['client_secret']);
                $client->setAccessType('offline');
                $client->setAccessToken($client->fetchAccessTokenWithRefreshToken($settings['refresh_token']));

                $driveService = new Drive($client);
                $fileMetadata = new DriveFile(['name' => basename($file_path), 'parents' => [$settings['folder_id']]]);

                $drive_file = $driveService->files->create($fileMetadata, [
                    'data' => file_get_contents($file_path),
                    'mimeType' => mime_content_type($file_path),
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink'
                ]);

                if (!empty($drive_file->webViewLink)) {
                    GFAPI::update_entry_field($entry['id'], $field->id, $drive_file->webViewLink);
                    unlink($file_path);
                }
            } catch (Exception $e) { error_log('GDrive Error: ' . $e->getMessage()); }
        }
    }
}

add_action('wp_enqueue_scripts', 'gfgd_enqueue_assets');
add_action('admin_enqueue_scripts', 'gfgd_enqueue_assets');
function gfgd_enqueue_assets() {
    wp_enqueue_style('gfgd-upload', plugin_dir_url(__FILE__) . 'assets/css/gfgd-upload.css', [], '2.1');
    wp_enqueue_script('gfgd-upload', plugin_dir_url(__FILE__) . 'assets/js/gfgd-upload.js', ['jquery'], '2.1', true);
}