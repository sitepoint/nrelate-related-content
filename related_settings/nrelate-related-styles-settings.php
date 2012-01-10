<?php
/**
 * nrelate Related Layout Settings
 *
 * @package nrelate
 * @subpackage Functions
 */
if (!defined('NRELATE_RELATED_STYLE_THUMBNAILS_URL'))
	define('NRELATE_RELATED_STYLE_THUMBNAILS_URL', 'http://imgcdn.nrelate.com/rcw_wp/'. NRELATE_RELATED_PLUGIN_VERSION . '/images');

function options_init_nr_rc_styles(){

	register_setting('nrelate_related_options_styles', 'nrelate_related_options_styles');
	if (isset($_GET['mode']) && in_array($_GET['mode'], array('Thumbnails', 'Text'))) {
		$options['related_thumbnail'] = $type = $_GET['mode'];
	} else {
		$options = get_option('nrelate_related_options');
		$type = $options['related_thumbnail'];
	}

	
	// Main Section
	add_settings_section('style_section', __('Style Settings for&nbsp;','nrelate') . $type, 'section_text_nr_rc_style', __FILE__);
	add_settings_field('related_style', '', 'setting_related_style_type',__FILE__,'style_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'main_section');
}
add_action('admin_init', 'options_init_nr_rc_styles' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/


///////////////////////////
//   Main Settings
//////////////////////////
 
// Section HTML, displayed before the first option
function section_text_nr_rc_style() {
	if (isset($_GET['mode']) && in_array($_GET['mode'], array('Thumbnails', 'Text'))) {
		$options['related_thumbnail'] = $type = $_GET['mode'];
	} else {
		$options = get_option('nrelate_related_options');
		$type = $options['related_thumbnail'];
	}

	_e('<p class="section-desc">Choose a style from our gallery, or choose NONE to disable all nrelate CSS.</p>','nrelate');
}


// RADIO - Name: nrelate_related_options_styles[related_thumbnail_size]
function setting_related_style_type(){
	if (isset($_GET['mode']) && in_array($_GET['mode'], array('Thumbnails', 'Text'))) {
		$options['related_thumbnail'] = $_GET['mode'];
	} else {
		$options = get_option('nrelate_related_options');
	}
	
	//Common header for both styles
	echo '<div id="style-gallery">
			<h4 class="style-select"></h4>
			<h4 class="style-image">Screenshot (click on image for live preview)</h4>
			<div class="style-features-info">
				<h4 class="style-features">Features</h4>
				<h4 class="style-info">Information</h4>
			</div>
			';
	
	if($options['related_thumbnail']=="Thumbnails"){
		related_thumbnail_styles();
	} else {
		related_text_styles();
	}
}

function related_thumbnail_styles() {
	global $nrelate_thumbnail_styles;
	$options = get_option('nrelate_related_options_styles');
	foreach ( $nrelate_thumbnail_styles as $style_code => $nrelate_thumbnail_style ) {
		$style_name = $nrelate_thumbnail_style['name'];	?>
	
		<div class="nrelate-style-images nrelate-style-prev">
<?php		$checked = ($options['related_thumbnails_style']==$style_code) ? 'checked="checked"' : ''; ?>
			<label class="style-select" for="nrelate_style_<?php echo $style_code; ?>">
				<input id="nrelate_style_<?php echo $style_code; ?>" <?php echo $checked; ?> type="radio" name="nrelate_related_options_styles[related_thumbnails_style]" value="<?php echo $style_code; ?>" /><br />
				<?php echo $style_name; ?><br />
			</label>
			<a href="#" class="nrelate_preview_button nrelate-thumbnail-style-prev" title="Preview this style">
				<img class="style-image" src="<?php echo NRELATE_ADMIN_IMAGES; ?>/thumbnail_style_<?php echo $style_code; ?>.png"  alt="<?php echo $style_code; ?>" />
			</a>
			<div id="info-style-<?php echo $style_code;?>" class="style-features-info">
				<div class="style-features"><?php echo $nrelate_thumbnail_style['features']; ?></div>
				<div class="style-info">
					<p><?php echo $nrelate_thumbnail_style['info']; ?></p>
					<?php if ($style_code!='none') { ?>
						<a href="<?php echo NRELATE_CSS_URL . 'nrelate-panels-' . $style_code .'.css';?>?keepThis=true&TB_iframe=true&height=450&width=500" title="CSS for <strong><?php echo $style_name; ?></strong> Style" class="thickbox">View Stylesheet</a>
					<?php } ?>
				</div>
			</div>
				
		</div>
<?php
	} ?>
		<div style="clear:both;"></div>
    <input type="hidden" name="nrelate_related_options_styles[related_text_style]" value="<?php echo htmlentities(isset($options['related_text_style']) ? $options['related_text_style'] : ''); ?>" />
  <?php
  echo '</div>';
}

function related_text_styles() {
	global $nrelate_text_styles;
	$options = get_option('nrelate_related_options_styles');

	foreach ( $nrelate_text_styles as $style_code => $nrelate_text_style ) {
		$style_name = $nrelate_text_style['name'];	?>
	
		<div class="nrelate-style-images nrelate-style-prev">
<?php		$checked = ($options['related_text_style']==$style_code) ? 'checked="checked"' : ''; ?>
			<label class="style-select" for="nrelate_style_<?php echo $style_code; ?>">
				<input id="nrelate_style_<?php echo $style_code; ?>" <?php echo $checked; ?> type="radio" name="nrelate_related_options_styles[related_text_style]" value="<?php echo $style_code; ?>" /><br />
				<?php echo $style_name; ?><br />
			</label>
			<a href="#" class="nrelate_preview_button nrelate-text-style-prev" title="Preview this style">
				<img class="style-image" src="<?php echo NRELATE_ADMIN_IMAGES; ?>/text_style_<?php echo $style_code; ?>.png"  alt="<?php echo $style_code; ?>" />
			</a>
			<div id="info-style-<?php echo $style_code;?>" class="style-features-info">
				<div class="style-features"><?php echo $nrelate_text_style['features']; ?></div>
				<div class="style-info"><p><?php echo $nrelate_text_style['info']; ?></p>
					<?php if ($style_code!='none') { ?>
							<a href="<?php echo NRELATE_CSS_URL . 'nrelate-text-' . $style_code .'.css';?>?keepThis=true&TB_iframe=true&height=450&width=500" title="CSS for <strong><?php echo $style_name; ?></strong> Style" class="thickbox">View Stylesheet</a>
						<?php } ?>
				</div>
			</div>				
		</div>
<?php
	} ?>


  	<div style="clear:both;"></div>
    <input type="hidden" name="nrelate_related_options_styles[related_thumbnails_style]" value="<?php echo htmlentities(isset($options['related_thumbnails_style']) ? $options['related_thumbnails_style'] : ''); ?>" />
	<?php
	echo '</div>';
}

/****************************************************************
 ******************** Build the Layout Settings Page ********************** 
*****************************************************************/

function nrelate_related_styles_do_page() { ?>

	<?php nrelate_related_settings_header(); ?>
    <script type="text/javascript">
		//<![CDATA[
		var nr_plugin_settings_url = '<?php echo NRELATE_RELATED_SETTINGS_URL; ?>';
		var nr_plugin_domain = '<?php echo NRELATE_BLOG_ROOT; ?>';
		var nr_plugin_version = '<?php echo NRELATE_RELATED_PLUGIN_VERSION ?>';
		//]]>
    </script>
		<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
    <?php
		$options = get_option('nrelate_related_options');
		$style_options = get_option('nrelate_related_options_styles');
		if (isset($_GET['mode']) && in_array($_GET['mode'], array('Thumbnails', 'Text'))) {
			$options['related_thumbnail'] = $_GET['mode'];
		}
    ?>
    <div class="nrelate-hidden">
      <input type="hidden" id="related_number_of_posts" value="<?php echo isset($options['related_number_of_posts']) ? $options['related_number_of_posts'] : ''; ?>" />
      <input type="hidden" id="related_number_of_posts_ext" value="<?php echo isset($options['related_number_of_posts_ext']) ? $options['related_number_of_posts_ext'] : ''; ?>" />
      <input type="hidden" id="related_title" value="<?php echo isset($options['related_title']) ? $options['related_title'] : ''; ?>" />
      <input type="checkbox" id="related_show_post_title" <?php echo empty($options['related_show_post_title']) ? '' : 'checked="checked"'; ?> value="on" />
      <input type="hidden" id="related_max_chars_per_line" value="<?php echo isset($options['related_max_chars_per_line']) ? $options['related_max_chars_per_line'] : ''; ?>" />
      <input type="checkbox" id="related_show_post_excerpt" <?php echo empty($options['related_show_post_excerpt']) ? '' : 'checked="checked"'; ?> value="on" />
      <input type="hidden" id="related_max_chars_post_excerpt" value="<?php echo isset($options['related_max_chars_post_excerpt']) ? $options['related_max_chars_post_excerpt'] : ''; ?>" />
      <input type="checkbox" id="show_ad" <?php echo empty($options['related_display_ad']) ? '' : 'checked="checked"'; ?> value="on" />
      <input type="hidden" id="related_number_of_ads" value="<?php echo isset($options['related_number_of_ads']) ? $options['related_number_of_ads'] : ''; ?>" />
      <input type="hidden" id="related_ad_placement" value="<?php echo isset($options['related_ad_placement']) ? $options['related_ad_placement'] : ''; ?>" />
      <input type="checkbox" id="show_logo" <?php echo empty($options['related_display_logo']) ? '' : 'checked="checked"'; ?> value="on" />
      <input type="hidden" id="related_thumbnail" value="<?php echo $options['related_thumbnail']; ?>" />
      <input type="hidden" id="related_textstyle" value="<?php echo empty($style_options['related_text_style']) ? 'default' : $style_options['related_text_style']; ?>" />
      <input type="hidden" id="related_imagestyle" value="<?php echo empty($style_options['related_thumbnails_style']) ? 'default' : $style_options['related_thumbnails_style']; ?>" />
      <input type="hidden" id="related_default_image" value="<?php echo $options['related_default_image']; ?>" />
      <input type="hidden" id="related_max_age_num" value="<?php echo $options['related_max_age_num']; ?>" />
      <input type="hidden" id="related_max_age_frame" value="<?php echo $options['related_max_age_frame']; ?>" />
      <input type="hidden" id="related_blogoption" value="<?php echo ( is_array($options['related_blogoption']) && count($options['related_blogoption'] > 0) ) ? 1 : 0; ?>" />
      <input type="checkbox" class="nrelate-thumb-size" value="<?php echo $options['related_thumbnail_size']; ?>" checked="checked" />
      <input type="checkbox" id="ad_animation" value="on" <?php echo empty($options['related_ad_animation']) ? '' : ' checked="checked" '; ?> />
    </div>
		<?php settings_fields('nrelate_related_options_styles'); ?>
		<?php do_settings_sections(__FILE__);?>
		<?php nrelate_save();?>
		</form>

	</div>
<?php }
?>