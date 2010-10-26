<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for this particular nrelate plugin
 *
 * @package nrelate
 * @subpackage Functions
 */

function nr_admin_message_set(){
	
	// Let's write some messages
	// Simple create div with id adverify for nrelate to populate the content
		$msg = $msg . '<div id="adverify"></div>';

	echo $msg;
};
add_action ('nrelate_admin_messages','nr_admin_message_set');


		
?>