<?php
/**
 * Gravity Forms Google Drive Upload Handler
 *
 * Processes file uploads from Gravity Forms entries and uploads them to Google Drive.
 *
 * @package GF_Google_Drive
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gravity Forms performs nonce verification before triggering
 * gform_after_submission. This is a trusted hook.
 */
// phpcs:ignore WordPress.Security.NonceVerification.Missing
add_action( 'gform_after_submission', 'gfgd_process_google_drive_upload', 10, 2 );

/**
 * Process Google Drive uploads after Gravity Forms submission.
 *
 * Handles uploading files from Gravity Forms entries to Google Drive
 * when a form contains a google_drive_upload field type.
 *
 * @since 1.0.0
 *
 * @param array $entry The entry object created from the submission.
 * @param array $form  The form object.
 */
function gfgd_process_google_drive_upload( $entry, $form ) {
	$settings = GF_Google_Drive_Settings::get_instance()->get_plugin_settings();

	if ( empty( $settings['client_id'] ) || empty( $settings['refresh_token'] ) ) {
		return;
	}

	foreach ( $form['fields'] as $field ) {
		if ( 'google_drive_upload' !== $field->type ) {
			continue;
		}

		$file_url = rgar( $entry, (string) $field->id );
		if ( empty( $file_url ) ) {
			continue;
		}

		$file_path = str_replace( get_site_url(), ABSPATH, $file_url );

		if ( ! file_exists( $file_path ) ) {
			$file_path = ABSPATH . str_replace( get_site_url(), '', $file_url );
		}

		if ( ! file_exists( $file_path ) ) {
			continue;
		}

		try {
			$client = new \Google\Client();
			$client->setClientId( $settings['client_id'] );
			$client->setClientSecret( $settings['client_secret'] );
			$client->setAccessType( 'offline' );
			$client->setAccessToken(
				$client->fetchAccessTokenWithRefreshToken( $settings['refresh_token'] )
			);

			$drive_service = new \Google\Service\Drive( $client );

			$file_metadata = new \Google\Service\Drive\DriveFile(
				array(
					'name'    => basename( $file_path ),
					'parents' => array( $settings['folder_id'] ),
				)
			);

			$drive_file = $drive_service->files->create(
				$file_metadata,
				array(
					'data'       => file_get_contents( $file_path ),
					'mimeType'   => mime_content_type( $file_path ),
					'uploadType' => 'multipart',
					'fields'     => 'id, webViewLink',
				)
			);

			if ( ! empty( $drive_file->webViewLink ) ) {
				GFAPI::update_entry_field( $entry['id'], $field->id, $drive_file->webViewLink );
				wp_delete_file( $file_path );
			}
		} catch ( Exception $e ) {

		}
	}
}
