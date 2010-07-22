<?php
/**
 * nrelate Related Content Settings
 *
 * @package nrelate
 * @subpackage Functions
 */
 

// Register our settings. Add the settings section, and settings fields
function options_init_nr_rc(){
	register_setting('nrelate_related_options', 'nrelate_related_options', 'related_options_validate' );
	
	// Main Section
	add_settings_section('main_section', 'Main Settings', 'section_text_nr_rc', __FILE__);
	add_settings_field('related_title', 'Title for related content box:', 'setting_string_nr_rc', __FILE__, 'main_section');
	add_settings_field('related_number_of_posts', 'Number of Related Posts', 'setting_related_number_of_posts_nr_rc', __FILE__, 'main_section');
	add_settings_field('related_bar', 'Related Bar', 'setting_related_bar_nr_rc', __FILE__, 'main_section');
	add_settings_field('related_max_chars_per_line', 'Maximum characters per line:', 'setting_related_max_chars_per_line', __FILE__, 'main_section');
	add_settings_field('related_max_age', 'Maximum age for related posts:', 'setting_related_max_age', __FILE__, 'main_section');
	
	// Layout Section
	add_settings_section('layout_section', 'Layout Settings', 'section_text_nr_rc_layout', __FILE__);
	add_settings_field('related_loc_top', 'Top of Post:', 'setting_related_loc_top', __FILE__, 'layout_section');
	add_settings_field('related_loc_bottom', 'Bottom of Post:', 'setting_related_loc_bottom', __FILE__, 'layout_section');
	add_settings_field('related_display_logo', 'Show nrelate logo (we would appreciate it!):', 'setting_related_display_logo', __FILE__, 'layout_section');

	// Ad Section
	add_settings_section('ad_section', 'Advertising Settings', 'section_text_nr_rc_ad', __FILE__);
	add_settings_field('related_display_ad', 'Would you like to display related ads on your website and make money?', 'setting_related_display_ad', __FILE__, 'ad_section');
	
	// Reset Setting
	add_settings_section('reset_section', 'Reset Settings to Default', 'section_text_nr_rc_reset', __FILE__);
	add_settings_field('related_reset', 'Restore Defaults Upon Reactivation?', 'setting_reset_nr_rc', __FILE__, 'reset_section');
	
}
add_action('admin_init', 'options_init_nr_rc' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/

///////////////////////////
//   Main Settings
//////////////////////////
 
// Section HTML, displayed before the first option
function section_text_nr_rc() {
	echo '<p>Main controls for your related content.</p>';
}

// DROP-DOWN-BOX - Name: nrelate_related_options[related_number_of_posts]
function setting_related_number_of_posts_nr_rc() {
	$options = get_option('nrelate_related_options');
	$items = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
	echo "<select id='related_number_of_posts' name='nrelate_related_options[related_number_of_posts]'>";
	foreach($items as $item) {
		$selected = ($options['related_number_of_posts']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}

// DROP-DOWN-BOX - Name: nrelate_related_options[related_bar]
function  setting_related_bar_nr_rc() {
	$options = get_option('nrelate_related_options');
	$items = array("Low", "Medium", "High");
	echo "<select id='related_bar' name='nrelate_related_options[related_bar]'>";
	foreach($items as $item) {
		$selected = ($options['related_bar']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}

// TEXTBOX - Name: nrelate_related_options[related_title]
function setting_string_nr_rc() {
	$options = get_option('nrelate_related_options');
	echo "<input id='related_title' name='nrelate_related_options[related_title]' size='40' type='text' value='{$options['related_title']}' />";
}

// TEXTBOX - Name: nrelate_related_options[related_max_chars_per_line]
function setting_related_max_chars_per_line() {
	$options = get_option('nrelate_related_options');
	echo "<input id='related_max_chars_per_line' name='nrelate_related_options[related_max_chars_per_line]' size='4' type='text' value='{$options['related_max_chars_per_line']}' />";
}

// TEXTBOX / DROPDOWN - Name: nrelate_related_options[related_max_age]
function setting_related_max_age() {
	$options_num = get_option('nrelate_related_options');
	$options_frame = get_option('nrelate_related_options');
	$items = array("Hour(s)", "Day(s)", "Week(s)", "Month(s)","Year(s)");
	echo "<input id='related_title' name='nrelate_related_options[related_max_age_num]' size='4' type='text' value='{$options_num['related_max_age_num']}' />";
	
	echo "<select id='related_bar' name='nrelate_related_options[related_max_age_frame]'>";
	foreach($items as $item) {
		$selected = ($options_frame['related_max_age_frame']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
		echo "</select>";
	
}


// CHECKBOX - Name: nrelate_related_options[related_reset]
function setting_reset_nr_rc() {
	$options = get_option('nrelate_related_options');
	if($options['related_reset']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_related_reset' name='nrelate_related_options[related_reset]' type='checkbox' />";
}

///////////////////////////
//   Layout Settings
//////////////////////////

// Section HTML, displayed before the first option
function section_text_nr_rc_layout() {
	echo '<p>Where do you want your related content to display?</p>';
}

// CHECKBOX - Location Post Top
function setting_related_loc_top() {
	$options = get_option('nrelate_related_options');
	if($options['related_loc_top']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='location-top' name='nrelate_related_options[related_loc_top]' type='checkbox' />";
}

// CHECKBOX - Location Post Bottom
function setting_related_loc_bottom() {
	$options = get_option('nrelate_related_options');
	if($options['related_loc_bottom']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='location-bottom' name='nrelate_related_options[related_loc_bottom]' type='checkbox' />";
}

// CHECKBOX - Show nrelate logo
function setting_related_display_logo() {
	$options = get_option('nrelate_related_options');
	if($options['related_display_logo']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='show-logo' name='nrelate_related_options[related_display_logo]' type='checkbox' />";
}

///////////////////////////
//   Advertising Settings
//////////////////////////

// Section HTML, displayed before the first option
function section_text_nr_rc_ad() {
	echo '<p>nrelate can display ads under your related posts and you can earn money. Sign up at <a href="http://www.nrelate.com">nrelate.com</a></p>';
}

// CHECKBOX - Display ads
function setting_related_display_ad() {
	$options = get_option('nrelate_related_options');
	if($options['related_display_ad']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='location-top' name='nrelate_related_options[related_display_ad]' type='checkbox' />";
}



///////////////////////////
//   Reset
//////////////////////////

// Section HTML, displayed before the first option
function section_text_nr_rc_reset() {
	echo '<p>Reset settings to defaults when plugin is reactivated.</p>';
}


/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/
function nrelate_related_do_page() {
?>

					
	<div class="wrap" style="margin: 10px 0 0 0;">
		<?php echo '<img src='. NRELATE_RELATED_ADMIN_IMAGES .'/nrelate-logo.png alt="nrelate Logo" style="float:left; margin: 0 20px 0 0"; />';?>
		<h2>Related Content</h2>
		Display related posts on your single post pages.
		<form action="options.php" method="post">
		<?php settings_fields('nrelate_related_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

// Validate user data for some/all of our input fields
function related_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['related_title'] =  wp_filter_nohtml_kses($input['related_title']);	
	return $input; // return validated input
}