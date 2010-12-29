<?php
/**
 * nrelate Related Content Settings
 *
 * @package nrelate
 * @subpackage Functions
 */

// Register our settings. Add the settings section, and settings fields
wp_enqueue_script('nrelate_related_js', NRELATE_RELATED_SETTINGS_URL.'/nrelate_related_jsfunctions.js');

function options_init_nr_rc(){
	register_setting('nrelate_related_options', 'nrelate_related_options', 'related_options_validate' );
	
	$options = get_option('nrelate_related_options');
	// Display preview image
	if($options['related_thumbnail']=="Thumbnails"){
		$divstyle = 'style="display:block;"';
	}
	else{
		$divstyle = 'style="display:none;"';
	}
	
	// Main Section
	add_settings_section('main_section', __('Main Settings','nrelate'), 'section_text_nr_rc', __FILE__);
	add_settings_field('related_thumbnail', __('Would you like to display thumbnails with text, or text only','nrelate'), 'setting_thumbnail',__FILE__,'main_section');
	add_settings_field('related_thumbnail_size', __('<div id="imagesizepreview_header" '.$divstyle.'>Please choose a thumbnail size </div>','nrelate'), 'setting_thumbnail_size',__FILE__,'main_section');
	add_settings_field('related_default_image', __('<div id="imagepreview_header" '.$divstyle.'>Please provide a link to your default image: (This will show up when a related post does not have a picture in it)<br/><i>For best results image should be as large (or larger) than the thumbnail size you chose above.</i></div>','nrelate'), 'setting_related_default_image',__FILE__,'main_section');
	add_settings_field('related_custom_field', __('<div id="imagecustomfield_header" '.$divstyle.'>If you use <b>Custom Fields</b> for your images, nrelate can show them.</div>','nrelate'), 'setting_related_custom_field',__FILE__,'main_section');
	add_settings_field('related_title', __('Please enter a title for the related content box','nrelate'), 'setting_string_nr_rc', __FILE__, 'main_section');
	add_settings_field('related_number_of_posts', __('<b>Maximum</b> number of related posts to display from this site','nrelate'), 'setting_related_number_of_posts_nr_rc', __FILE__, 'main_section');
	add_settings_field('related_bar', __('How relevant do you want the results to be?<br/><i>Based on the amount/type of content on your website, higher relevancy settings may return little or no posts.</i>','nrelate'), 'setting_related_bar_nr_rc', __FILE__, 'main_section');
	add_settings_field('related_max_chars_per_line', __('Maximum number of characters per line?','nrelate'), 'setting_related_max_chars_per_line', __FILE__, 'main_section');
	add_settings_field('related_max_age', __('How deep into your archive would you like to go for related posts?','nrelate'), 'setting_related_max_age', __FILE__, 'main_section');
	
	//Partner Section
	add_settings_section('partner_section',__('Partner Settings','nrelate'),'section_text_nr_rc_partner',__FILE__);
	add_settings_field('related_blogoption',__('Would you like to display related content from sites on your blogroll?','nrelate'), 'setting_blogroll',__FILE__,'partner_section');
	add_settings_field('related_number_of_posts_ext',__('<b>Maximum</b> number of related posts to display from this site\'s blogroll','nrelate'), 'setting_related_number_of_posts_nr_rc_ext', __FILE__, 'partner_section');
	
	// Layout Section
	add_settings_section('layout_section',__('Layout Settings','nrelate'), 'section_text_nr_rc_layout', __FILE__);
	add_settings_field('related_loc_top',__('Top of post <em>(Automatic)</em>','nrelate'), 'setting_related_loc_top', __FILE__, 'layout_section');
	add_settings_field('related_loc_bottom',__('Bottom of post <em>(Automatic)</em>','nrelate'), 'setting_related_loc_bottom', __FILE__, 'layout_section');
    add_settings_field('related_loc_widget',__('Widget area or Sidebar <em>(Automatic)</em>','nrelate'), 'setting_related_widget', __FILE__, 'layout_section');
	add_settings_field('related_loc_manual',__('Add to Theme <em>(Manual)</em>','nrelate','nrelate'), 'setting_related_manual', __FILE__, 'layout_section');
	add_settings_field('related_display_logo',__('Would you like to support nrelate by displaying our logo?','nrelate'), 'setting_related_display_logo', __FILE__, 'layout_section');

	// Ad Section
	add_settings_section('ad_section',__('Advertising Settings','nrelate'), 'section_text_nr_rc_ad', __FILE__);
	add_settings_field('related_display_ad',__('Would you like to display ads?','nrelate'), 'setting_related_display_ad', __FILE__, 'ad_section');


	// Reset Setting
	add_settings_section('reset_section',__('Reset Settings to Default','nrelate'), 'section_text_nr_rc_reset', __FILE__);
	add_settings_field('related_reset',__('Would you like to restore to defaults upon reactivation?','nrelate'), 'setting_reset_nr_rc', __FILE__, 'reset_section');
	
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
	_e('<p>Main controls for your related content.</p>','nrelate');
}



