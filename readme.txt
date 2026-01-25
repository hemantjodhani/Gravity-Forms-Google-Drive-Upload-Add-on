=== Drive Upload for Gravity Forms (Google Drive) ===
Contributors: hemantjodhani
Tags: gravity forms, google drive, file upload, integration
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.1
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically sync Gravity Forms file uploads to Google Drive. Securely store and manage attachments in the cloud.

== Description ==

Drive Upload for Gravity Forms seamlessly integrates Google Drive with your Gravity Forms. Automatically upload files and form data directly to your Google Drive when users submit forms.

**Features:**
- Direct file uploads to Google Drive
- Automatic folder organization
- Support for multiple file types
- Easy Google authentication
- Form field mapping

== Video Tutorial ==

Watch this step-by-step guide to setting up the plugin, including how to get your Google API Client ID, Secret, and Refresh Token.

[youtube https://youtu.be/cf5WjW0TQSE]

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin panel
3. Go to Forms > Settings > Drive Upload
4. Authenticate with your Google account
5. Configure your upload settings

== Configuration ==

1. Connect your Google Drive account
2. Select destination folder
3. Map form fields to Drive metadata
4. Save and test with a form submission

== Support ==

For support, visit the plugin documentation or contact support.

== Screenshots ==

1. Google Drive API Settings: Configure your Client ID, Client Secret, and destination Folder ID in the main settings.
2. Easy Integration: Drag and drop the "Google Drive Upload" field from the Advanced Fields section in the form editor.
3. Field Configuration: Customize file restrictions, including allowed extensions and maximum file size limits directly in the field settings.
4. Seamless Uploads: Submissions are automatically uploaded and organized in your connected Google Drive folder.

== Changelog ==

= 2.1 =
* Fixed: Fatal error "Class GFAddOn not found" by ensuring Gravity Forms is fully loaded before initialization.
* Fixed: Resolved text domain mismatch issues.
* Updated: Renamed all classes and functions to use the unique `DUGF_` prefix.
* Updated: Added `composer.json` for dependency management.

= 1.0.0 =
* Initial release

== License ==

This plugin is licensed under the GPL v2 or later.