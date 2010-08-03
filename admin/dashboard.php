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
 * Temporarily resets the admin message to no
 * messages.php updates this to Yes if neccessary
 */
function nrelate_reset_admin_msg(){
	update_option('nrelate_admin_msg', 'no');
}
add_action ('init','nrelate_reset_admin_msg');


/**
 * Setup Dashboard menu and menu page
 */
function nrelate_setup_dashboard() {
	
		require_once NRELATE_RELATED_ADMIN_DIR . '/nrelate-main-menu.php';
		add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_RELATED_ADMIN_IMAGES . '/menu-logo.png');
		
};
add_action('admin_menu', 'nrelate_setup_dashboard');



};
?>