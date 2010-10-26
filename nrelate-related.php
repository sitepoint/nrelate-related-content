<?php
/**
Plugin Name: nrelate Related Content
Plugin URI: http://www.nrelate.com
Description: Easily display related content on your website. Click on <a href="admin.php?page=nrelate-related">nrelate &rarr; Related Content</a> to configure your settings.
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire LLC.</a> 
Version: 0.42.0
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
 * Define Plugin constants
 */
define( 'NRELATE_RELATED_PLUGIN_VERSION', '0.42.0' );
define( 'NRELATE_RELATED_ADMIN_SETTINGS_PAGE', 'nrelate-related' );
define( 'NRELATE_WEBSITE_FORUM_URL', 'http://nrelate.com/forum/' );

/**
 * Define Path constants
 */
 if ( ! defined( 'NRELATE_PLUGIN_BASENAME' ) )
	define( 'NRELATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
	
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
	
if ( ! defined( 'NRELATE_RELATED_ADMIN_URL' ) )
	define( 'NRELATE_RELATED_ADMIN_URL', NRELATE_RELATED_PLUGIN_URL . '/admin' );
	
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
	
	// Loads and registers the widget.
	add_action( 'widgets_init', 'nrelate_related_load_widget' );

};

// Check dashboard messages if on dashboard page in admin
if ( is_admin() ) {
	require_once NRELATE_RELATED_SETTINGS_DIR . '/related-messages.php';
}
/**
 * Add settings link on plugin page
 *
 * @since 0.40.3
 */
function nrelate_related_add_plugin_links( $links, $file) {
	static $this_plugin;
	if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	if( $file == $this_plugin ){
		$settings_link = '<a href="admin.php?page='.NRELATE_RELATED_ADMIN_SETTINGS_PAGE.'">'.__('Settings', 'nrelate').'</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter('plugin_action_links', 'nrelate_related_add_plugin_links', 10, 2);

/**
 * Add plugin row meta on plugin page
 *
 * @since 0.40.3
 */
function nrelate_related_set_plugin_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);
	// create link
	if ($file == $plugin) {
		return array_merge( $links, array(
			'<a href="admin.php?page='.NRELATE_RELATED_ADMIN_SETTINGS_PAGE.'">'.__('Plugin Settings', 'nrelate').'</a>',
			'<a href="'.NRELATE_WEBSITE_FORUM_URL.'">' . __('Support Forum', 'nrelate') . '</a>'
		));
	}
	return $links;
}
add_filter('plugin_row_meta', 'nrelate_related_set_plugin_meta', 10, 2 );
	
		
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
		"related_loc_top" => "off",
		"related_display_logo" => true,		
		"related_reset" => "",
		"related_blogoption" => "off",	
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
		
		// Get rss mode information
    	$excerptset = get_option('rss_use_excerpt');
		$rss_mode = "FULL"; 					
		if ($excerptset != '0') { // are RSS feeds set to excerpt
			update_option('nrelate_admin_msg', 'yes');
			$rss_mode = "SUMMARY";
		}
		
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
		$rssurl = get_bloginfo('rss2_url');
		$bloglist = blogroll_1();
		// Write the parameters to be sent
		$curlPost = 'DOMAIN='.$wp_root_nr.'&NUM='.$number.'&HDR='.$r_title.'&R_BAR='.$r_bar.'&BLOGOPT='.$blogroll.'&BLOGLI='.$bloglist.'&MAXPOST='.$maxageposts.'&MAXCHAR='.$r_max_char_per_line.'&ADOPT='.$ad.'&THUMB='.$thumb.'&ADCODE='.$r_validate_ad.'&LOGO='.$logo.'&NUMEXT='.$number_ext.'&IMAGEURL='.$backfillimage.'&RSSURL='.$rssurl.'&RSSMODE='.$rss_mode.'&KEY='.get_option('nrelate_key').'&THUMBSIZE='.$related_thumbnail_size;
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
	
	// Send notification to nrelate server of activation and send us rss feed mode information
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$action = "ACTIVATE";
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/wordpressnotify_activation.php'); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
	curl_exec($ch);
	curl_close($ch);
	
}
register_activation_hook(__FILE__, 'add_defaults_nr_rc');

