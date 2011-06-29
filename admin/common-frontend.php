<?php
/**
 * Common Frontend Functions 
 *
 * Load frontend common functions
 *
 * Checks if another nrelate plugin loaded these functions first
 * 
 * @package nrelate
 * @subpackage Functions
 */

 
define( 'NRELATE_COMMON_FRONTEND_LOADED', true );
 
/**
 * Load jquery
 */
function nrelate_jquery() {
	wp_enqueue_script('jquery');
	if (function_exists("nrelate_popular_is_loading"))
		$popular_load=(nrelate_popular_is_loading()? 1:0);
	if (function_exists("nrelate_related_is_loading"))	
		$related_load=(nrelate_related_is_loading()? 1:0);
		
	if ($related_load || $popular_load) {
		wp_register_script( 'nrelate_js', NRELATE_ADMIN_URL . '/common_frontend'. ( NRELATE_JS_DEBUG ? '' : '.min') .'.js', array(), null, false);
		wp_enqueue_script('nrelate_js', array('jquery'));
	}
}
add_action ('template_redirect', 'nrelate_jquery');


/**
 * Load feed only when called
 * and if another nrelate plugin has not loaded it yet.
 *
 * @since 0.42.7
 */
if(isset($_GET['nrelate_feed'])&& !function_exists('nrelate_custom_feed')) { require_once NRELATE_RELATED_ADMIN_DIR . '/rss-feed.php'; }
 

/**
 * Detects if called inside main loop
 * @cred http://alexking.org/blog/2011/06/01/wordpress-code-snippet-to-detect-main-loop
 *
 * @since 0.47.3
 */
function nrelate_is_main_loop($query = null) {
	global $wp_the_query, $nr_is_main_loop;
	
	if (is_null($query)) {
		return $nr_is_main_loop ? true : false;
	}
	
	if ($query === $wp_the_query) {
		$nr_is_main_loop = true;
	} else {
		$nr_is_main_loop = false;
	}
	
	return $nr_is_main_loop;
}
add_action('loop_start', 'nrelate_is_main_loop');


?>