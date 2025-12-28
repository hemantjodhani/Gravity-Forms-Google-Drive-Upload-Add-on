<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GF_Field_Google_Drive extends GF_Field_FileUpload {
	public $type = 'google_drive_upload';

	public function get_form_editor_field_title() {
		return esc_html__( 'Google Drive Upload', 'gfgd' );
	}

	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => esc_html__( 'Google Drive Upload', 'gfgd' ),
		);
	}

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
					name="input_<?php echo $field_id; ?>"
					id="<?php echo $input_id; ?>"
					class="gfgd-drop-zone__input"
					<?php echo $accept ? 'accept="' . esc_attr( $accept ) . '"' : ''; ?>
				/>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}