<?php
/**
Plugin Name: nrelate Related Content
Plugin URI: http://www.nrelate.com
Description: Easily display related content on your website
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire LLC.</a> 
Version: 0.11
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
	
if ( ! defined( 'NRELATE_RELATED_ADMIN_DIR' ) )
	define( 'NRELATE_RELATED_ADMIN_DIR', NRELATE_RELATED_PLUGIN_DIR . '/admin' );
	
if ( ! defined( 'NRELATE_RELATED_ADMIN_IMAGES' ) )
	define( 'NRELATE_RELATED_ADMIN_IMAGES', NRELATE_RELATED_PLUGIN_URL . '/admin/images' );


// Load Language
load_plugin_textdomain('nrelate-related', false, NRELATE_RELATED_PLUGIN_DIR . '/language');

// Launch the plugin.
add_action( 'init', 'nrelate_related_plugin_init' );

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.1
 */
function nrelate_related_plugin_init() {

	//load admin pages
	require_once ( NRELATE_RELATED_ADMIN_DIR . '/admin.php' );
	
	// Loads and registers the new widget.
	// Temporarily disabled
	//add_action( 'widgets_init', 'nrelate_related_load_widget' );
	

};

/**
 * Define default options for settings 
 *
 * @since 0.1
 */
function add_defaults_nr_rc() {
	$tmp = get_option('nrelate_related_options');
    if(($tmp['related_reset']=='on')||(!is_array($tmp))) {
		$arr = array(
		"related_number_of_posts"=>"5",
		"related_bar" => "Low",
		"related_title" => "You may also like -",
		"related_max_age_num" => "2",
		"related_max_age_frame" => "Month(s)",
		"related_max_chars_per_line" => "50",
		"related_display_ad" => "",
		"related_loc_bottom" => "on",
		"related_display_logo" => 'on',		
		"related_reset" => "",
		);
		update_option('nrelate_related_options', $arr);
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
	$content_top = nrelate_related();
	} else {
	$content_top = '';
	};
	
	if ($related_loc_bottom == "on"){
	$content_bottom = nrelate_related();
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

    return '<div class="nr-shortcode" style="float:'.$float.';width:'.$width.';\">'.nrelate_related().'</div>';
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
function nrelate_related() {

if (is_single()) {

// Assign options
$nrelate_related_options = get_option( 'nrelate_related_options' );

	$post_title = urlencode(get_the_title($href));
	$related_title = urlencode ($nrelate_related_options['related_title']);
	$related_no_related_posts = $nrelate_related_options['related_number_of_posts'];
	$related_maxcharperline = $nrelate_related_options['related_max_chars_per_line'];
	$related_bar = $nrelate_related_options['related_bar'];
	$related_max_age_num = $nrelate_related_options['related_max_age_num'];
	$related_max_age_frame = $nrelate_related_options['related_max_age_frame'];
	$related_display_ad = $nrelate_related_options['related_display_ad'];
	$related_display_logo = $nrelate_related_options['related_display_logo'];
	$related_loc_top = $nrelate_related_options['related_loc_top'];
	$related_loc_bottom = $nrelate_related_options['related_loc_bottom'];
	
	

// Convert max age time frame to minutes
switch ($related_max_age_frame)
{
case 'Hour(s)':
  $maxageposts = $related_max_age_num * 60;
  break;
case 'Day(s)':
  $maxageposts = $related_max_age_num * 1440;
  break;
case 'Week(s)':
  $maxageposts = $related_max_age_num * 10080;
  break;
case 'Month(s)':
  $maxageposts = $related_max_age_num * 120960;
  break;
case 'Year(s)':
  $maxageposts = $related_max_age_num * 1451520;
  break;
}

// Convert relevancy bar to required values
switch ($related_bar)
{
case 'Low':
  $minrelevance = "l";
  break;
case 'Medium':
  $minrelevance = "m";
  break;
case 'High':
  $minrelevance = "h";
  break;
}

// Convert ad parameter
switch ($related_display_ad)
{
case 'on':
  $ad = 1;
  break;
default:
 $ad = 0;
}

// Convert logo parameter
switch ($related_display_logo)
{
case 'on':
  $logo = 1;
  break;
default:
 $logo = 0;
}

	$markup = <<<EOD
	<script type="text/javascript" src="http://api.nrelate.com/rcw_wp/?keywords=$post_title&ad=$ad&logo=$logo&header=$related_title&norelatedposts=$related_no_related_posts&maxcharperline=$related_maxcharperline&maxageposts=$maxageposts&minrelevance=$minrelevance&tag=nr_related"></script>
EOD;
	
	return $markup;
}

};	
	

?>