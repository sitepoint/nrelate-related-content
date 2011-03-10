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


//Check system requirements
add_action('admin_init', 'nrelate_system_check', 0);

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

/**
 * System check
 *
 * verifies whether the current system meets our minimum requirements
 */
function nrelate_system_check(){
	$plugin = NRELATE_PLUGIN_BASENAME;
	$warning = "<p><strong>".__('nrelate Warning:', 'nrelate')."</strong></p>";
	
	// is curl installed?
	if ( !function_exists('curl_init')) {
		$message .= "<p>".__('This nrelate plugin requires CURL installed on your server. Please contact your web host and ask them to install CURL.','nrelate')."</p>";
	}

	$closing .= "<p>".__('The nrelate plugin has been deactivated.','nrelate')."<br/><br/>".__('Refresh this page to return to your WordPress dashboard.','nrelate')."</p>";
		
	if (!empty($message)) {
		deactivate_plugins($plugin);
		wp_die( $warning . $message . $closing );
	}
}

/********************
 *  Admin only code
 *******************/
 if (is_admin()) {
 
 
/**
 * load javascript
 */
 wp_enqueue_script('nrelate_admin_js', NRELATE_ADMIN_URL.'/nrelate_admin_jsfunctions.js');
 
 
/**
 * Setup Dashboard menu and menu page
 */
function nrelate_setup_dashboard() {

		require_once NRELATE_ADMIN_DIR . '/nrelate-admin-settings.php';
		require_once NRELATE_ADMIN_DIR . '/nrelate-main-menu.php';
		require_once NRELATE_ADMIN_DIR . '/admin-messages.php';
		global $dashboardpage;
		$dashboardpage = add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_ADMIN_IMAGES . '/menu-logo.gif');
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
 * Add Dashboard help
 *
 * add contextual help page to dashboard
 * Since v0.44.0
 */
function nrelate_dasboard_help($contextual_help, $screen_id, $screen) {
	global $dashboardpage;
	if ($screen_id == $dashboardpage) {
		$contextual_help = nrelate_site_inventory();
	}
	return $contextual_help;
}
add_action('contextual_help', 'nrelate_dasboard_help', 10, 3);


/**
 * Website inventory for support
 *
 * used in dashboard help page
 * Since v0.44.0
 * @credits http://wordpress.org/extend/plugins/wphelpcenter/
 */
function nrelate_site_inventory(){
	$theme = get_theme(get_current_theme());
		$themename = $theme[Name];
		$themeversion = $theme[Version];
		$themeauthor = $theme[Author];
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
	foreach ( (array)$active_plugins as $plugin_file => $plugin_data) {
		$plugins .= esc_html($plugin_data['Title']). '&nbsp;' . __('version:', 'nrelate').' '.esc_html($plugin_data['Version']). '&nbsp' . __('by:', 'nrelate') . '&nbsp' . esc_html($plugin_data['Author']).'&#10;' ;
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
// move custom field option from related settings to admin settings
nrelate_upgrade_option('nrelate_related_options', 'related_custom_field', 'nrelate_admin_options', 'admin_custom_field');

// move ad code field option from related settings to admin settings - since 0.42.6
nrelate_upgrade_option('nrelate_related_options', 'related_validate_ad', 'nrelate_admin_options', 'admin_validate_ad');
};/* end is_admin */

?>