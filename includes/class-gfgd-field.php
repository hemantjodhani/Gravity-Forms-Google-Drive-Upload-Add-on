<?php
/**
 * Gravity Forms custom field for uploading a file to Google Drive.
 *
 * Defines a custom Gravity Forms field type that extends the core
 * file upload field and provides a drag-and-drop UI for uploading
 * a single file to Google Drive.
 *
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

/**
 * Class GF_Field_Google_Drive
 *
 * Custom Gravity Forms field that handles Google Drive file uploads.
 *
 * @since 2.1
 */
class GF_Field_Google_Drive extends GF_Field_FileUpload {

	/**
	 * Field type name.
	 *
	 * Used internally by Gravity Forms to identify the field type.
	 *
	 * @since 2.1
	 *
	 * @var string
	 */
	public $type = 'google_drive_upload';

	/**
	 * Get the field title shown in the Gravity Forms editor.
	 *
	 * @since 2.1
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return esc_html__( 'Google Drive Upload', 'gravity-forms-google-drive-upload-addon' );
	}

	/**
	 * Define the button settings for the Gravity Forms editor.
	 *
	 * Controls where and how the field button appears when adding
	 * fields in the form editor.
	 *
	 * @since 2.1
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => esc_html__( 'Google Drive Upload', 'gravity-forms-google-drive-upload-addon' ),
		);
	}

	/**
	 * Get the settings available for this field in the form editor.
	 *
	 * @since 2.1
	 *
	 * @return array
	 */
	public function get_form_editor_field_settings() {
		return array(
			'label_setting',
			'rules_setting',
			'required_setting',
			'error_message_setting',
			'css_class_setting',
			'file_extensions_setting',
			'file_size_setting',
		);
	}

	/**
	 * Generate the field input HTML.
	 *
	 * Outputs the drag-and-drop upload interface and file input element
	 * used on the frontend form.
	 *
	 * @since 2.1
	 *
	 * @param array      $form  The current form object.
	 * @param string     $value The field value.
	 * @param array|null $entry The current entry object.
	 *
	 * @return string
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {
		$allowed_extensions = trim( (string) $this->allowedExtensions );
		$accept             = $allowed_extensions
			? implode(
				',',
				array_map(
					fn( $ext ) => '.' . strtolower( trim( $ext ) ),
					explode( ',', $allowed_extensions )
				)
			)
			: '';

		$field_id = absint( $this->id );
		$input_id = "input_{$form['id']}_{$field_id}";

		ob_start();
		?>
		<div class="gfgd-upload-container" id="gfgd-container-<?php echo esc_attr( $field_id ); ?>">
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
					<?php if ( $allowed_extensions ) : ?>
						<p class="gfgd-drop-zone__note">
							Allowed: <?php echo esc_html( strtoupper( $allowed_extensions ) ); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="gfgd-file-details" style="display:none;">
					<div class="gfgd-files-list"></div>
					<button type="button" class="gfgd-clear-btn">Clear</button>
				</div>

				<input
					type="file"
					name="input_<?php echo esc_attr( $field_id ); ?>"
					id="<?php echo esc_attr( $input_id ); ?>"
					class="gfgd-drop-zone__input"
					<?php echo $accept ? 'accept="' . esc_attr( $accept ) . '"' : ''; ?>
				/>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
