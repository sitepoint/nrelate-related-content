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

 
/**
 * Load jquery
 */
function nrelate_related_jquery() {
	wp_enqueue_script('jquery');
	}
add_action ('template_redirect', 'nrelate_related_jquery');


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