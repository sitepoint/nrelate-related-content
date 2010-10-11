<?php
/**
 * nrelate Admin Settings
 *
 * Common settings for all nrelate plugins
 *
 * @package nrelate
 * @subpackage Functions
 */
wp_enqueue_script('nrelate_java_script_functions', NRELATE_RELATED_ADMIN_URL.'/nrelate_jsfunctions.js');

function options_admin_init_nr(){
	register_setting('nrelate_admin_options', 'nrelate_admin_options', 'admin_options_validate' );
	
	// Ad Section
	add_settings_section('ad_section', 'Advertising', 'section_text_nr_ad', __FILE__);
	add_settings_field('admin_validate_ad', 'Please Enter your ad ID<br>(<a href="http://www.nrelate.com/advalidate.php" target="_blank">Get Your ID</a>)', 'setting_admin_validate_ad', __FILE__, 'ad_section');	

	// Communication Section
	add_settings_section('comm_section', 'Communication', 'section_text_nr_comm', __FILE__);
	add_settings_field('admin_email_address', 'Check here to send nrelate the admin email address (under "General Settings").<br/>We promise not to overwhelm you with email.', 'setting_admin_email', __FILE__, 'comm_section');	
	
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
		echo '<p>nrelate can display ads under your admin posts and you can earn money.</p>';
}

// TEXTBOX - Validate ads
function setting_admin_validate_ad() {
	$options = get_option('nrelate_admin_options');
	$related_options = get_option('nrelate_related_options');
	$adcodeopt = $related_options['related_display_ad'];
	echo '<input id="admin_validate_ad" name="nrelate_admin_options[admin_validate_ad]" size="10" type="text" value="'.htmlspecialchars(stripslashes($options['admin_validate_ad'])).'" />';
	$wp_root_nr=get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	// AJAX call to nrelate server to bring back ad code status
	echo '<script type="text/javascript"> checkad(\''.NRELATE_RELATED_ADMIN_URL.'\',\''.$wp_root_nr.'\',\''.$adcodeopt.'\',\''.NRELATE_RELATED_PLUGIN_VERSION.'\'); </script>';
}


///////////////////////////
//   Communciation Settings
//////////////////////////

// Section HTML: Communication
function section_text_nr_comm() {
		echo '<p>nrelate may need to communicate with you when we release new features or have a problem accessing your website.</p>';
}

// CHECKBOX - Admin email address
function setting_admin_email() {
	$options = get_option('nrelate_admin_options');
	if($options['admin_email_address']){ $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='location-top' name='nrelate_admin_options[admin_email_address]' type='checkbox' />";
}



/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/
function nrelate_admin_do_page() { ?> 
		<div class="inner-sidebar">
			<div id="side-bar" class="meta-box-sortabless ui-sortable" style="position:relative;">
				<div id="nr_settings" class="postbox sidebar-list">
				<h3 class="hndle"><span>General Settings:</span></h3>
					<div class="inside">
					<?php $connectionstatus = update_nrelate_admin_data();
					if($connectionstatus !="Success"){
						echo "<br><br><h1 style='color:red;font-size:16px;'>".$connectionstatus."</h1>";
					} ?>

					<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
					<?php settings_fields('nrelate_admin_options'); ?>
					<?php do_settings_sections(__FILE__);?>
		
					<p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
					</form>
					</div>
				</div>
			</div>
		</div>
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

	switch ($send_email){
	case true:
		$send_email = 1;
		break;
	default:
		$send_email = 0;
	}
	
	// Get the wordpress root url and the wordpress rss url.
	$wp_root_nr=get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$rssurl = get_bloginfo('rss2_url');
	// Write the parameters to be sent
	$curlPost = 'DOMAIN='.$wp_root_nr.'&ADCODE='.$r_validate_ad.'&EMAIL='.$n_user_email.'&EMAILOPT='.$send_email;
	// Curl connection to the nrelate server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/rcw_wp/'.NRELATE_RELATED_PLUGIN_VERSION.'/processWPadmin.php'); 
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