<?php

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
	$related_title=stripslashes($related_title);
	//$related_title=urlencode($related_title);
	$markup = <<<EOD
	<script type="text/javascript" src="http://api.nrelate.com/rcw_wp/index2.php?widgetstyle=$thumb&domain=$wp_root_nr&keywords=Test&adopt=$ad&logo=$logo&header=$related_title&norelatedposts=$no_related_posts&maxcharperline=$maxcharperline&minrelevance=0&noblogrollposts=$no_related_posts_ext&backfillImageURL=$imageurl&maxageposts=$maxage&adcode=$adcode"></script>
EOD;
	echo $markup;
?>
