<?php
/**
 * nrelate Admin Settings
 *
 * Admin settings for all nrelate plugins
 *
 * @package nrelate
 * @subpackage Functions
 */

// Register our settings. Add the settings section, and settings fields

function options_admin_init_nr(){
	
	register_setting('nrelate_admin_options', 'nrelate_admin_options', 'admin_options_validate' );
	
	// Ad Section
	add_settings_section('ad_section', __('Advertising','nrelate'), 'section_text_nr_ad', __FILE__);
	add_settings_field('admin_validate_ad', __('Please Enter your ad ID','nrelate').'<br>(<a href="' . NRELATE_WEBSITE_AD_SIGNUP . '" target="_blank">'.__('Get Your ID','nrelate') . '</a>)', 'setting_admin_validate_ad', __FILE__, 'ad_section');	

	// Communication Section
	add_settings_section('comm_section', __('Communication','nrelate'), 'section_text_nr_comm', __FILE__);
	add_settings_field('admin_email_address', __('Send email address','nrelate'), 'setting_admin_email', __FILE__, 'comm_section');	
	
	// Custom Fields
	add_settings_section('customfield_section', __('Custom Field for Images','nrelate'), 'section_text_nr_customfield', __FILE__);
	add_settings_field('admin_custom_field', __('Enter your <b>Custom Field</b> for images, here:','nrelate'), 'setting_admin_custom_field',__FILE__,'customfield_section');
	
	// Exclude categories
	add_settings_section('excludecat_section', __('Exclude Categories','nrelate'), 'section_text_nr_excludecat', __FILE__);
	add_settings_field('admin_exclude_categories', __('Categories:','nrelate'), 'setting_admin_exclude_categories',__FILE__,'excludecat_section');
	
}
add_action('admin_init', 'options_admin_init_nr' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/

///////////////////////////
//   Advertising Settings
//////////////////////////

// Section HTML: Advertising
function section_text_nr_ad() {
		_e('<p>Become a part of the nrelate advertising network and earn some extra money on your blog.</p>','nrelate');
}

// TEXTBOX - Validate ads
function setting_admin_validate_ad() {
	$options = get_option('nrelate_admin_options');
	echo '<input id="admin_validate_ad" name="nrelate_admin_options[admin_validate_ad]" size="10" type="text" value="'.htmlspecialchars(stripslashes($options['admin_validate_ad'])).'" />';
}


///////////////////////////
//   Communciation Settings
//////////////////////////

// Section HTML: Communication
function section_text_nr_comm() {
		_e('<p>nrelate may need to communicate with you when we release new features or have a problem accessing your website.</br>  Check the box, below, to send nrelate the admin email address (under "General Settings").  We promise not to overwhelm you with email.<p/>','nrelate');
}

// CHECKBOX - Admin email address
function setting_admin_email() {
	$options = get_option('nrelate_admin_options');
	if($options['admin_email_address']){ $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='location-top' name='nrelate_admin_options[admin_email_address]' type='checkbox' />";
}

///////////////////////////
//   customfield Settings
//////////////////////////

// Section HTML: customfield
function section_text_nr_customfield() {
		_e('<p>If you use a Custom Field to show images in your posts, you can have nrelate show those images.</p>','nrelate');
}

// TEXTBOX - Name: nrelate_admin_options[admin_custom_field]
function setting_admin_custom_field() {
	$options = get_option('nrelate_admin_options');
	$customfield = $options['admin_custom_field'];
	echo '<div id="imagecustomfield"><input id="admin_custom_field" name="nrelate_admin_options[admin_custom_field]" size="40" type="text" value="'.$customfield.'" /></div>';
}

///////////////////////////
//   Exclude Categories Settings
//////////////////////////

// Section HTML: customfield
function section_text_nr_excludecat() {
	_e('<p>Select the categories you want to <b>exclude</b> from ALL nrelate products.</p>', 'nrelate');
}

// CHECKBOX LIST - Name: nrelate_admin_options[admin_exclude_categories]
function setting_admin_exclude_categories() {
	$options = get_option('nrelate_admin_options');
	
	echo '<div id="nrelate-exclude-cats" class="categorydiv"><ul id="categorychecklist" class="list:category categorychecklist form-no-clear">';
	
	$taxonomy = 'category';
	$args = array('taxonomy' => $taxonomy);
	$tax = get_taxonomy($taxonomy);
	$args['disabled'] = !current_user_can($tax->cap->assign_terms);
	$args['selected_cats'] = is_array($options['admin_exclude_categories']) ? $options['admin_exclude_categories'] : array();
	$categories = (array) get_terms($taxonomy, array('get' => 'all'));
	$walker = new nrelate_Walker_Category_Checklist();
	echo call_user_func_array(array(&$walker, 'walk'), array($categories, 0, $args));
	
	echo '</ul></div>';
	
	$javascript = <<< JAVA_SCRIPT
jQuery(document).ready(function(){
	var nrel_excluded_cats_changed = false;
	
	jQuery('#nrelate-exclude-cats :checkbox').change(function(){
		var me = jQuery(this);
		
		if (!nrel_excluded_cats_changed) {		
			if (confirm("Any changes to this section will cause a site reindex. Are you sure you want to continue?\u000AIf Yes, press OK and then SAVE CHANGES."))
			{
				nrel_excluded_cats_changed = true;
			} 
			else 
			{
				me.attr('checked', !me.is(':checked'));
			}
		}
		
		if ( nrel_excluded_cats_changed ) {
			me.parent().siblings('.children').find(':checkbox').attr('checked', me.is(':checked'));
		}
	});								
});
JAVA_SCRIPT;

	echo "<script type='text/javascript'>{$javascript}</script>";
}

// Walker class to customize Checkbox List input names
class nrelate_Walker_Category_Checklist extends Walker {
	var $tree_type = 'category';
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	function start_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children' style='margin-left:18px;'>\n";
	}

	function end_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function end_el(&$output, $category, $depth, $args) {
		$output .= "</li>\n";
	}
	
	function start_el(&$output, $category, $depth, $args) {
		extract($args);
		if ( empty($taxonomy) )
			$taxonomy = 'category';

		$name = 'nrelate_admin_options[admin_exclude_categories]';
		
		$css_classes .= !$category->parent ? ' top-level-category' : '';
		$css_classes .= $has_children ? ' parent-category' : '';
		$css_classes .= in_array( $category->term_id, $selected_cats ) ? ' excluded-category' : '';
		
		$class =  $css_classes ? "class='{$css_classes}'" : '';
		
		$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit"><input value="' . $category->term_id . '" type="checkbox" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
	}
}


/*
*	Executes when nrelate_admin_options changes so it can call nrelate_reindex()
*	if one of the changes requires complete site re-indexation
*/
function nrelate_check_options_change($new_value) {
	$old_value = (array) get_option('nrelate_admin_options');
	$reindex = false;
	
	if( $new_value['admin_exclude_categories'] !== $old_value['admin_exclude_categories'] ) {
		$reindex = true;
	}
	
	
	if ( $reindex ) {
		nrelate_reindex();	
	}
	
	return $new_value;
}

add_filter('pre_update_option_nrelate_admin_options', 'nrelate_check_options_change');


/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/
function nrelate_admin_do_page() { ?> 

		<div id="nr-admin-settings" class="postbox">
			<h3 class="hndle"><span><?php _e('Common settings for all nrelate products:')?></span></h3>
				<ul class="inside">
					<?php $connectionstatus = update_nrelate_admin_data();
					if($connectionstatus !="Success"){
						echo "<h1 style='color:red;font-size:16px;'>".$connectionstatus."</h1>";
					} ?>
					<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
						<?php settings_fields('nrelate_admin_options'); ?>
						<?php do_settings_sections(__FILE__);?>
						<p class="submit">
							<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','nrelate'); ?>" />
						</p>
					</form>
				</ul><!-- .inside -->
		</div><!-- #nr-admin-settings -->
<?php
	
	update_nrelate_admin_data();
}

// Loads all of the nrelate_admin_options from wp database
// Makes necessary conversion for some parameters.
// Sends nrelate_admin_options entries, rss feed mode, and wordpress home url to the nrelate server
// Returns Success if connection status is "200". Returns error if not "200"
function update_nrelate_admin_data(){
	
	// Get nrelate_admin options from wordpress database
	$option = get_option('nrelate_admin_options');
	$r_validate_ad = $option['admin_validate_ad'];
	$n_user_email = get_option('admin_email');
	$send_email = $option['admin_email_address'];
	$custom_field = $option['admin_custom_field'];

	switch ($send_email){
	case true:
		$send_email = 1;
		$user_email = $n_user_email;
		break;
	default:
		$send_email = 0;
		$user_email = null;
	}
	
	// Get Rssmode from rss_use_excerpt option
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL"; 					
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}
	
	$rssurl = get_bloginfo('rss2_url');
	
	// Get the wordpress root url and the wordpress rss url.
	$wp_root_nr=get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$rssurl = get_bloginfo('rss2_url');
	// Write the parameters to be sent
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ADCODE='.$r_validate_ad.'&EMAIL='.$user_email.'&EMAILOPT='.$send_email.'&CUSTOM='.$custom_field.'&RSSMODE='.$rss_mode.'&RSSURL='.$rssurl.'&KEY='.get_option('nrelate_key');
	// Curl connection to the nrelate server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/processWPadmin.php'); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	switch ($info['http_code']){
		case 200:
			return "Success";
			break;
		default:
			return "Error accessing the nrelate server.";
			break;
	}
	curl_close($ch);

	echo $data; // Returns any errors sent back from the nrelate server
}


// Validate user data for some/all of our input fields
function admin_options_validate($input) {
	
	// Like escape all text fields
	$input['admin_validate_ad'] = like_escape($input['admin_validate_ad']);
	// Add slashes to all text fields
	$input['admin_validate_ad'] = esc_sql($input['admin_validate_ad']);

	return $input; // return validated input
}
?>