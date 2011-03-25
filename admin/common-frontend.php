<?php
/**
 * Common Frontend Functions File
 *
 * Load Frontend common functions
 *
 * 
 * @package nrelate
 * @subpackage Functions
 */
 
 
// Only load if no other plugin has already loaded
if ( !function_exists('nrelate_common_styles') ) { 
	
	/*
	 * Load common styles
	 *
	 * since v.44.0
	 */

	function nrelate_common_styles() {
		if ((is_single()) && (!is_attachment()))  {
			$version = NRELATE_RELATED_PLUGIN_VERSION;
		
				$nr_css_url = "http://static.nrelate.com/rcw_wp/$version/nrelate-panels.css";
				$nr_css_ie6_url = "http://static.nrelate.com/rcw_wp/$version/ie6-panels.css";
		
				wp_register_style('nrelate_css', $nr_css_url, false, '1.0' );
				wp_enqueue_style( 'nrelate_css' );
		
				wp_register_style('nrelate_ie6_css', $nr_css_ie6_url, false, '1.0' );
				$GLOBALS['wp_styles']->add_data( 'nrelate_ie6_css', 'conditional', 'IE 6' );
				wp_enqueue_style( 'nrelate_ie6_css' );
		}
	}
	add_action('wp_print_styles', 'nrelate_common_styles');


	/*
	 * Load common Javascript
	 *
	 * since v.44.0
	 */
	function nrelate_common_javascript() {
		if ((is_single()) && (!is_attachment()))  {
			wp_register_script( 'nrelate_js', NRELATE_RELATED_PLUGIN_URL . '/nrelate-related.js', array(), '1.0', false);
			wp_enqueue_script('nrelate_js');
		}
	}
	add_action('wp_print_scripts', 'nrelate_common_javascript');
	
}

	
	

?>