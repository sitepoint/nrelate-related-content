<?php
/**
 * nrelate Main Menu
 *
 * @package nrelate
 * @subpackage Functions
 */
 
function nrelate_main_section() { ?>
	<div class="wrap" style="margin: 10px 0 0 0;">
	 
	 <style type="text/css">
		#poststuff p {
			font-size:12px !important;
		}
		.form-table th {
			line-height:130%;
		}
		#nr-messages div.green {
			padding-left: 25px;
			background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/yes.gif');
			background-repeat: no-repeat;
			color:green;
		}
		#nr-messages div.red {
			padding-left: 25px;
			background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/no.gif');
			background-repeat: no-repeat;
			color:red;
		}
		#nr-messages div.info {
			padding-left: 25px;
			background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/information.png');
			background-repeat: no-repeat;
			color:blue;
			font-weight:bold;
		}
		#nr-admin-settings .inside h3 {
			background:none;
		}
		#nr-admin-settings table.form-table {
			border-bottom:1px solid #dfdfdf;
		}
		#nr_installed_plugins li.active-plugins { padding-left: 0px;}
		#nr_installed_plugins li.active-plugins a { padding-left: 5px; text-decoration:none;}
		#nr_about li.twitter { background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/twitter.png');}
		#nr_about li.facebook { background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/facebook.png');}
		#nr_about li.nrelate { background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/nrelate-n.png');}
		#nr_about li.forums { background-image: url( '<?php echo NRELATE_ADMIN_IMAGES ?>/forums.png');}
		
		.sidebar-list li{
			background-repeat:no-repeat;
			font-size:14px;
			height:40px;
			list-style:none outside none;
			margin-top:5px;
			padding-left:45px;
			padding-top:5px;
			vertical-align:middle;
		}
		.sidebar-list li a {
			text-decoration:none;
		}
		#nr_rss_feeds li {
			font-size:12px;
			list-style:outside;
			padding:0 0 10px 0;
			height:100%;
		}
		#nr_rss_feeds ul {
			margin:0 0 0 15px;
		}
		#nr_about  {
      float:left;
    }
    #nr_about li {
      display:inline;
      width:80px;
      float:left;
    }
	
	</style>
	
<div id="nrelate-dashboard" style="overflow: hidden;">

<?php echo '<img src="'. NRELATE_ADMIN_IMAGES .'/nrelate-logo.png" alt="nrelate Logo" style="float:left; margin: 0 20px 0 0"; />'?>
<h2><?php _e('nrelate Dashboard')?></h2>

<div class="metabox-holder has-right-sidebar" id="poststuff">

	<div class="inner-sidebar" id="side-info-column">

		<div class="meta-box-sortables ui-sortable" id="side-sortables">
		
				<!-- Plugins Installed -->
				<div id="nr_installed_plugins" class="postbox sidebar-list">
					<h3 class="hndle"><span><?php _e('Configure Installed Plugins:')?></span></h3>
					<div class="inside">
						<!-- Hook to let us know which plugins are active -->
						<?php do_action('nrelate_active_plugin_notice');?>
					</div>
				</div>

				<!-- RSS Feeds -->
				<div id="nr_rss_feeds" class="postbox sidebar-list">
					<h3 class="hndle"><span><?php _e('From Our Blog:')?></span></h3>
					<div class="inside">
						<?php // Get RSS Feed(s)
						include_once(ABSPATH . WPINC . '/feed.php');

						// Get a SimplePie feed object from the specified feed source.
						$rss = fetch_feed('http://nrelate.com/theblog/feed');
						if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
						// Figure out how many total items there are, but limit it to 5. 
						$maxitems = $rss->get_item_quantity(5); 

						// Build an array of all the items, starting with element 0 (first element).
						$rss_items = $rss->get_items(0, $maxitems); 
						endif;
						?>

					<ul>
						<?php if ($maxitems == 0) echo '<li>No items.</li>';
					else
						// Loop through each feed item and display each item as a hyperlink.
						foreach ( $rss_items as $item ) : ?>
					<li>
						<a href='<?php echo $item->get_permalink(); ?>'
						title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
						<?php echo $item->get_title(); ?></a>
					</li>
						<?php endforeach; ?>
					</ul>
					</div>
				</div>
				
				<!-- About nrelate -->
				<div id="nr_about" class="postbox sidebar-list">
					<h3 class="hndle"><span><?php _e('About nrelate:')?></span></h3>
					<div class="inside">
            <ul>
              <li class="nrelate"><a href="http://www.nrelate.com"><?php _e('Visit us')?></a></li>
              <li class="forums"><a href="http://www.nrelate.com/forum"><?php _e('Ask us')?></a></li>
              <li class="twitter"><a href="http://www.twitter.com/nrelate"><?php _e('Follow us')?></a></li>
              <li class="facebook"><a href="http://www.facebook.com/nrelatecommunity"><?php _e('Like us')?></a></li>
            </ul>
					</div>
				</div>

			</div><!-- #side-sortables -->
		</div><!-- #side-info-column -->

		
		<div id="post-body">
			<div id="post-body-content">

				<!-- Message -->
				<div id="nr-messages" class="postbox">
					<h3 class="hndle"><span><?php _e('Messages:')?></span></h3>
					<ul class="inside">
				
					<!-- Hook for admin messages from all nrelate plugins -->
					<?php do_action('nrelate_admin_messages');?>
					<li>
					<div class="info" id="extra_message"><?php 
						// Call to nrelate server (sends home url)
						// Nrelate server returns any message to be displayed in the nrelate dashboard
						$wp_root_nr = get_bloginfo( 'url' );
						$wp_root_nr = str_replace(array('http://','https://'), '', $wp_root_nr);
						$curlPost = 'DOMAIN='.$wp_root_nr;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_adminmessage.php'); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
						$data = curl_exec($ch);
						$info = curl_getinfo($ch);
						curl_close($ch);
						echo $data;?>
					</div>
					</li>
					</ul>
				</div>
				
				<?php nrelate_admin_do_page(); 	// Get Admin settings from nrelate-admin-settings.php ?>







</div>
</div>




<div class="clear"></div></div>

<?php }
?>