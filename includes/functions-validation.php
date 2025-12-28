<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'gform_field_validation', 'gfgd_validate_field', 10, 4 );

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