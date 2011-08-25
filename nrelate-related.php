<?php
/**
Plugin Name: nrelate Related Content
Plugin URI: http://www.nrelate.com
Description: Easily display related content on your website. Click on <a href="admin.php?page=nrelate-related">nrelate &rarr; Related Content</a> to configure your settings.
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire</a>
Version: 0.49.3
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
define( 'NRELATE_RELATED_PLUGIN_VERSION', '0.49.3' );
define( 'NRELATE_RELATED_ADMIN_SETTINGS_PAGE', 'nrelate-related' );
define( 'NRELATE_RELATED_ADMIN_VERSION', '0.03.0' );

define( 'NRELATE_LATEST_ADMIN_VERSION', '0.03.0' );
define( 'NRELATE_CSS_URL', 'http://static.nrelate.com/common_wp/' . NRELATE_RELATED_ADMIN_VERSION . '/' );
define( 'NRELATE_BLOG_ROOT', urlencode(str_replace(array('http://','https://'), '', get_bloginfo( 'url' ))));
define( 'NRELATE_JS_DEBUG', isset($_REQUEST['nrelate_debug']) ? true : false );

define( 'NRELATE_ADMIN_COMMON_FILE', plugin_basename( __FILE__ ) );
define( 'NRELATE_ADMIN_DIR_NAME', trim( dirname( NRELATE_ADMIN_COMMON_FILE ), '/' ) );
define( 'NRELATE_ADMIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_ADMIN_DIR_NAME.'/admin');
define( 'NRELATE_ADMIN_URL', WP_PLUGIN_URL . '/' . NRELATE_ADMIN_DIR_NAME.'/admin');

/**
 * Define Path constants
 */
