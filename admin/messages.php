<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for all nrelate plugins
 *
 * @package nrelate
 * @subpackage Functions
 */

  
//////////////////////////////////////////////
////////// Related Content Messages //////////
/////////////////////////////////////////////

// Check if RSS feeds are set to excerpt
function nr_msg_rss_excerpt(){
 $excerptset = get_option('rss_use_excerpt'); 
						
	if ($excerptset != '0') {
		echo '<li class="red">RSS Feeds are set to display excerpts.  nrelate works better when it can read your full feed.  Change your feed settings <a href="options-reading.php">here</a>.</li>';
		update_option('nrelate_admin_msg', 'yes');
	} else {
		echo '<li class="green">RSS Feeds are set to full feed</li>';
		update_option('nrelate_admin_msg', 'no');
	}
};		
		
?>