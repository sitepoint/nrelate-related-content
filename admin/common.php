<?php
/**
 * Common Admin Functions File
 *
 * Load Admin common functions
 *
 * Checks if another nrelate plugin loaded these functions first
 * 
 * @package nrelate
 * @subpackage Functions
 */

 /**
 * Define Admin constants
 */
		define( 'NRELATE_WEBSITE_FORUM_URL', 'http://nrelate.com/forum/' );
		define( 'NRELATE_WEBSITE_AD_SIGNUP', 'http://nrelate.com/partners/content-publishers/sign-up-for-advertising/' );

		define( 'NRELATE_ADMIN_COMMON_FILE', plugin_basename( __FILE__ ) );
		define( 'NRELATE_ADMIN_DIR_NAME', trim( dirname( NRELATE_ADMIN_COMMON_FILE ), '/' ) );
		define( 'NRELATE_ADMIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_ADMIN_DIR_NAME );
		define( 'NRELATE_ADMIN_URL', WP_PLUGIN_URL . '/' . NRELATE_ADMIN_DIR_NAME );
		define( 'NRELATE_ADMIN_IMAGES', NRELATE_ADMIN_URL . '/images' );
		
		define( 'NRELATE_MIN_WP', '2.9' );
		define( 'NRELATE_MIN_PHP', '5.0' );
		
		

/**
 * System check
 *
 * verifies whether the current system meets our minimum requirements
 */
function nrelate_system_check(){
	$plugin = NRELATE_PLUGIN_BASENAME;
	$warning = "<p><strong>".__('nrelate Warning(s):', 'nrelate')."</strong></p>";
	
	// WordPress version check
	if (!version_compare(NRELATE_MIN_WP, get_bloginfo('version'), '<=')) {
		$message .= "<li>".sprintf(__('You\'re running WordPress version %1$s. nrelate requires WordPress version %2$s.<br/>Please upgrade to WordPress version %2$s.', 'nrelate' ), get_bloginfo('version'), NRELATE_MIN_WP ) . "</li>";
	}
	
	// PHP version check
	if (!version_compare(NRELATE_MIN_PHP, PHP_VERSION, '<')) {
		$message .= "<li>".sprintf(__('You\'re server is running PHP version %1$s. nrelate requires PHP version %2$s.<br/>Please contact your web host and request PHP version %2$s.', 'nrelate' ), PHP_VERSION, NRELATE_MIN_PHP ) . "</li>";
	}
	
	// Check for CURL
	if ( !function_exists('curl_init')) {
		$message .= "<li>".__('This nrelate plugin requires CURL installed on your server. Please contact your web host and ask them to install CURL.','nrelate')."</li>";
	}
	
	$closing = "<p>".__('The nrelate plugin has been deactivated.','nrelate')."<br/><br/><a href=\"/wp-admin\">".__('Click here to return to your WordPress dashboard.','nrelate')."</a></p>";
		
	if (!empty($message)) {
		deactivate_plugins($plugin);
		wp_die( $warning . "<ol>" . $message . "<ol>" . $closing );
	}
}


/**************************************
 *  Admin only code
 *  only load if logged into admin area.
 ***************************************/
 if (is_admin()) {
 
 
/**
 * load javascript
 */
 wp_enqueue_script('nrelate_admin_js', NRELATE_ADMIN_URL.'/nrelate_admin_jsfunctions.min.js');
 
 
/**
 * Setup Dashboard menu and menu page
 */
function nrelate_setup_dashboard() {
		require_once NRELATE_ADMIN_DIR . '/nrelate-admin-settings.php';
		require_once NRELATE_ADMIN_DIR . '/nrelate-main-menu.php';
		require_once NRELATE_ADMIN_DIR . '/admin-messages.php';
		global $dashboardpage;
		$dashboardpage = add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_ADMIN_IMAGES . '/spacer.gif');
};
add_action('admin_menu', 'nrelate_setup_dashboard');
 
 /**
 * Add CSS for admin pages
 */
 function nrelate_admin_css() {
    echo '<link rel="stylesheet" type="text/css" href="' . NRELATE_ADMIN_URL . '/nrelate-admin.css" media="screen" />';
}
add_action('admin_head', 'nrelate_admin_css');

/**
 * Load thickbox
 *
 * used for help videos
 */
function nrelate_load_thickbox() {
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}
add_action('admin_print_styles','nrelate_load_thickbox');

/**
 * Common function to load YouTube videos into our admin
 * $youtube_id = youtube id, not full url
 * $div_id = unique div id for each thickbox instance
 */
function nrelate_thickbox_youtube($youtube_id, $div_id) {

$output = '
<div id="' . $div_id .'" style="display:none">
	<div class="nrelate_help_video">
		<object width="640" height="385">
			<param name="movie" value="http://www.youtube.com/v/' . $youtube_id . '&autoplay=1?fs=1&amp;hl=en_US"></param>
			<param name="allowFullScreen" value="true"></param>
			<param name="allowscriptaccess" value="always"></param>
			<embed src="http://www.youtube.com/v/' . $youtube_id . '&autoplay=1?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed>
		</object>
	</div>
</div>
<a class="thickbox" href="#TB_inline?height=385&amp;width=640&amp;inlineId=' . $div_id . '">
	<img class="nrelate-help" src=' . NRELATE_ADMIN_IMAGES . '/help.png />
</a>';

return $output;
}


/**
 * Re-index function
 *
 * Since v0.45.0
 */
function nrelate_reindex() {
	$action = "REINDEX";
	$rss_mode = isset($rss_mode) ? $rss_mode : '';
	$rssurl = isset($rssurl) ? $rssurl : '';
	
	$curlPost = 'DOMAIN='.NRELATE_BLOG_ROOT.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/reindex.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	curl_close($ch);
}

/**
 * Check index status
 *
 * Since v0.45.0
 */
function nrelate_index_check() {
	echo '<li class="nolist"><div id="indexcheck" class="info"></div></li>
		<script type="text/javascript">
			checkindex(\''.NRELATE_ADMIN_URL.'\',\''.NRELATE_BLOG_ROOT.'\',\''.NRELATE_RELATED_ADMIN_VERSION.'\');
		</script>';
}

/**
 * Get blogroll
 *
 * Takes user's bookmarks with category name 'blogroll'
 * Returns a string with all of the blogroll link urls separated by the less than character (<).
 */

function nrelate_get_blogroll(){
	$bm = get_bookmarks( array(
		'category_name'  => 'Blogroll', 
		'hide_invisible' => 1,
		'show_updated'   => 0, 
		'include'        => null,
		'exclude'        => null,
		'search'         => '.'));
	$counter=0;
	$tmp = '';
	foreach ($bm as $bookmark){
		if($counter<25)
			$tmp.=$bookmark->link_url.'<';
		$counter+=1;
	}
	return $tmp;
}

/**
 * Add nrelate dropdown help
 *
 * add contextual help page to all nrelate pages
 * Since v0.44.0
 * Updated v0.46.0 for all pages
 */
function nrelate_dashboard_help($contextual_help, $screen_id) {
	$string = "nrelate";
	if (strstr($screen_id, $string)) {
		$contextual_help = nrelate_site_inventory();
	}
	return $contextual_help;
}
add_action('contextual_help', 'nrelate_dashboard_help', 10, 2);


/**
 * Website inventory for support
 *
 * used in dashboard help page
 * Since v0.44.0
 * @credits http://wordpress.org/extend/plugins/wphelpcenter/
 */
function nrelate_site_inventory(){
	$theme = get_theme(get_current_theme());
	$themename = $theme['Name'];
	$themeversion = $theme['Version'];
	$themeauthor = strip_tags($theme['Author']);
	$url = get_option('siteurl');
	$wp_version = get_bloginfo('version');
	global $wpmu_version, $wp_version;
		is_null($wpmu_version) ? $wp_type = __('WordPress (single user)', 'nrelate') : $wp_type = __('WordPress MU', 'nrelate');
	$phpversion = phpversion();
	
	//get active plugins
	$all_plugins = get_plugins();
	$active_plugins = array();
	$inactive_plugins = array();
	foreach ( (array)$all_plugins as $plugin_file => $plugin_data) {
		if ( is_plugin_active($plugin_file) ) {
			$active_plugins[ $plugin_file ] = $plugin_data;
		} else {
			$inactive_plugins[ $plugin_file ] = $plugin_data;
		}
	}
	$plugins = '';
	foreach ( (array)$active_plugins as $plugin_file => $plugin_data) {
		$plugins .= strip_tags($plugin_data['Title']). " " .strip_tags($plugin_data['Version']). " " . __('by:', 'nrelate') .  " " . strip_tags($plugin_data['Author']).'&#10;' ;
	}

$message = <<<EOD
<strong>If you are having trouble with our plugin please copy the information below and email it to: support@nrelate.com</strong>
<textarea style="width:90%; height:200px;">
URL: $url 
WordPress Version: $wp_version
WordPress Type: $wp_type
PHP Version: $phpversion
Active Theme: $themename $themeversion by $themeauthor

Active Plugins:
$plugins
</textarea>
EOD;

return $message;
}



/**
 * Old to New Options for all plugins
 *
 * @param string $old_option - Old Option name
 * @param string $old_option_key - old Option key name
 * @param string $new_option - new Option name
 * @param string $new_option_key - new Option key name
 * 
 * Since v0.42.2
 */
function nrelate_upgrade_option($old_option, $old_option_key, $new_option, $new_option_key) {
    $old_value = get_option($old_option);
	$old_value = $old_value[$old_option_key];
	$old_value = ($old_value == false) ? array() : $old_value;
    if ($old_value != false) {
        $new_value = get_option($new_option);
        $new_value = ($new_value == false) ? array() : $new_value;

        $new_value[$new_option_key] = $old_value;
        update_option($new_option, $new_value);
    }
}





};/* end is_admin */

?>