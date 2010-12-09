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


if ( !function_exists('nrelate_setup_dashboard') ) {

/**
 * Define Admin constants
 */
		define( 'NRELATE_WEBSITE_FORUM_URL', 'http://nrelate.com/forum/' );

		define( 'NRELATE_DASHBOARD_FILE', plugin_basename( __FILE__ ) );
		define( 'NRELATE_ADMIN_DIR_NAME', trim( dirname( NRELATE_DASHBOARD_FILE ), '/' ) );
		define( 'NRELATE_ADMIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_ADMIN_DIR_NAME );
		define( 'NRELATE_ADMIN_URL', WP_PLUGIN_URL . '/' . NRELATE_ADMIN_DIR_NAME );
		define( 'NRELATE_ADMIN_IMAGES', NRELATE_ADMIN_URL . '/images' );		

/**
 * Setup Dashboard menu and menu page
 */
function nrelate_setup_dashboard() {

		require_once NRELATE_ADMIN_DIR . '/nrelate-admin-settings.php';
		require_once NRELATE_ADMIN_DIR . '/nrelate-main-menu.php';
		require_once NRELATE_ADMIN_DIR . '/admin-messages.php';
		add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_ADMIN_IMAGES . '/menu-logo.png');
		
		
};
add_action('admin_menu', 'nrelate_setup_dashboard');


/**
 * Load custom RSS feed
 */
require_once NRELATE_ADMIN_DIR . '/rss-feed.php';


/**
 * Add CSS for admin pages
 */
 function nrelate_admin_css() {
    echo '<link rel="stylesheet" type="text/css" href="' . NRELATE_ADMIN_URL . '/nrelate-admin.css" media="screen" />';
}
add_action('admin_head', 'nrelate_admin_css');


/**
 * Old to New Options for all plugins
 *
 * @param string $old_option - Old Option name
 * @param string $old_option_key - old Option key name
 * @param string $new_option - new Option name
 * @param string $new_option_key - new Option key name
 * 
 * Since v1.5
 */
function nrelate_upgrade_option($old_option, $old_option_key, $new_option, $new_option_key) {
    $old_value = get_option($old_option);
	$old_value = $old_value[$old_option_key];
	$old_value = ($old_value == false) ? array() : $old_value;
    if ($old_value != false) {
        $new_value = get_option($new_option);
        $new_value = ($new_value == false) ? array() : $new_value;

        $new_value[$new_option_key] = $old_value;
        update_option($new_option, $new_value);
    }
}

/* Old -> New options
 *
 */
 if (is_admin()) {
nrelate_upgrade_option('nrelate_related_options', 'related_custom_field', 'nrelate_admin_options', 'admin_custom_field');
}


};/* end !function_exists */


?>