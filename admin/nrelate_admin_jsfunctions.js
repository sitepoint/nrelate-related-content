
//hide and show advanced thumbnail options
function nrelate_showhide_thumbnail(idname){
	var nr_thumbval = document.getElementById(idname).value;
	switch (nr_thumbval){
	case 'Thumbnails':
		jQuery('#imagesizepreview').show('slow');
		jQuery('#imagesizepreview_header').show('slow');
		jQuery('#imagepreview').show('slow');
		jQuery('#imagepreview_header').show('slow');
		jQuery('#imagecustomfield').show('slow');
		jQuery('#imagecustomfield_header').show('slow');
		break;
	default:
		jQuery('#imagesizepreview').hide('slow');
		jQuery('#imagesizepreview_header').hide('slow');
		jQuery('#imagepreview').hide('slow');
		jQuery('#imagepreview_header').hide('slow');
		jQuery('#imagecustomfield').hide('slow');
		jQuery('#imagecustomfield_header').hide('slow');
	}
}

//Ajax call to checkad.php to check ad validation
function checkad(nr_domain,nr_admin_version,nr_key){
	jQuery.getScript("http://api.nrelate.com/common_wp/"+nr_admin_version+"/adcheck.php?domain="+nr_domain+"&nr_key="+nr_key+"&getrequest=1");
}

//Ajax call to checkindex.php to check the site's indexing status
function checkindex(nr_settingsurl,nr_domain,nr_admin_version){
	jQuery.getScript("http://api.nrelate.com/common_wp/"+nr_admin_version+"/indexcheck.php?domain="+nr_domain+"&getrequest=1");
}

//Ajax call to getnrcode.php to get the adcode for signing up in partners.nrelate.com
function getnrcode(nr_domain,nr_admin_version,nr_key){
	jQuery.getScript("http://api.nrelate.com/common_wp/"+nr_admin_version+"/getnrcode.php?domain="+nr_domain+"&nr_key="+nr_key);
}