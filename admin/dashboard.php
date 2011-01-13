<?php
/**
 * Dashboard Functions File
 *
 * Load dashboard common functions
 *
 * Checks if another nrelate plugin loaded these functions first
 * 
 * @package nrelate
 * @subpackage Functions
 */


if ( !function_exists('nrelate_setup_dashboard') ) {

//Check system requirements
add_action('admin_init', 'nrelate_system_check', 0);

/**
 * Define Admin constants
 */
		define( 'NRELATE_WEBSITE_FORUM_URL', 'http://nrelate.com/forum/' );

		define( 'NRELATE_DASHBOARD_FILE', plugin_basename( __FILE__ ) );
		define( 'NRELATE_ADMIN_DIR_NAME', trim( dirname( NRELATE_DASHBOARD_FILE ), '/' ) );
		define( 'NRELATE_ADMIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_ADMIN_DIR_NAME );
		define( 'NRELATE_ADMIN_URL', WP_PLUGIN_URL . '/' . NRELATE_ADMIN_DIR_NAME );
		define( 'NRELATE_ADMIN_IMAGES', NRELATE_ADMIN_URL . '/images' );
		
/**
 * Load Javascript
 */
wp_enqueue_script('jquery');

/**
 * Load custom RSS feed
 */
require_once NRELATE_ADMIN_DIR . '/rss-feed.php';

	
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
		add_menu_page(__('Dashboard','nrelate'), __('nrelate','nrelate'), 'manage_options', 'nrelate-main', 'nrelate_main_section', NRELATE_ADMIN_IMAGES . '/menu-logo.gif');	
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
function nrelate_thickbox_youtube($youtube_id, $div_id) { ?>

<div id="<?php echo $div_id ?>" style="display:none">
	<div class="nrelate_help_video">
		<object width="640" height="385">
			<param name="movie" value="http://www.youtube.com/v/<?php echo $youtube_id ?>&autoplay=1?fs=1&amp;hl=en_US"></param>
			<param name="allowFullScreen" value="true"></param>
			<param name="allowscriptaccess" value="always"></param>
			<embed src="http://www.youtube.com/v/<?php echo $youtube_id ?>&autoplay=1?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed>
		</object>
	</div>
</div>
<a class="thickbox" href="#TB_inline?height=385&amp;width=640&amp;inlineId=<?php echo $div_id ?>">
	<img class="nrelate-help" src=<?php echo NRELATE_ADMIN_IMAGES ?>/help.png />
</a>
<?php
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



 










};/* end !function_exists */


?>