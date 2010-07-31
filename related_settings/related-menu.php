<?php
/**
 * Plugin Admin File
 *
 * Settings for this plugin
 *
 * @package nrelate
 * @subpackage Functions
 */


/**
 * Add sub menu
 */
function nrelate_related_setup_admin() {

    // Add our submenu to the custom top-level menu:
	require_once NRELATE_RELATED_SETTINGS_DIR . '/nrelate-related-settings.php';
    add_submenu_page('nrelate-main', __('Related Content','nrelate'), __('Related Content','nrelate'), 'manage_options', 'nrelate-related', 'nrelate_related_do_page');

};
add_action('admin_menu', 'nrelate_related_setup_admin');



?>