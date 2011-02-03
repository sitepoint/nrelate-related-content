<?php
/**
 * Plugin Admin File
 *
 * Settings for this plugin
 *
 * @package nrelate
 * @subpackage Functions
 */


/**
 * Add sub menu
 */
function nrelate_related_setup_admin() {

    // Add our submenu to the custom top-level menu:
	require_once NRELATE_RELATED_SETTINGS_DIR . '/nrelate-related-settings.php';
    add_submenu_page('nrelate-main', __('Related Content','nrelate'), __('Related Content','nrelate'), 'manage_options', NRELATE_RELATED_ADMIN_SETTINGS_PAGE, 'nrelate_related_do_page');

};
add_action('admin_menu', 'nrelate_related_setup_admin');

// Check dashboard messages if on dashboard page in admin
require_once NRELATE_RELATED_SETTINGS_DIR . '/related-messages.php';

/**
 * Tells the dashboard that we're active
 * Shows icon and link to settings page
 */
function nr_rc_plugin_active(){ ?>
	<li class="active-plugins">
		<?php echo '<img src='. NRELATE_RELATED_IMAGE_DIR .'/relatedcontent.png style="float:left"; />'?>
		<a href="admin.php?page=<?php echo NRELATE_RELATED_ADMIN_SETTINGS_PAGE ?>">
		<?php _e('Related Content')?> &raquo;</a>
	</li>
<?php
};
add_action ('nrelate_active_plugin_notice','nr_rc_plugin_active');



/**
 * Add settings link on plugin page
 *
 * @since 0.40.3
 */
function nrelate_related_add_plugin_links( $links, $file) {
	if( $file == NRELATE_RELATED_PLUGIN_BASENAME ){
		return array_merge( array(
			'<a href="admin.php?page='.NRELATE_RELATED_ADMIN_SETTINGS_PAGE.'">'.__('Settings', 'nrelate').'</a>',
			'<a href="admin.php?page=nrelate-main">'.__('Dashboard', 'nrelate').'</a>'
		),$links );
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
	// create link
	if ($file == NRELATE_RELATED_PLUGIN_BASENAME) {
		return array_merge( $links, array(
			'<a href="admin.php?page='.NRELATE_RELATED_ADMIN_SETTINGS_PAGE.'">'.__('Settings', 'nrelate').'</a>',
			'<a href="admin.php?page=nrelate-main">'.__('Dashboard', 'nrelate').'</a>',
			'<a href="'.NRELATE_WEBSITE_FORUM_URL.'">' . __('Support Forum', 'nrelate') . '</a>'
		));
	}
	return $links;
}
add_filter('plugin_row_meta', 'nrelate_related_set_plugin_meta', 10, 2 );

?>