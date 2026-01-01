<?php
/**
 * Gravity Forms Google Drive upload field validation.
 *
 * @package GF_Google_Drive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This filter runs during Gravity Forms server-side validation and processes
 * trusted form submission data. Gravity Forms handles nonce verification
 * internally, so an explicit nonce check is not required here.
 *
 * @phpcsIgnore WordPress.Security.NonceVerification.Recommended
 */
add_filter( 'gform_field_validation', 'gfgd_validate_field', 10, 4 );

/**
 * Validate the Google Drive upload field.
 *
 * @param array    $result The validation result.
 * @param string   $value  The value of the field.
 * @param array    $form   The form object.
 * @param GF_Field $field  The field object.
 *
 * @return array
 */
function gfgd_validate_field( $result, $value, $form, $field ) {
	if ( 'google_drive_upload' === $field->type && $field->isRequired ) {
		if ( empty( $_FILES[ 'input_' . $field->id ]['name'] ) ) {
			$result['is_valid'] = false;
			$result['message']  = empty( $field->errorMessage )
				? 'This field is required.'
				: $field->errorMessage;
		}
	}
	return $result;
}
