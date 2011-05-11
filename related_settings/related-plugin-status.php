<?php
/**
 * nrelate Plugin Status
 *
 * Activation, Deactivation and Upgrade functions
 *
 * @package nrelate
 * @subpackage Functions
 */
 
 

global $nr_rc_std_options, $nr_rc_layout_options, $nr_rc_old_checkbox_options;

// Default Options
// ALL options must be listed
$nr_rc_std_options = array(
		"related_version" => NRELATE_RELATED_PLUGIN_VERSION,
		"related_number_of_posts"=> 3,
		"related_bar" => "Low",
		"related_title" => "You may also like -",
		"related_max_age_num" => "10",
		"related_max_age_frame" => "Year(s)",
		"related_display_ad" => false,
		"related_ad_animation" => "on",
		"related_loc_top" => "",
		"related_loc_bottom" => "on",
		"related_display_logo" => true,
		"related_reset" => "",
		"related_blogoption" => "Off",
		"related_show_post_title" => 'on',
		"related_max_chars_per_line" => 100,
		"related_show_post_excerpt" => "",
		"related_max_chars_post_excerpt" => 25,		
		"related_thumbnail" => "Thumbnails",
		"related_thumbnail_size" => 110,
		"related_default_image" => NULL,
		"related_number_of_posts_ext" => 3,
		"related_validate_ad" => NULL,
		"related_number_of_ads" => 1,
		"related_ad_placement" => "Mixed"
	);
		
$nr_rc_layout_options = array(		
		"related_thumbnails_style" => "default",
		"related_text_style" => "default"
);


/**
 * Backwards compatibility
 * Stores all checkbox options for versions <= 0.46.0
 * This should never have to be changed.
 */ 
$nr_rc_old_checkbox_options = array(
		"related_show_post_title" => "",	// Since 0.46.0 default on
		"related_ad_animation" => "",		// Since 0.46.0 default on
		"related_show_post_excerpt" => "",
		"related_reset" => "",
		"related_loc_top" => "",
		"related_loc_bottom" => "",
		"related_display_logo" => "",
		"related_display_ad" => ""
);



/**
 * Upgrade function
 *
 * @since 0.46.0
 */
add_action('admin_init','nr_rc_upgrade');
function nr_rc_upgrade() {
	$related_settings = get_option('nrelate_related_options');
	$related_layout_settings = get_option('nrelate_related_options_styles');
	$current_version = $related_settings['related_version'];
	
	// If settings exist and we're running on old version (or version doesn't exist), then this is an upgrade
	if ( ( !empty( $related_settings ) ) && ( $current_version < NRELATE_RELATED_PLUGIN_VERSION ) )  {
	
		nrelate_system_check(); // run system check
		
		global $nr_rc_std_options, $nr_rc_layout_options, $nr_rc_old_checkbox_options;
			
			// move custom field option from related settings to admin settings: v.0.42.2
			nrelate_upgrade_option('nrelate_related_options', 'related_custom_field', 'nrelate_admin_options', 'admin_custom_field');

			// move ad code field option from related settings to admin settings: v0.42.6
			nrelate_upgrade_option('nrelate_related_options', 'related_validate_ad', 'nrelate_admin_options', 'admin_validate_ad');

			// re-get the latest since we just made changes
			$related_settings = get_option('nrelate_related_options');
			
			// Sanitize settings for versions <= 0.46.0
			if ( $current_version <= '0.46.0' ) {
				
				// User is upgrading from version < 0.46.0
				if ( $current_version < '0.46.0' ) {
					// Apply 0.46.0 defaults before running standard upgrade
					$nr_rc_old_checkbox_options["related_show_post_title"] = 'on';
					$nr_rc_old_checkbox_options["related_ad_animation"] = 'on';
				}
				
				$related_settings = wp_parse_args( $related_settings, $nr_rc_old_checkbox_options );
			}

			// STD OPTIONS: Update new options if they don't exist
			$related_settings = wp_parse_args( $related_settings, $nr_rc_std_options );
			
			// now update again
			update_option('nrelate_related_options', $related_settings);
			
			// LAYOUT OPTIONS
			if ( ( empty( $related_layout_settings ) ) ) {
				add_option('nrelate_related_options_styles', $nr_rc_layout_options);
			} else {
				$related_layout_settings = wp_parse_args( $related_layout_settings, $nr_rc_layout_options );
				
				update_option('nrelate_related_options_styles', $related_layout_settings);
			}
			
			// Update version number in DB
			$related_settings = get_option('nrelate_related_options');
			$related_settings['related_version'] = NRELATE_RELATED_PLUGIN_VERSION;
			update_option('nrelate_related_options', $related_settings);
	}
}


  
 /**
 * Define default options for settings
 *
 * @since 0.1
 */
 
 
