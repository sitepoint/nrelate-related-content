<?php
/**
 * nrelate common functions
 *
 * This file is located in all nrelate plugins
 * and will only load if another nrelate plugin did not load these functions first
 * 
 * @package nrelate
 * @subpackage Functions
 */
 
if ( !function_exists('nrelate_msg') ) {

/**
 * Check if there are dashboard messages
 * If yes, notify via admin notice
 */
function nrelate_dashboard_msg() { 

	$nrelate_admin_msg = get_option('nrelate_admin_msg'); 
		if ($nrelate_admin_msg == 'yes') {
			add_action('admin_notices', 'nrelate_msg');
		}
};
add_action('init', 'nrelate_dashboard_msg');


/**
 * Here's the admin notice
 */
function nrelate_msg() {
		echo "<div class=\"error fade\"><strong><p>nrelate needs help. Check your messages <a href=\"admin.php?page=nrelate-main\">here</a></p></strong></div>";
	return;
};


};
?>