<?php
/**
 * Gravity Forms Google Drive Upload Field Class
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
 * Google Drive Upload Field for Gravity Forms.
 *
 * Handles rendering and behavior of the Google Drive file-upload field.
 */
class GF_Field_Google_Drive extends GF_Field_FileUpload {
	/**
	 * Field type identifier.
	 *
	 * @var string
	 */
	public $type = 'google_drive_upload';

	/**
	 * Get the field title for the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return esc_html__( 'Google Drive Upload', 'drive-upload-for-gravity-forms' );
	}

	/**
	 * Get the form editor button configuration for this field.
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => esc_html__( 'Google Drive Upload', 'drive-upload-for-gravity-forms' ),
		);
	}

	/**
	 * Get the list of field settings for the form editor.
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
	 * Generate the HTML input markup for the Google Drive upload field.
	 *
	 * @param array      $form  The form object.
	 * @param string     $value The field value (default empty).
	 * @param array|null $entry The entry object (default null).
	 *
	 * @return string The rendered field input HTML.
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
						<span class="gfgd-drop-zone__prompt">
							<?php echo esc_html__( 'Drop file or', 'drive-upload-for-gravity-forms' ); ?>
							<strong><?php echo esc_html__( 'browse', 'drive-upload-for-gravity-forms' ); ?></strong>
						</span>

						<?php if ( $allowed_extensions ) : ?>
							<p class="gfgd-drop-zone__note">
								<?php
									printf(
										/* translators: %s is the list of allowed file extensions (e.g., JPG, PNG). */
										esc_html__( 'Allowed: %s', 'drive-upload-for-gravity-forms' ),
										esc_html( strtoupper( $allowed_extensions ) )
									);
								?>
							</p>
						<?php endif; ?>
					</div>

					<div class="gfgd-file-details" style="display:none;">
						<div class="gfgd-files-list"></div>
						<button type="button" class="gfgd-clear-btn">
							<?php echo esc_html__( 'Clear', 'drive-upload-for-gravity-forms' ); ?>
						</button>
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