// Add default values to nrelate_related_options in wordpress db
// After conversion, send default values to nrelate server with user's home url and rss url
// UPDATE (v.0.2.2): add nrelate ping host to ping list and enable xml-rpc ping
// UPDATE (v.0.2.2): notify nrelate server when this plugin is activated
// UPDATE (v.0.3): send the plugin version info to nrelate server
function nr_rc_add_defaults() {

	nrelate_system_check(); // run system check
	
	global $nr_rc_std_options, $nr_rc_layout_options;

	$tmp = get_option('nrelate_related_options');
	// If related_reset value is on or if nrelate_related_options was never created, insert default values
    if(($tmp['related_reset']=='on')||(!is_array($tmp))) {
		
		add_option('nrelate_related_options', $nr_rc_std_options);
		add_option('nrelate_related_options_styles', $nr_rc_layout_options);

		// Convert some values to send to nrelate server
		$number = 3;
		$r_bar = "Low";
		$r_title = "You may also like -";
		$r_max_age = 10;
		$r_max_frame = "Year(s)";
		$r_display_post_title = true;
		$r_max_char_per_line = 100;
		$r_max_char_post_excerpt = 100;
		$r_display_ad = false;
		$r_display_logo = true;
		$r_related_reset = "";
		$related_blogoption = "Off";
		$related_thumbnail = "Thumbnails";
		$r_validate_ad = NULL;
		$backfillimage = NULL;
		$number_ext = 3;
		$related_thumbnail_size=110;
		$r_number_of_ads = 0;
		$r_ad_placement = "Mixed";

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

		// Convert display post title parameter
		switch ($r_display_post_title)
		{
		case 'on':
		  $r_display_post_title = 1;
		  break;
		default:
		 $r_display_post_title = 0;
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
		$bloglist = nrelate_get_blogroll();
		// Write the parameters to be sent
		$curlPost = 'DOMAIN='.NRELATE_BLOG_ROOT.'&NUM='.$number.'&HDR='.$r_title.'&R_BAR='.$r_bar.'&BLOGOPT='.$blogroll.'&BLOGLI='.$bloglist.'&MAXPOST='.$maxageposts.'&SHOWPOSTTITLE='.$r_display_post_title.'&MAXCHAR='.$r_max_char_per_line.'&MAXCHAREXCERPT='.$r_max_char_post_excerpt.'&ADOPT='.$ad.'&THUMB='.$thumb.'&ADCODE='.$r_validate_ad.'&LOGO='.$logo.'&NUMEXT='.$number_ext.'&IMAGEURL='.$backfillimage.'&THUMBSIZE='.$related_thumbnail_size.'&ADNUM='.$r_number_of_ads.'&ADPLACE='.$r_ad_placement;
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
	$action = "ACTIVATE";
	$curlPost = 'DOMAIN='.NRELATE_BLOG_ROOT.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	curl_exec($ch);
	curl_close($ch);

}
 
 
// Deactivation hook callback
function nr_rc_deactivate(){

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
	$action = "DEACTIVATE";
	$curlPost = 'DOMAIN='.NRELATE_BLOG_ROOT.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	curl_exec($ch);
	curl_close($ch);
}
		
?>