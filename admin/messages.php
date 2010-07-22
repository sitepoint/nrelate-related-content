<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for this particular nrelate plugin
 *
 * @package nrelate
 * @subpackage Functions
 */


/**
 * Check WordPress for issues that may effect plugin
 *
 * If issues are found, change nrelate_admin_msg to "yes"
 */
function nr_rc_system_check(){
 $excerptset = get_option('rss_use_excerpt'); 
						
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
	}
};
add_action ('init','nr_rc_system_check');

 
 
 
/**
 * Set messages for dashboard
 *
 * All individual message get added here
 */
function nr_rc_message_set(){
 $nrelate_admin_msg = get_option('nrelate_admin_msg'); 
						
	if ($nrelate_admin_msg == 'yes') {
		$msg = $msg . '<li class="red">RSS Feeds are set to display excerpts. <strong>nrelate related content</strong> works better when it can read your full feed.  Change your feed settings <a href="options-reading.php">here</a>.</li>';
	} else {
		$msg = $msg . '<li class="green">RSS Feeds are set to full feed</li>';
	};

	
	
	echo $msg;
};
add_action ('nrelate_admin_messages','nr_rc_message_set');


		
?>