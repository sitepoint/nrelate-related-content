<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Related Content Preview</title>
</head>
<body>
<?php
	// Takes values from GET (sent by the JS) and calls the preview from nrelate server
	$no_related_posts = $_GET['NUM'];
	$no_related_posts_ext	= $_GET['NUMEXT'];
	$related_title = $_GET['TITLE'];
	$maxcharperline = $_GET['MAXCHAR'];
	$ad = $_GET['AD'];
	$logo = $_GET['LOGO'];
	$thumb = $_GET['THUMB'];
	$imageurl = $_GET['IMAGEURL'];
	$maxage = $_GET['MAXAGE'];
	$wp_root_nr = $_GET['DOMAIN'];
	$opt_ext = $_GET['EXTOPT'];
	$thumbsize = $_GET['THUMBSIZE'];
	$version = $_GET['RELATED_VERSION'];
	$related_title=stripslashes($related_title);
	//$related_title=urlencode($related_title);
	$markup = <<<EOD
	<script type="text/javascript" src="http://api.nrelate.com/rcw_wp/$version/preview.php?preview=1&widgetstyle=$thumb&domain=$wp_root_nr&adopt=$ad&logo=$logo&header=$related_title&norelatedposts=$no_related_posts&maxcharperline=$maxcharperline&minrelevance=0&noblogrollposts=$no_related_posts_ext&backfillImageURL=$imageurl&maxageposts=$maxage&blogrollopt=$opt_ext&thumbsize=$thumbsize&version=$version"></script>
EOD;
	echo $markup;
?>
</body>
</html>