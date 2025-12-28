<?php
/**
 * Validation helpers for the Gravity Forms Google Drive Upload field.
 *
 * Hooks into Gravity Forms field validation to ensure required
 * Google Drive upload fields contain a file.
 *
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'gform_field_validation', 'gfgd_validate_field', 10, 4 );

/**
 * Validate the Google Drive upload field.
 *
 * Ensures that a file is provided when the Google Drive upload
 * field is marked as required.
 *
 * @since 2.1
 *
 * @param array    $result Validation result array.
 * @param mixed    $value  Field value.
 * @param array    $form   The current form object.
 * @param GF_Field $field  The current field object.
 *
 * @return array
 */
function gfgd_validate_field( $result, $value, $form, $field ) {
	if ( 'google_drive_upload' === $field->type && $field->isRequired ) {
		if ( empty( $_FILES[ 'input_' . $field->id ]['name'] ) ) {
			$result['is_valid'] = false;
			$result['message']  = empty( $field->errorMessage )
				? __( 'This field is required.', 'gravity-forms-google-drive-upload-addon' )
				: $field->errorMessage;
		}
	}

	return $result;
}
