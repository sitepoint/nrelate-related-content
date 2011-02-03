<?php
/**
 * nrelate Plugin Status
 *
 * Activation, Deactivation and Uninstall functions
 *
 * @package nrelate
 * @subpackage Functions
 */
 
  
 /**
 * Define default options for settings
 *
 * @since 0.1
 */
// Takes user's bookmarks with category name 'blogroll'
// Returns a string with all of the blogroll link urls separated by the less than character (<).
 function blogroll_1(){
	$bm = get_bookmarks( array(
		'category_name'  => 'Blogroll',
		'hide_invisible' => 1,
		'show_updated'   => 0,
		'include'        => null,
		'exclude'        => null,
		'search'         => '.'));


	foreach ($bm as $bookmark){
		$tmp.=$bookmark->link_url.'<';
	}
	return $tmp;
}

 
 
 // Add default values to nrelate_related_options in wordpress db
// After conversion, send default values to nrelate server with user's home url and rss url
// UPDATE (v.0.2.2): add nrelate ping host to ping list and enable xml-rpc ping
// UPDATE (v.0.2.2): notify nrelate server when this plugin is activated
// UPDATE (v.0.3): send the plugin version info to nrelate server
function add_defaults_nr_rc() {

	$tmp = get_option('nrelate_related_options');
	// If related_reset value is on or if nrelate_related_options was never created, insert default values
    if(($tmp['related_reset']=='on')||(!is_array($tmp))) {
		$arr = array(
		"related_number_of_posts"=> 3,
		"related_bar" => "Low",
		"related_title" => "You may also like -",
		"related_max_age_num" => "10",
		"related_max_age_frame" => "Year(s)",
		"related_display_ad" => false,
		"related_loc_bottom" => "on",
		"related_display_logo" => true,
		"related_reset" => "",
		"related_blogoption" => "Off",
		"related_max_chars_per_line" => 100,
		"related_thumbnail" => "Thumbnails",
		"related_thumbnail_size" => 110,
		"related_default_image" => NULL,
		"related_number_of_posts_ext" => 3,
		"related_validate_ad" => NULL
		);
		update_option('nrelate_related_options', $arr);

		// Convert some values to send to nrelate server
		$number = 3;
		$r_bar = "Low";
		$r_title = "You may also like -";
		$r_max_age = 10;
		$r_max_frame = "Year(s)";
		$r_max_char_per_line = 100;
		$r_display_ad = false;
		$r_display_logo = true;
		$r_related_reset = "";
		$related_blogoption = "Off";
		$related_thumbnail = "Thumbnails";
		$r_validate_ad = NULL;
		$backfillimage = NULL;
		$number_ext = 3;
		$related_thumbnail_size=110;

		// Convert max age time frame to minutes
		switch ($r_max_frame)
		{
		case 'Hour(s)':
		  $maxageposts = $r_max_age * 60;
		  break;
		case 'Day(s)':
		  $maxageposts = $r_max_age * 1440;
		  break;
		case 'Week(s)':
		  $maxageposts = $r_max_age * 10080;
		  break;
		case 'Month(s)':
		  $maxageposts = $r_max_age * 44640;
		  break;
		case 'Year(s)':
		  $maxageposts = $r_max_age * 525600;
		  break;
		}

		// Convert ad parameter
		switch ($r_display_ad)
		{
		case true:
			$ad = 1;
			break;
		default:
			$ad = 0;
		}

		// Convert logo parameter
		switch ($r_display_logo)
		{
		case 'on':
		  $logo = 1;
		  break;
		default:
		 $logo = 0;
		}

		// Convert blogroll option parameter
		switch ($related_blogoption)
		{
		case 'Off':
		  $blogroll = 0;
		  break;
		default:
		 $blogroll = 1;
		}

		// Convert thumbnail option parameter
		switch ($related_thumbnail)
		{
		case 'Thumbnails':
			$thumb = 1;
			break;
		default:
			$thumb = 0;
		}

		// Get the wordpress root url and the rss url
		$wp_root_nr=get_bloginfo( 'url' );
		$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
		$bloglist = blogroll_1();
		// Write the parameters to be sent
		$curlPost = 'DOMAIN='.$wp_root_nr.'&NUM='.$number.'&HDR='.$r_title.'&R_BAR='.$r_bar.'&BLOGOPT='.$blogroll.'&BLOGLI='.$bloglist.'&MAXPOST='.$maxageposts.'&MAXCHAR='.$r_max_char_per_line.'&ADOPT='.$ad.'&THUMB='.$thumb.'&ADCODE='.$r_validate_ad.'&LOGO='.$logo.'&NUMEXT='.$number_ext.'&IMAGEURL='.$backfillimage.'&THUMBSIZE='.$related_thumbnail_size;
		// Curl connection to the nrelate server
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/rcw_wp/'.NRELATE_RELATED_PLUGIN_VERSION.'/processWPrelated.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);
		switch ($info['http_code']){
			case 200:
				$connection = 1;
				break;
			default:
				$connection = 0;
				break;
		}
		curl_close($ch);

		if($connection==0){
			//bad connection
		}

		//bad database response
		//echo $data;

	}

	// RSS mode is sent again just incase if the user already had nrelate_related_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Add our ping host to the ping list
	$current_ping_sites = get_option('ping_sites');
	$pingexist = strpos($current_ping_sites, "http://api.nrelate.com/rpcpinghost/");
	if($pingexist == false){
	$pinglist = <<<EOD
$current_ping_sites
http://api.nrelate.com/rpcpinghost/
EOD;
	update_option('ping_sites',$pinglist);
	}
	// Enable xmlrpc for the user
	update_option('enable_xmlrpc',1);


	//Set up a unique nrelate key, for secure feed access
	$key = get_option( 'nrelate_key' );
	if ( empty( $key ) ) {
		$key = wp_generate_password( 24, false, false );
		update_option( 'nrelate_key', $key );
	}



	// Send notification to nrelate server of activation and send us rss feed mode information
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$action = "ACTIVATE";
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	curl_exec($ch);
	curl_close($ch);

}
 
 
// Deactivation hook callback
function nrelate_deactivate(){

	if(function_exists('nrelate_popular')){
    	//popular plugin is activated, don't delete xmlrpc pinghost
	}
	else{
		// Remove our ping link from ping_sites
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
	}
	// RSS mode is sent again just incase if the user already had nrelate_related_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Send notification to nrelate server of deactivation
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$action = "DEACTIVATE";
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	curl_exec($ch);
	curl_close($ch);
}



//Uninstallation hook callback
function nrelate_uninstall(){
	// Delete nrelate related options from user's wordpress db
	delete_option('nrelate_related_options');
	delete_option('nrelate_admin_msg');

	if(function_exists('nrelate_popular')){
    	//popular plugin is activated, don't delete xmlrpc pinghost
	}
	else{
		// This occurs if the user is deleting all of nrelate's products
		// Delete nrelate admin options from users wordpress db
		delete_option('nrelate_admin_options');
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
	}

	// RSS mode is sent again just incase if the user already had nrelate_related_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Send notification to nrelate server of uninstallation
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$action = "UNINSTALL";
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	curl_exec($ch);
	curl_close($ch);
}

		
?>