// Deactivation hook callback
function nrelate_deactivate(){
	// Remove our ping link from ping_sites
	$current_ping_sites = get_option('ping_sites');
	$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
	update_option('ping_sites',$new_ping_sites);
	
	// Send notification to nrelate server of deactivation
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$action = "DEACTIVATE";
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/wordpressnotify_activation.php'); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
	curl_exec($ch);
	curl_close($ch);
}

//Uninstallation hook callback
function nrelate_uninstall(){
	// Delete nrelate options from user's wordpress db
	delete_option('nrelate_related_options');
	delete_option('nrelate_admin_msg');
	
	// Send notification to nrelate server of uninstallation
	$wp_root_nr = get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$action = "UNINSTALL";
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/wordpressnotify_activation.php'); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
	curl_exec($ch);
	curl_close($ch);
}

register_deactivation_hook(__FILE__, 'nrelate_deactivate');
register_uninstall_hook(__FILE__, 'nrelate_uninstall');

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
 * @written in 0.1
 * @live in 0.41.0
 */
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

$nr_i=0;

function nrelate_related($opt=false) {
	global $nr_i;
	if (is_single() && $nr_i===0) {
		$nr_i+=1;
		// Assign options
		$post_urlencoded = urlencode(get_permalink());
		$post_title = urlencode(get_the_title($href));
		$wp_root_nr = get_bloginfo( 'url' );
		$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
		$wp_root_nr = urlencode($wp_root_nr);
		$version = NRELATE_RELATED_PLUGIN_VERSION;
		$markup = <<<EOD
<div class="nr_clear"></div><div id="nrelate_related" class="nrelate_related"></div><div class="nr_clear"></div>
		
<script type="text/javascript">
var css=document.createElement("link");
css.setAttribute("rel", "stylesheet");
css.setAttribute("type", "text/css");
css.setAttribute("href", "http://api.nrelate.com/rcw_wp/$version/nrelate-panels.css");
document.getElementsByTagName("head")[0].appendChild(css);

var nr_load_link = false;
var nr_clicked_link = null;

function nr_clickthrough(nr_dest_url) {
	var nr_src_url = window.location.href;
	var nr_iframe_src= "http://api.nrelate.com/rcw_wp/track.html?clicked=true" +
		"&src_url=" + nr_src_url +
		"&dest_url=" + nr_dest_url;
	var nr_iframe = document.getElementById('nr_clickthrough_frame');
	nr_iframe.src = nr_iframe_src;
	nr_load_link = true;
	nr_clicked_link = nr_dest_url;
}

function nr_loadframe() {
	if(nr_load_link) {
		nr_load_link = false;
		window.location.href = nr_clicked_link;
	}
}

document.write('<iframe  id="nr_clickthrough_frame" height="0" width="0" style="border-width: 0px;" onload="javascript:nr_loadframe();"></iframe>');


function nr_fix_css() {
	var nr_height = 0; 
	jQuery("a.nr_rc_panel").each(function() {
		if (jQuery(this).height() > nr_height) {
			nr_height = jQuery(this).height();
		}
	});
	jQuery("a.nr_rc_panel").css("height", nr_height + "px");
}

function nr_onload() {
       var nr_url="http://api.nrelate.com/rcw_wp/0.42.0/?tag=nrelate_related";
       nr_url+="&keywords=$post_title&domain=$wp_root_nr&url=$post_urlencoded&p=wp";
       var nr_head = document.getElementsByTagName("head")[0]; var nr_script = document.createElement("script");
       nr_script.type = "text/javascript"; nr_script.async = true; nr_script.src = nr_url; nr_head.appendChild(nr_script);
}
if (window.attachEvent) { window.attachEvent("onload", nr_onload); }
else if (window.addEventListener) { window.addEventListener("load", nr_onload, false); }
else { document.addEventListener("load", nr_onload, false);}

</script>

EOD;
	if ($opt){
		return $markup;
	}else{
		echo $markup;
	}
	

}


};

//if(is_single()){
	$nrelate_related_options = get_option( 'nrelate_related_options' );
	if($nrelate_related_options['related_thumbnail']=="Thumbnails"){
		wp_enqueue_script("jquery");
	}
//}

?>
