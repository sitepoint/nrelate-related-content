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
	
	 // Get all options
	$related_options = get_option('nrelate_related_options');

	// Advertising options
	$ad_id = $related_options['related_validate_ad'];
	$display_ad = $related_options['related_display_ad'];
	
	// Thumbnail options
	$show_thumbnails = $related_options['related_thumbnail'];
	$thumbnailurl = $related_options['related_default_image'];
	
	// Let's write some messages
	
	// Ad ID
	if ($ad_id == null) {
		$msg = $msg . '<li class="red">Why not join the nrelate ad network and make some money? Look under <a href="admin.php?page=nrelate-related">Advertising Settings</a></li>';
	} else {
		$msg = $msg . '<li class="green">You joined the nrelate advertising program.</li>';
				// Is show ads checked?
				if ($display_ad == null) {
					$msg = $msg . '<li class="red">You joined the nrelate advertising program, but <a href="admin.php?page=nrelate-related">"Would you like to display ads?"</a> is not checked.</li>';
				}			
	};
	
	// Thumbnail
	if ($show_thumbnails == 'Thumbnails') {
		// Is there a default thumbnail set?
		if ($thumbnailurl == null) {
				$msg = $msg . '<li class="red">Related Content is set to show thumbnails. It\'s a good idea to add a default image just in case no image exists in a post. Add your <a href="admin.php?page=nrelate-related">default image here</a>.</li>';
		} else {
				$msg = $msg . '<li class="green">Related Content will show thumbnails, and default thumbnail is set.</li>';
		}
	};	
	

	echo $msg;
};
add_action ('nrelate_admin_messages','nr_rc_message_set');


		
?>