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
	$adcode = $_GET['ADCODE'];
	$wp_root_nr = $_GET['DOMAIN'];
	$opt_ext = $_GET['EXTOPT'];
	$related_title=stripslashes($related_title);
	//$related_title=urlencode($related_title);
	
	$markup = <<<EOD
	<script type="text/javascript" src="http://api.nrelate.com/rcw_wp/preview.php?preview=1&widgetstyle=$thumb&domain=$wp_root_nr&adopt=$ad&logo=$logo&header=$related_title&norelatedposts=$no_related_posts&maxcharperline=$maxcharperline&minrelevance=0&noblogrollposts=$no_related_posts_ext&backfillImageURL=$imageurl&maxageposts=$maxage&adcode=$adcode&blogrollopt=$opt_ext"></script>
EOD;
	echo $markup;
?>
