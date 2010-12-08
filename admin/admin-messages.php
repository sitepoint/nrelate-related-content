<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for admin settings
 *
 * @package nrelate
 * @subpackage Functions
 */

function nr_admin_message_set(){
	
	// Let's write some messages
	// Simple create div with id adverify for nrelate to populate the content
		$msg = $msg . '<li id="adverify"></li>';
		
	 // Get admin options
	$admin_options = get_option('nrelate_admin_options');
	
	// get admin email address
	$admin_email = $admin_options['admin_email_address'];
	
	// Communication
	if ($admin_email == null) {
		$msg = $msg . '<li><div class="red">It\'s a good idea to provide nrelate with your email address. Check the box under the "Communication" area below.</div></li>';
		}

	echo $msg;
};
add_action ('nrelate_admin_messages','nr_admin_message_set');


		
?>