<?php
/**
 * nrelate Admin Settings
 *
 * customfield settings for all nrelate plugins
 *
 * @package nrelate
 * @subpackage Functions
 */

// Register our settings. Add the settings section, and settings fields

function options_admin_init_nr(){
	register_setting('nrelate_admin_options', 'nrelate_admin_options', 'admin_options_validate' );
	
	// Ad Section
	add_settings_section('ad_section', __('Advertising','nrelate'), 'section_text_nr_ad', __FILE__);
	add_settings_field('admin_validate_ad', __('Please Enter your ad ID','nrelate').'<br>(<a href="http://nrelate.com/partners/content-publishers/sign-up-for-advertising/" target="_blank">'.__('Get Your ID','nrelate') . '</a>)', 'setting_admin_validate_ad', __FILE__, 'ad_section');	

	// Communication Section
	add_settings_section('comm_section', __('Communication','nrelate'), 'section_text_nr_comm', __FILE__);
	add_settings_field('admin_email_address', __('Send email address','nrelate'), 'setting_admin_email', __FILE__, 'comm_section');	
	
	// Custom Fields
	add_settings_section('customfield_section', __('Custom Field for Images','nrelate'), 'section_text_nr_customfield', __FILE__);
	add_settings_field('admin_custom_field', __('Enter your <b>Custom Field</b> for images, here:','nrelate'), 'setting_admin_custom_field',__FILE__,'customfield_section');
	
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



/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/
function nrelate_admin_do_page() { ?> 

		<div id="nr-admin-settings" class="postbox">
			<h3 class="hndle"><span><?php _e('Settings:')?></span></h3>
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
		break;
	default:
		$send_email = 0;
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
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ADCODE='.$r_validate_ad.'&EMAIL='.$n_user_email.'&EMAILOPT='.$send_email.'&CUSTOM='.$custom_field.'&RSSMODE='.$rss_mode.'&RSSURL='.$rssurl.'&KEY='.get_option('nrelate_key');
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