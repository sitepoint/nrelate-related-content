<?php
/**
Plugin Name: nrelate Related Content
Plugin URI: http://www.nrelate.com
Description: Easily display related content on your website
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire LLC.</a> 
Version: 0.21
Author URI: http://nrelate.com/


// Copyright (c) 2010 nrelate, All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is a plugin for WordPress
// http://wordpress.org/
//
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************
**/

/**
 * Define some constants
 */
if ( ! defined( 'NRELATE_RELATED_PLUGIN_BASENAME' ) )
	define( 'NRELATE_RELATED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'NRELATE_RELATED_PLUGIN_NAME' ) )
	define( 'NRELATE_RELATED_PLUGIN_NAME', trim( dirname( NRELATE_RELATED_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'NRELATE_RELATED_PLUGIN_DIR' ) )
	define( 'NRELATE_RELATED_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_RELATED_PLUGIN_NAME );

if ( ! defined( 'NRELATE_RELATED_PLUGIN_URL' ) )
	define( 'NRELATE_RELATED_PLUGIN_URL', WP_PLUGIN_URL . '/' . NRELATE_RELATED_PLUGIN_NAME );
	
if ( ! defined( 'NRELATE_RELATED_SETTINGS_DIR' ) )
	define( 'NRELATE_RELATED_SETTINGS_DIR', NRELATE_RELATED_PLUGIN_DIR . '/related_settings' );
	
if ( ! defined( 'NRELATE_RELATED_SETTINGS_URL' ) )
	define( 'NRELATE_RELATED_SETTINGS_URL', NRELATE_RELATED_PLUGIN_URL . '/related_settings' );
	
if ( ! defined( 'NRELATE_RELATED_ADMIN_DIR' ) )
	define( 'NRELATE_RELATED_ADMIN_DIR', NRELATE_RELATED_PLUGIN_DIR . '/admin' );
	
if ( ! defined( 'NRELATE_RELATED_ADMIN_IMAGES' ) )
	define( 'NRELATE_RELATED_ADMIN_IMAGES', NRELATE_RELATED_PLUGIN_URL . '/admin/images' );
	
// Load Language
load_plugin_textdomain('nrelate-related', false, NRELATE_RELATED_PLUGIN_DIR . '/language');

// Launch the plugin.
add_action( 'plugins_loaded', 'nrelate_related_plugin_init' );

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.1
 */
function nrelate_related_plugin_init() {

	//load dashboard pages
	require_once ( NRELATE_RELATED_ADMIN_DIR . '/dashboard.php' );

	//load admin pages
	require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-menu.php' );
	
	//load messages
	require_once NRELATE_RELATED_ADMIN_DIR . '/messages.php';
	
	//load nrelate common functions
	require_once ( NRELATE_RELATED_ADMIN_DIR . '/common-functions.php' );
	
	// Loads and registers the new widget.
	// Temporarily disabled
	//add_action( 'widgets_init', 'nrelate_related_load_widget' );
	

};

/**
 * Define default options for settings 
 *
 * @since 0.1
 */
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

function add_defaults_nr_rc() {
	//$current_ping_sites = get_option('ping_sites');
	//update_option('ping_sites',$current_ping_sites."")
	
	$tmp = get_option('nrelate_related_options');
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
		"related_default_image" => NULL,
		"related_number_of_posts_ext" => 3,
		"related_validate_ad" => NULL
		);
		update_option('nrelate_related_options', $arr);
		
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
		
		//Convert blogroll option parameter
		switch ($related_blogoption)
		{
		case 'Off':
		  $blogroll = 0;
		  break;
		default:
		 $blogroll = 1;
		}
		
		switch ($related_thumbnail)
		{
		case 'Thumbnails':
			$thumb = 1;
			break;
		default:
			$thumb = 0;
		}
		
		//get the wordpress root directory
		$wp_root_nr=get_bloginfo( 'url' );
		$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
		$bloglist = blogroll_1();
		$curlPost = 'DOMAIN='.$wp_root_nr.'&NUM='.$number.'&HDR='.$r_title.'&R_BAR='.$r_bar.'&BLOGOPT='.$blogroll.'&BLOGLI='.$bloglist.'&MAXPOST='.$maxageposts.'&MAXCHAR='.$r_max_char_per_line.'&ADOPT='.$ad.'&THUMB='.$thumb.'&ADCODE='.$r_validate_ad.'&LOGO='.$logo.'&NUMEXT='.$number_ext.'&IMAGEURL='.$backfillimage;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/rcw_wp/processWPadmin.php'); 
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
}
register_activation_hook(__FILE__, 'add_defaults_nr_rc');


/**
 * Inject related posts into the content
 *
 *
 * @since 0.1
 */
function nrelate_related_inject($content) {
	global $post;

$nrelate_related_options = get_option( 'nrelate_related_options' );

	$related_loc_top = $nrelate_related_options['related_loc_top'];
	$related_loc_bottom = $nrelate_related_options['related_loc_bottom'];
	
	if ($related_loc_top == "on"){
	$content_top = nrelate_related(true);
	} else {
	$content_top = '';
	};
	
	if ($related_loc_bottom == "on"){
	$content_bottom = nrelate_related(true);
	} else {
	$content_bottom = '';
	};
	
		$original = $content;
		
		$content  = $content_top;
		$content .= $original;
		$content .= $content_bottom;
		
		
       return $content;
}
add_filter( 'the_content', 'nrelate_related_inject' );


/**
 * nrelate related shortcode
 *
 * @since 0.1
 */
function nrelate_related_shortcode ($atts) {
	extract(shortcode_atts(array(
		"float" => 'left',
		"width" => '100%',
	), $atts));

    return '<div class="nr-shortcode" style="float:'.$float.';width:'.$width.';\">'.nrelate_related(true).'</div>';
}
add_shortcode('nrelate-related', 'nrelate_related_shortcode');

/**
 * Register the widget. 
 *
 * @uses register_widget() Registers individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 *
 * @since 0.1
 */
 // Temporarily disabled -- don't worry it's coming back
function nrelate_related_load_widget() {
	
	//Load widget file.
	require_once( 'related-widget.php' );

	// Register widget.
	register_widget( 'nrelate_Widget_Related' );
};


/**
 * Primary function
 *
 * Gets options and passes to nrelate via Javascript
 *
 * @since 0.1
 */
function nrelate_related($opt=false) {

if (is_single()) {

// Assign options
$nrelate_related_options = get_option( 'nrelate_related_options' );

	$post_title = urlencode(get_the_title($href));
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$wp_root_nr = urlencode($wp_root_nr);
	
	$markup = <<<EOD
	<script type="text/javascript" src="http://api.nrelate.com/rcw_wp/index2.php?keywords=$post_title&domain=$wp_root_nr"></script>
EOD;
	if ($opt){
		return $markup;
	}
	else{
		echo $markup;
	}
}
};
	

?>