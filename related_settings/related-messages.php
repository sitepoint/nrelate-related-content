<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for this particular nrelate plugin
 *
 * @package nrelate
 * @subpackage Functions
 */

function nr_rc_message_set(){

	 // Get related options
	$related_options = get_option('nrelate_related_options');
	
	 // Get admin options
	$admin_options = get_option('nrelate_admin_options');
	$admin_ad = $admin_options['admin_validate_ad']; // get ad ID
	
	$wp_root_nr=get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);

	// Related Thumbnail options
	$show_thumbnails = $related_options['related_thumbnail'];
	$thumbnailurl = $related_options['related_default_image'];
	// Related ad options
	$adcodeopt = $related_options['related_display_ad'];
	
	// Thumbnail
	if ($show_thumbnails == 'Thumbnails') {
		// Is there a default thumbnail set?
		if ($thumbnailurl == null || $thumbnailurl == '') {
				$msg = $msg . '<li><div class="red">Related Content is set to show thumbnails. It\'s a good idea to add a default image just in case a post does not have images in it. Add your <a href="admin.php?page=nrelate-related">default image here</a>.</div></li>';
		} else {
				$msg = $msg . '<li><div class="green">Related Content will show thumbnails, and default thumbnail is set.</div></li>';
		}
	};
	
	// Ad ID
	// Is ad ID entered?
	if ($admin_ad == null) {
			// Is ad option turned on for this plugin?
		if ($adcodeopt == 'on') {
			$msg = $msg . '<li><div class="red">Related content is set to show ads, but <b>your AD ID is blank</b>. Enter your Ad ID below, or <a href="http://www.nrelate.com/advalidate.php" target="_blank">sign up for an Ad ID here.</a></div></li>';
		}
		else{
			
		}
	};
	
	$msg=$msg. '<li id="adverify"></li>';
	// AJAX call to nrelate server to bring back ad code status
	echo '<script type="text/javascript"> checkad(\''.NRELATE_ADMIN_URL.'\',\''.$wp_root_nr.'\',\''.NRELATE_RELATED_ADMIN_VERSION.'\',\'adverify\',\''.$admin_ad.'\'); </script>';
	
	echo $msg;
};
add_action ('nrelate_admin_messages','nr_rc_message_set');


		
?>