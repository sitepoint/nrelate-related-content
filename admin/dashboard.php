<?php
/**
 * Dashboard Functions File
 *
 * Load dashboard common functions
 *
 * Checks if another nrelate plugin loaded these functions first
 * 
 * @package nrelate
 * @subpackage Functions
 */

if ( !function_exists('nrelate_main_section') ) {

/**
 * Setup Dashboard menu and menu page
 */
function nrelate_setup_dashboard() {
	
		require_once NRELATE_RELATED_ADMIN_DIR . '/nrelate-main-menu.php';
		add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_RELATED_ADMIN_IMAGES . '/menu-logo.png');
		
};
add_action('admin_menu', 'nrelate_setup_dashboard');

// Load custom RSS feed
require_once NRELATE_RELATED_ADMIN_DIR . '/rss-feed.php';


};
?>