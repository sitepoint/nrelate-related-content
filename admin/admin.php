<?php
/**
 * Admin Functions File
 *
 * Load admin plugin functions
 *
 * @package nrelate
 * @subpackage Functions
 */


// Hook for adding admin menus
add_action('admin_menu', 'nrelate_setup_admin_pages');

// action function for above hook
function nrelate_setup_admin_pages() {

    // Add main nrelate section if another nrelate plugin hasn't already created it
	if ( !function_exists('nrelate_main_section') ) {
	
		//load main menu
		require_once NRELATE_RELATED_ADMIN_DIR . '/nrelate-main-menu.php';
		add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_RELATED_ADMIN_IMAGES . '/menu-logo.png');
		
		//load messages
		require_once NRELATE_RELATED_ADMIN_DIR . '/messages.php';
	}

    // Add our submenu to the custom top-level menu:
	require_once NRELATE_RELATED_ADMIN_DIR . '/nrelate-related-settings.php';
    add_submenu_page('nrelate-main', __('Related Content','nrelate'), __('Related Content','nrelate'), 'manage_options', 'nrelate-related', 'nrelate_related_do_page');

};

?>