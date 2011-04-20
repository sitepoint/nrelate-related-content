<?php
/**
 * nrelate uninstall
 *
 * @package nrelate
 * @subpackage Functions
 */
 
 
// For pre WP 2.7
if ( !defined('WP_UNINSTALL_PLUGIN') ) { exit(); }
 
 
// Delete nrelate related options from user's wordpress db
delete_option('nrelate_related_options');
delete_option('nrelate_related_options_styles');
delete_option('nrelate_admin_msg');
	

if(function_exists('nrelate_popular')){
   	//popular plugin is activated, don't delete xmlrpc pinghost
}
else{
	// This occurs if the user is deleting all of nrelate's products
	// Delete nrelate admin options from users wordpress db
	delete_option('nrelate_admin_options');
	$current_ping_sites = get_option('ping_sites');
	$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
	update_option('ping_sites',$new_ping_sites);
}

// RSS mode is sent again just incase if the user already had nrelate_related_options in their wordpress db
// and doesn't get sent above
$excerptset = get_option('rss_use_excerpt');
$rss_mode = "FULL";
if ($excerptset != '0') { // are RSS feeds set to excerpt
	update_option('nrelate_admin_msg', 'yes');
	$rss_mode = "SUMMARY";
}

$rssurl = get_bloginfo('rss2_url');

// Send notification to nrelate server of uninstallation
$action = "UNINSTALL";
$curlPost = 'DOMAIN='.NRELATE_BLOG_ROOT.'&ACTION='.$action.'&RSSMODE='.$rss_mode.'&VERSION='.NRELATE_RELATED_PLUGIN_VERSION.'&KEY='.get_option('nrelate_key').'&ADMINVERSION='.NRELATE_RELATED_ADMIN_VERSION.'&PLUGIN=related&RSSURL='.$rssurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
curl_exec($ch);
curl_close($ch);

?>