// Generic: will be assigned to the last nrelate plugin that loads
define( 'NRELATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NRELATE_PLUGIN_NAME', trim( dirname( NRELATE_PLUGIN_BASENAME ), '/' ) );
define( 'NRELATE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_PLUGIN_NAME );
	
// Plugin specific
define( 'NRELATE_RELATED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NRELATE_RELATED_PLUGIN_NAME', trim( dirname( NRELATE_RELATED_PLUGIN_BASENAME ), '/' ) );
define( 'NRELATE_RELATED_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_RELATED_PLUGIN_NAME );
define( 'NRELATE_RELATED_PLUGIN_URL', WP_PLUGIN_URL . '/' . NRELATE_RELATED_PLUGIN_NAME );
define( 'NRELATE_RELATED_SETTINGS_DIR', NRELATE_RELATED_PLUGIN_DIR . '/related_settings' );
define( 'NRELATE_RELATED_SETTINGS_URL', NRELATE_RELATED_PLUGIN_URL . '/related_settings' );
define( 'NRELATE_RELATED_ADMIN_DIR', NRELATE_RELATED_PLUGIN_DIR . '/admin' );
define( 'NRELATE_RELATED_IMAGE_DIR', NRELATE_RELATED_PLUGIN_URL . '/images' );

// Load WP_Http
if( !class_exists( 'WP_Http' ) )
	include_once( ABSPATH . WPINC. '/class-http.php' );
	
// Load Language
load_plugin_textdomain('nrelate-related', false, NRELATE_RELATED_PLUGIN_DIR . '/language');

/**
 * Get the product status of all nrelate products.
 *
 * @since 0.49.0
 */
if ( ! defined( 'NRELATE_PRODUCT_STATUS' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/product-status.php' ); }

/**
 * Load plugin styles if another nrelate plugin has not loaded it yet.
 *
 * @since 0.46.0
 */
if (!isset($nrelate_thumbnail_styles)) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/styles.php' ); }

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.1
 */
if (is_admin()) {

		//load common admin files if not already loaded from another nrelate plugin
		if ( ! defined( 'NRELATE_COMMON_LOADED' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/common.php' ); }
		
		//load plugin status
		require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-plugin-status.php' );
		
		//load related menu
		require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-menu.php' );
}



/** Load common frontend functions **/
if ( ! defined( 'NRELATE_COMMON_FRONTEND_LOADED' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/common-frontend.php' ); }


/*
 * Load related styles
 *
 * since v.44.0
 * updated v46.0
 */
function nrelate_related_styles() {
	if ( nrelate_related_is_loading() ) {
		$options = get_option('nrelate_related_options');
		$style_options = get_option('nrelate_related_options_styles');
		if ($options['related_thumbnail']=='Thumbnails') {
			//Thumbnails mode
			if ('none'==$style_options['related_thumbnails_style']) return;
			$style_type = $style_options['related_thumbnails_style'];
			$stylesheet = 'nrelate-panels-' . $style_type .'.min.css';
			
			// Register ie6 styles
			$nr_css_ie6_url = NRELATE_CSS_URL . "ie6-panels.min.css";
			$nr_ie6_id = 'nrelate-ie6-' . str_replace(".","-",NRELATE_RELATED_ADMIN_VERSION);
			wp_register_style($nr_ie6_id, $nr_css_ie6_url, false, null );
			$GLOBALS['wp_styles']->add_data( $nr_ie6_id, 'conditional', 'IE 6' );
		
		} else {
		//Text mode
			if ('none'==$style_options['related_text_style']) return;
			$style_type = 'text' . $style_options['related_text_style'];
			$stylesheet = 'nrelate-text-'.$style_options['related_text_style'].'.min.css';
		}
		
		$nr_css_url = NRELATE_CSS_URL . $stylesheet;
		
		wp_register_style('nrelate-style-'. $style_type . "-" . str_replace(".","-",NRELATE_RELATED_ADMIN_VERSION), $nr_css_url, false, null );
		wp_enqueue_style( 'nrelate-style-'. $style_type . "-" . str_replace(".","-",NRELATE_RELATED_ADMIN_VERSION) );
		wp_enqueue_style( 'nrelate-ie6-' . str_replace(".","-",NRELATE_RELATED_ADMIN_VERSION) );
	}
}
add_action('wp_print_styles', 'nrelate_related_styles');

/*
 * Check if nrelate is loading (frontend only)
 *
 * @since 0.47.0
 */
function nrelate_related_is_loading() {
    $is_loading = false;
   
    if ( !is_admin() ) {   
        $options = get_option('nrelate_related_options');
       
        if ( isset($options['related_where_to_show']) ) {
            foreach ( (array)$options['related_where_to_show'] as $cond_tag ) {
                if ( function_exists( $cond_tag ) && call_user_func( $cond_tag ) ) {
                    $is_loading = true;
                    break;
                }
            }
        }
    }
   
    return apply_filters( 'nrelate_related_is_loading', $is_loading);
}



/**
 * Inject related posts into the content
 *
 * Stops injection into themes that use get_the_excerpt in their meta description
 *
 * @since 0.1
 */
function nrelate_related_inject($content) {
	global $post;
	
	if ( nrelate_related_should_inject() ) {
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
add_filter( 'the_excerpt', 'nrelate_related_inject', 10 );


/**
 * Returns true if currently the_content or the_excerpt
 * filter should be injected with nrelate code
 *
 * @since 0.47.3
 */
function nrelate_related_should_inject() {
	global $wp_current_filter;
	
	$should_inject = true;
	
	if ( !nrelate_is_main_loop() ) {
		// Don't inject if out of main loop
		$should_inject = false;
	} elseif ( in_array( 'get_the_excerpt', $wp_current_filter ) ) {
		// Don't inject if calling get_the_excerpt
		$should_inject = false;
	} elseif ( is_single() && in_array( 'the_excerpt', $wp_current_filter ) ) {
		// Don't inject the_excerpt on single post pages
		$should_inject = false;
	}
	
	// Third party widgets
	// For php 5.25 support: debug_backtrace(false);
	$call_stack = debug_backtrace();
	foreach ( $call_stack as $call ) {
		if ( $call['function'] == 'widget' ) {
			$should_inject = false;
			break;
		}
	}
	
	return apply_filters( 'nrelate_related_should_inject', $should_inject );
}



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

$nr_counter = 0;

function nrelate_related($opt=false) {
	global $post, $nr_counter;
	
	$animation_fix = $nr_rc_nonjsbody = $nr_rc_nonjsfix = $nr_rc_js_str = '';
	
	if ( nrelate_related_is_loading() )  {	
		$nr_counter++;
		
		$nrelate_related_options = get_option('nrelate_related_options');
		$style_options = get_option('nrelate_related_options_styles');
		$style_code = 'nrelate_' . (($nrelate_related_options['related_thumbnail']=='Thumbnails') ? $style_options['related_thumbnails_style'] : $style_options['related_text_style']);
		$nr_width_class = 'nr_' . (($nrelate_related_options['related_thumbnail']=='Thumbnails') ? $nrelate_related_options['related_thumbnail_size'] : "text");
		$post_title = urlencode(get_the_title($post->ID));
		$post_urlencoded = urlencode(get_permalink($post->ID));
		
		$nonjs=$nrelate_related_options['related_nonjs'];
		
		$nr_url = "http://api.nrelate.com/rcw_wp/" . NRELATE_RELATED_PLUGIN_VERSION . "/?tag=nrelate_related";
		$nr_url .= "&keywords=$post_title&domain=" . NRELATE_BLOG_ROOT . "&url=$post_urlencoded&nr_div_number=".$nr_counter;
		$nr_url .= is_home() ? '&source=hp' : '';
		
		//is loaded only once per page for related
		if (!defined('NRELATE_RELATED_HOME')) {
			define('NRELATE_RELATED_HOME', true);
			
			$animation_fix = '<style type="text/css">.nrelate .nr_sponsored{ left:0px !important; }</style>';
			
			if (!empty($nrelate_related_options['related_ad_animation'])) {
				$animation_fix = '';
			}
		}
		//is loaded only once per page for nrelate
		if (!defined('NRELATE_HOME')) {
			define('NRELATE_HOME', true);
			$domain = addslashes(NRELATE_BLOG_ROOT);
			$script= <<< EOD
					$animation_fix
					<script type="text/javascript">
					//<![CDATA[
					nRelate.domain = "{$domain}";
					//]]>
					</script>
EOD;
			echo $script;
		} 
		
	if($nonjs){
			$request = new WP_Http;
		    $args=array("timeout"=>5);
		    $response = $request->request( $nr_url."&nonjs=1",$args);
		    if( !is_wp_error( $response ) ){
			    if($response['response']['code']==200 && $response['response']['message']=='OK'){
				    $nr_rc_nonjsbody=$response['body'];
			   		$nr_rc_nonjsfix='<script type="text/javascript">nRelate.fixHeight("nrelate_related_'.$nr_counter.'");';
			   		$nr_rc_nonjsfix.='nRelate.adAnimation("nrelate_related_'.$nr_counter.'");';
					$nr_rc_nonjsfix.='nRelate.tracking("rc");</script>';
			    }else{
			    	$nr_rc_nonjsbody="<!-- nrelate server not 200. -->";
			    }
		    }else{
		    	$nr_rc_nonjsbody="<!-- WP-request to nrelate server failed. -->";
		    }
		}
		else{
			$nr_rc_js_str= <<<EOD
<script type="text/javascript">
	//<![CDATA[
		var entity_decoded_nr_url = jQuery('<span/>').html("$nr_url").text();
		nRelate.getNrelatePosts(entity_decoded_nr_url);
	//]]>
	</script>
EOD;
		}
		
		$markup = <<<EOD
$animation_fix
<div class="nr_clear"></div>	
	<div id="nrelate_related_{$nr_counter}" class="nrelate nrelate_related $style_code $nr_width_class">$nr_rc_nonjsbody</div>
	<!--[if IE 6]>
		<script type="text/javascript">jQuery('.$style_code').removeClass('$style_code');</script>
	<![endif]-->
	$nr_rc_nonjsfix
	$nr_rc_js_str
<div class="nr_clear"></div>
EOD;

		if ($opt){
			return $markup;
			}else{
			echo $markup;
		}
	}
}


//Activation and Deactivation functions
//Since 0.47.4, added uninstall hook
register_activation_hook(__FILE__, 'nr_rc_add_defaults');
register_deactivation_hook(__FILE__, 'nr_rc_deactivate');
register_uninstall_hook(__FILE__, 'nr_rc_uninstall');
?>