// DROP-DOWN-BOX - Name: nrelate_related_options[related_number_of_posts]
function setting_related_number_of_posts_nr_rc() {
	$options = get_option('nrelate_related_options');
	$items = array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
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
	$items = array ("Low", "Medium", "High");
	$itemval = array ("Low" => __("Low: least relevant",'nrelate'), "Medium" => __("Med: more relevant",'nrelate'), "High" => __("High: most relevant",'nrelate'));
	echo "<select id='related_bar' name='nrelate_related_options[related_bar]'>";
	foreach($items as $item) {
		$selected = ($options['related_bar']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$itemval[$item]</option>";
	}
	echo "</select>";
}

// TEXTBOX - Name: nrelate_related_options[related_title]
function setting_string_nr_rc() {
	$options = get_option('nrelate_related_options');
	$r_title = stripslashes(stripslashes($options['related_title']));
	$r_title = htmlspecialchars($r_title);
	nrelate_thickbox_youtube('cWEpWJ7Ftsw','related_title_video');
	echo '<input id="related_title" name="nrelate_related_options[related_title]" size="40" type="text" value="'.$r_title.'" />';
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
	$items = array(__("Hour(s)","nrelate"), __("Day(s)","nrelate"), __("Week(s)","nrelate"), __("Month(s)","nrelate"), __("Year(s)","nrelate"));
	echo "<input id='related_max_age_num' name='nrelate_related_options[related_max_age_num]' size='4' type='text' value='{$options_num['related_max_age_num']}' />";
	
	echo "<select id='related_max_age_frame' name='nrelate_related_options[related_max_age_frame]'>";
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
//   Partner Settings
//////////////////////////

function section_text_nr_rc_partner(){
	_e('<p>Related content can be brought in from your blogroll.</p>','nrelate');
}

// DROP-DOWN-BOX - Name: nrelate_related_options[related_blogoption]
function setting_blogroll() {
	$options = get_option('nrelate_related_options');
	$wp_root_nr=get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$items = array("On", "Off");
	echo '<select id="related_blogoption" name="nrelate_related_options[related_blogoption]">';
	foreach($items as $item) {
		if($item=="On")
			$selection = __("Yes","nrelate");
		else
			$selection = __("No","nrelate");
		$selected = ($options['related_blogoption']==$item) ? 'selected="yes"' : '';
		echo "<option value='$item' $selected>$selection</option>";
	}
	echo "</select>";
	
	// Ajax calls to contact nrelate servers and update as necessary
	echo "<div id='bloglinks'".$blog_div_style."></div>";
	echo '<script type="text/javascript"> checkindex(\''.NRELATE_RELATED_SETTINGS_URL.'\',\''.$wp_root_nr.'\'); checkblog(\''.NRELATE_RELATED_SETTINGS_URL.'\',\''.$wp_root_nr.'\'); </script>';
}

// DROP-DOWN-BOX - Name: nrelate_related_options[related_number_of_posts_ext]
// Number of posts from external sites
function setting_related_number_of_posts_nr_rc_ext(){
	$options = get_option('nrelate_related_options');
	$items = array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
	echo "<div id='blogrollnumber'><select id='related_number_of_posts_ext' name='nrelate_related_options[related_number_of_posts_ext]'>";
	foreach($items as $item) {
		$selected = ($options['related_number_of_posts_ext']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select></div>";
}

///////////////////////////
//   Layout Settings
//////////////////////////

// Section HTML, displayed before the first option
function section_text_nr_rc_layout(){
	_e('<p><strong>Related posts will only show up once per page.</strong></p><p>Where do you want your related content to display?</p>','nrelate');
}

// CHECKBOX - Location Post Top
function setting_related_loc_top(){
	$options = get_option('nrelate_related_options');
	if($options['related_loc_top']=='on'){ $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='related_loc_top' name='nrelate_related_options[related_loc_top]' type='checkbox'/>";
}

// CHECKBOX - Location Post Bottom
function setting_related_loc_bottom(){
	$options = get_option('nrelate_related_options');
	if($options['related_loc_bottom']=='on'){ $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='related_loc_bottom' name='nrelate_related_options[related_loc_bottom]' type='checkbox'/>";
}


// TEXT ONLY - use widget
function setting_related_widget(){
	_e("To use our widget, visit <a href=\"widgets.php\">your widget page.</a></b>","nrelate");
}

// TEXT ONLY - no options
function setting_related_manual(){
	_e("Add this code anywhere in your theme to show related content.<br>A good place is either Single.php or Sidebar.php:","nrelate"); echo"<br><b>&lt;?php if (function_exists('nrelate_related')) nrelate_related(); ?&gt;</b>";
}

// CHECKBOX - Show nrelate logo
function setting_related_display_logo(){
	$options = get_option('nrelate_related_options');
	if($options['related_display_logo']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='show_logo' name='nrelate_related_options[related_display_logo]' type='checkbox' />";
}

// DROPDOWN - Name: nrelate_related_options[related_thumbnail]
function setting_thumbnail() {
	$options = get_option('nrelate_related_options');
	$items = array(__("Thumbnails","nrelate"),__("Text","nrelate"));
	echo "<select id='related_thumbnail' name='nrelate_related_options[related_thumbnail]' onChange='nrelate_showhide_thumbnail(\"related_thumbnail\");'>";
	/*?><select id='related_thumbnail' name='nrelate_related_options[related_thumbnail]'>;
	<?php*/
	foreach($items as $item) {
		$selected = ($options['related_thumbnail']==$item) ? 'selected="selected"' : '';
		echo "<option value='".$item."' ".$selected.">".$item."</option>";
	}
	echo "</select>";
}

// RADIO - Name: nrelate_related_options[related_thumbnail_size]
function setting_thumbnail_size(){
	$options = get_option('nrelate_related_options');
	$thumbnailsize = $options['thumbnailsize'];
	if($options['related_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	}
	else{
		$divstyle = "style='display:none;'";
	}
	
	echo "<div id='imagesizepreview' ".$divstyle.">";
	nrelate_thickbox_youtube('9Y09dHk8nO0','related_thumbnailsize_video');
	$sizes = array(80,90,100,110,120,130,140,150);
	
	foreach($sizes as $size){ ?>
		<div class="nrelate-layout-thumbnails-1">
			<?php $checked = ($options['related_thumbnail_size']==$size) ? 'checked' : '';
			echo "<label><input ".$checked." id='related_imagesize_".$size."' value='$size' name='nrelate_related_options[related_thumbnail_size]' type='radio' /><br/>$size</label><br /><img src='http://img.nrelate.com/rcw_wp/default_images/preview/preview_cloud_".$size.".jpeg' />";?>
		</div>
	<?php
	}
}

// TEXTBOX - Name: nrelate_related_options[related_thumbnail]
//show picture and give ability to change picture
function setting_related_default_image(){
	
	$options = get_option('nrelate_related_options');
	// Display preview image
	if($options['related_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	}
	else{
		$divstyle = "style='display:none;'";
	}
	echo "<div id='imagepreview' ".$divstyle.">";
	$imageurl = stripslashes(stripslashes($options['related_default_image']));
	$imageurl = htmlspecialchars($imageurl);
	
	// Check if $imageurl is an empty string
	if($imageurl==""){
		_e("No default image chosen, until you provide your default image, nrelate will use free images from <a href='http://img.nrelate.com/rcw_wp/".NRELATE_RELATED_PLUGIN_VERSION."/defaultImages.html' target='_blank'>here</a>.<BR>","nrelate");
	}
	else{
		// Curl connection to nrelate server
		// Send image url and returns a thumbed version of the image
		$curlPost = "link=".urlencode($imageurl)."&w=".$options['related_thumbnail_size']."&h=".$options['related_thumbnail_size'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/thumbimagecheck.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);
		$imageurl_cached = $data;
		curl_close($ch);
		echo "Current default image: &nbsp &nbsp";
		//$imageurl = htmlspecialchars(stripslashes($imageurl));
		$imagecall = '<img id="imgupload" style="outline: 1px solid #DDDDDD;" src="'.$imageurl_cached.'" alt="No default image chosen" /><br><br>';
		echo $imagecall;
	}
	// User can input an image url
	_e("Enter the link to your default image (include http://): <br>");
	echo '<input type="text" size="60" id="related_default_image" name="nrelate_related_options[related_default_image]" value="'.$imageurl.'"></div>';
}


// TEXTBOX - Name: nrelate_related_options[related_custom_field]
function setting_related_custom_field() {
	$options = get_option('nrelate_related_options');
	// Display preview image
	if($options['related_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	}
	else{
		$divstyle = "style='display:none;'";
	}
		_e('<div id="imagecustomfield" '.$divstyle.'><a href="admin.php?page=nrelate-main">Click Here, to enter your custom field on the nrelate dashboard, under CUSTOM FIELD FOR IMAGES settings ></a>.</div>','nrelate');
		echo "<script type='text/javascript'> nrelate_showhide_thumbnail('related_thumbnail');</script>";
}

///////////////////////////
//   Advertising Settings
//////////////////////////

// Section HTML, displayed before the first option
function section_text_nr_rc_ad() {
		_e('<p>nrelate can display ads under your related posts and you can earn money. Make sure you have signed up for an "Ad ID", and entered it on the <a href="admin.php?page=nrelate-main">nrelate Dashboard page</a>.</p>','nrelate');
}

// CHECKBOX - Display ads
function setting_related_display_ad() {
	$options = get_option('nrelate_related_options');
	if($options['related_display_ad']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='show_ad' name='nrelate_related_options[related_display_ad]' type='checkbox' />";
}

///////////////////////////
//   Reset
//////////////////////////

// Section HTML, displayed before the first option
function section_text_nr_rc_reset() {
	_e('<p>To reset the plugin to defaults, check the box below, deactivate the plugin, and then reactivate it.</p>','nrelate');
}


/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/

function nrelate_related_do_page() {

//Convert some visual option parameters for preview purposes
	$option = get_option('nrelate_related_options');
	$number = $option['related_number_of_posts'];
	$r_title = urlencode($option['related_title']);
	$r_max_char_per_line = $option['related_max_chars_per_line'];
	$r_display_ad = $option['related_display_ad'];
	$r_display_logo = $option['related_display_logo'];
	$related_thumbnail = $option['related_thumbnail'];
	$number_ext = $option ['related_number_of_posts_ext'];
	
	// Convert ad parameter
	switch ($r_display_ad){
	case true:
		$ad = 1;
		break;
	default:
		$ad = 0;
	}
	
	// Convert logo parameter
	switch ($r_display_logo){
	case true:
	  $logo = 1;
	  break;
	default:
	 $logo = 0;
	}
	
	// Convert thumbnail parameter
	switch ($related_thumbnail)
	{
	case 'Thumbnails':
	  $thumb = 1;
	  break;
	default:
	 $thumb = 0;
	}
	$tag = '?NUM='.$number.'&TITLE='.$r_title.'&MAXCHAR='.$r_max_char_per_line.'&AD='.$ad.'&LOGO='.$logo.'&THUMB='.$thumb;
	//echo $tag;
?>

	<div class="wrap" style="margin: 10px 0 0 0;">
		<?php echo '<img src='. NRELATE_ADMIN_IMAGES .'/nrelate-logo.png alt="nrelate Logo" style="float:left; margin: 0 20px 0 0"; />';
		
		_e('<h2>Related Content</h2>
		The related content plugin allows you to display related posts on your single posts pages.
		Click <a href="'.NRELATE_WEBSITE_FORUM_URL.'" target="_blank">here</a> to read about each setting.','nrelate'); ?>
		<br><br>
		<div id="indexcheck"></div>
		<?php
		$connectionstatus = update_nrelate_data();
		if($connectionstatus !="Success"){
			echo "<br><br><h1 style='color:red;font-size:16px;'>".$connectionstatus."</h1>";
		}
			$wp_root_nr = get_bloginfo( 'url' );
			$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
			$wp_root_nr = urlencode($wp_root_nr);
		?>
		<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
        <p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','nrelate'); ?>" />
		</p>
        <button type="button" class="button-primary" onClick="return nrelate_related_popup_preview('<?php echo NRELATE_RELATED_SETTINGS_URL ?>','<?php echo $wp_root_nr ?>','<?php echo NRELATE_RELATED_PLUGIN_VERSION ?>');"> <?php _e('Preview','nrelate'); ?> </button>
		<?php settings_fields('nrelate_related_options'); ?>
		<?php do_settings_sections(__FILE__);?>
		<br><button type="button" class="button-primary" onClick="return nrelate_related_popup_preview('<?php echo NRELATE_RELATED_SETTINGS_URL ?>','<?php echo $wp_root_nr ?>','<?php echo NRELATE_RELATED_PLUGIN_VERSION ?>');"> <?php _e('Preview','nrelate'); ?> </button>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','nrelate'); ?>" />
		</p>
		</form>

	</div>
<?php
	
	update_nrelate_data();
}

// Loads all of the nrelate_related_options from wp database
// Makes necessary conversion for some parameters.
// Sends nrelate_related_options entries, rss feed mode, and wordpress home url to the nrelate server
// Returns Success if connection status is "200". Returns error if not "200"
function update_nrelate_data(){
	
	// Get nrelate_related options from wordpress database
	$option = get_option('nrelate_related_options');
	$number = urlencode($option['related_number_of_posts']);
	$r_bar = $option['related_bar'];
	$r_title = urlencode($option['related_title']);
	$r_max_age = $option['related_max_age_num'];
	$r_max_frame = $option['related_max_age_frame'];
	$r_max_char_per_line = $option['related_max_chars_per_line'];
	$r_display_ad = $option['related_display_ad'];
	$r_display_logo = $option['related_display_logo'];
	$r_related_reset = $option['related_reset'];
	$related_blogoption = $option['related_blogoption'];
	$related_thumbnail = $option['related_thumbnail'];
	$backfill = $option['related_default_image'];
	$number_ext = $option ['related_number_of_posts_ext'];
	$related_thumbnail_size = $option['related_thumbnail_size'];
	$related_loc_top = $option['related_loc_top'];
	$related_loc_bot = $option['related_loc_bottom'];
	
	$related_layout= '';
	if($related_loc_top==on){
		$related_layout.='(TOP)';
	}
	if($related_loc_bot==on){
		$related_layout.='(BOT)';
	}
	
	// Convert max age time frame to minutes
	switch ($r_max_frame){
	case 'Hour(s)':
		$maxageposts = $r_max_age * 60;
		break;
	case 'Day(s)':
		$maxageposts = $r_max_age * 1440;
		break;
	case 'Week(s)':
		$maxageposts = $r_max_age * 10080;
		break;
	case 'Month(s)':
		$maxageposts = $r_max_age * 44640;
		break;
	case 'Year(s)':
		$maxageposts = $r_max_age * 525600;
		break;
	}

	// Convert ad parameter
	switch ($r_display_ad){
	case true:
		$ad = 1;
		break;
	default:
		$ad = 0;
	}

	// Convert logo parameter
	switch ($r_display_logo){
	case true:
		$logo = 1;
		break;
	default:
		$logo = 0;
	}
	
	// Convert blogroll option parameter
	switch ($related_blogoption){
	case 'Off':
		$blogroll = 0;
		break;
	default:
		$blogroll = 1;
	}
	
	// Convert thumbnail option parameter
	switch ($related_thumbnail){
	case 'Thumbnails':
		$thumb = 1;
	  break;
	default:
		$thumb = 0;
	}
	
	// Get the wordpress root url and the wordpress rss url.
	$wp_root_nr=get_bloginfo( 'url' );
	$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
	$bloglist = blogroll();
	// Write the parameters to be sent
	$curlPost = 'DOMAIN='.$wp_root_nr.'&NUM='.$number.'&NUMEXT='.$number_ext.'&HDR='.$r_title.'&R_BAR='.$r_bar.'&BLOGOPT='.$blogroll.'&BLOGLI='.$bloglist.'&MAXPOST='.$maxageposts.'&MAXCHAR='.$r_max_char_per_line.'&ADOPT='.$ad.'&THUMB='.$thumb.'&LOGO='.$logo.'&IMAGEURL='.$backfill.'&THUMBSIZE='.$related_thumbnail_size.'&LAYOUT='.$related_layout;
	// Curl connection to the nrelate server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/rcw_wp/'.NRELATE_RELATED_PLUGIN_VERSION.'/processWPrelated.php'); 
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

// Takes user's bookmarks with category name 'blogroll'
// Returns a string with all of the blogroll link urls separated by the less than character (<).
function blogroll(){
	$bm = get_bookmarks( array(
		'category_name'  => 'Blogroll', 
		'hide_invisible' => 1,
		'show_updated'   => 0, 
		'include'        => null,
		'exclude'        => null,
		'search'         => '.'));
	$counter=0;
	foreach ($bm as $bookmark){
		if($counter<25)
			$tmp.=$bookmark->link_url.'<';
		$counter+=1;
	}
	return $tmp;
}


// Validate user data for some/all of our input fields
function related_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['related_title'] =  wp_filter_nohtml_kses($input['related_title']);
	if(!is_numeric($input['related_max_chars_per_line'])){
		$input['related_max_chars_per_line']=100;
	}
	if(!is_numeric($input['related_max_age_num'])){
		$input['related_max_age_num']=2;
	}
	
	// Like escape all text fields
	$input['related_default_image'] = like_escape($input['related_default_image']);
	$input['related_title'] = like_escape($input['related_title']);
	// Add slashes to all text fields
	$input['related_default_image'] = esc_sql($input['related_default_image']);
	$input['related_title'] = esc_sql($input['related_title']);

	return $input; // return validated input
}
?>