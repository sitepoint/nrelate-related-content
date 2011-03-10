<?php
/**
Plugin Name: nrelate Related Content
Plugin URI: http://www.nrelate.com
Description: Easily display related content on your website. Click on <a href="admin.php?page=nrelate-related">nrelate &rarr; Related Content</a> to configure your settings.
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire</a>
Version: 0.44.1
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
define( 'NRELATE_RELATED_PLUGIN_VERSION', '0.44.1' );
define( 'NRELATE_RELATED_ADMIN_SETTINGS_PAGE', 'nrelate-related' );
define( 'NRELATE_RELATED_ADMIN_VERSION', '0.01.0' );

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

if ( ! defined( 'NRELATE_RELATED_IMAGE_DIR' ) )
	define( 'NRELATE_RELATED_IMAGE_DIR', NRELATE_RELATED_PLUGIN_URL . '/images' );

// Load Language
load_plugin_textdomain('nrelate-related', false, NRELATE_RELATED_PLUGIN_DIR . '/language');

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.1
 */
if (is_admin()) {

		//load plugin status
		require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-plugin-status.php' );

		//load common admin files if not already loaded from another nrelate plugin
		if ( !function_exists('nrelate_setup_dashboard') ) {
			require_once ( NRELATE_RELATED_ADMIN_DIR . '/common.php' );
		}			
		//load related menu
		require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-menu.php' );
} else {
	require_once ( NRELATE_RELATED_ADMIN_DIR . '/common-frontend.php' );
}

/**
 * Load feed only when called
 * and if another nrelate plugin has loaded it yet.
 *
 * @since 0.42.7
 */
if(isset($_GET['nrelate_feed'])&& !function_exists('nrelate_custom_feed')) {
		require_once NRELATE_RELATED_ADMIN_DIR . '/rss-feed.php';
}

/**
 * Load Javascript
 */
wp_enqueue_script('jquery');


/**
 * Inject related posts into the content
 *
 * Stops injection into themes that use get_the_excerpt in their meta description
 *
 * @since 0.1
 */
function nrelate_related_inject($content) {

	// if widget is active, exit without inserting
	if ( is_active_widget(false, false, 'nrelate-related', true) ) {
		return $content;
	}
	
	global $post, $wp_current_filter;

	if ( !in_array( 'get_the_excerpt', $wp_current_filter ) ) {
	
		// Thesis theme
		if(function_exists(thesis_html_framework) && has_filter('excerpt_length')){
			// if thesis and has the filter for excerpt length,  exit without inserting
			return $content;
		}

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

	}
	return $content;
}
add_filter( 'the_content', 'nrelate_related_inject', 10 );

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
add_action( 'widgets_init', 'nrelate_related_load_widget' );

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
	if ((is_single() && $nr_i===0) && (!is_attachment()))  {	
		$nr_i+=1;
		global $wp_query;
		$post_id = $wp_query->post->ID;
		// Assign options
		$nrelate_related_options = get_option( 'nrelate_related_options' );
		$nr_width_class = 'nr_'.$nrelate_related_options['related_thumbnail_size'];
		$post_urlencoded = urlencode(get_permalink());
		$post_title = urlencode(get_the_title($post_id));
		$wp_root_nr = get_bloginfo( 'url' );
		$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
		$wp_root_nr = urlencode($wp_root_nr);
		$version = NRELATE_RELATED_PLUGIN_VERSION;

		$markup = <<<EOD
<div class="nr_clear"></div>
<div id="nrelate_related" class="nrelate_related $nr_width_class"></div>
<script type="text/javascript">
/* <![CDATA[ */ 
var nr_url="http://api.nrelate.com/rcw_wp/$version/?tag=nrelate_related";
nr_url+="&keywords=$post_title&domain=$wp_root_nr&url=$post_urlencoded";
var nr_domain="$wp_root_nr";
var nr_load_link=false;
var nr_clicked_link=null;
document.write('<iframe  id="nr_clickthrough_frame" height="0" width="0" style="border-width: 0px; display:none;" onload="javascript:nr_loadframe();"></iframe>');
jQuery.getScript(nr_url);
/* ]]> */ 
</script>
<div class="nr_clear"></div>
EOD;
 
		if ($opt){
			return $markup;
			}else{
			echo $markup;
		}
	}
}

register_activation_hook(__FILE__, 'add_defaults_nr_rc');
register_deactivation_hook(__FILE__, 'nrelate_deactivate');
register_uninstall_hook(__FILE__, 'nrelate_uninstall');
?>