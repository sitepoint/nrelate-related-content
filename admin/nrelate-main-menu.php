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
		#nr-messages li.green {
			padding-left: 25px;
			background-image: url( '<?php echo NRELATE_RELATED_ADMIN_IMAGES ?>/yes.gif');
			background-repeat: no-repeat;
			color:green;
		}
		#nr-messages li.red {
			padding-left: 25px;
			background-image: url( '<?php echo NRELATE_RELATED_ADMIN_IMAGES ?>/no.gif');
			background-repeat: no-repeat;
			color:red;
		}
		#nr_about li.twitter { background-image: url( '<?php echo NRELATE_RELATED_ADMIN_IMAGES ?>/twitter.png');}
		#nr_about li.nrelate { background-image: url( '<?php echo NRELATE_RELATED_ADMIN_IMAGES ?>/nrelate-n.png');}
		#nr_about li.forums { background-image: url( '<?php echo NRELATE_RELATED_ADMIN_IMAGES ?>/forums.png');}
		
		#nr_about li{
			background-repeat:no-repeat;
			font-size:14px;
			height:40px;
			list-style:none outside none;
			margin-top:5px;
			padding-left:40px;
			padding-top:5px;
			vertical-align:middle;
		}
		#nr_about li a {
			text-decoration:none;
		}
		
	</style>

  
	<div id="dashboard-head">
	<?php echo '<img src='. NRELATE_RELATED_ADMIN_IMAGES .'/nrelate-logo.png alt="nrelate Logo" style="float:left; margin: 0 20px 0 0"; />';?>
	<h2>nrelate Dashboard</h2>

	<div class="clear"></div>
		</div>
				 
	<div id="poststuff" class="metabox-holder has-right-sidebar" style="margin: 30px 10px 0 0 ;">											
	<div class="clear"></div>

		<div class="inner-sidebar">
			<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
									
				<div id="nr_about" class="postbox">
					<h3 class="hndle"><span>About nrelate:</span></h3>
					<div class="inside">
					<li class="nrelate"><a href="http://www.nrelate.com">Visit us</a></li>
					<li class="forums"><a href="http://www.nrelate.com/forum">Ask us</a></li>
					<li class="twitter"><a href="http://www.twitter.com/nrelate">Follow us</a></li>
					</div>
				</div>
			</div>
		</div>

										
		<div class="has-sidebar nr-padded" >
			<div id="post-body-content" class="has-sidebar-content">
				<div class="meta-box-sortabless">
										
				<!-- Message -->
				<div id="nr-messages" class="postbox">
				<h3 class="hndle"><span>Messages:</span></h3>
				<ul class="inside">
				
				<?php if (function_exists('nr_msg_rss_excerpt')) { nr_msg_rss_excerpt(); } ?>

				</ul>
				</div>
				
			<!-- RSS Feeds -->
				<div id="nr-blog" class="postbox">
					<h3><?php _e('From our Blog:'); ?></h3>
						<div class="inside">
						<?php // Get RSS Feed(s)
						include_once(ABSPATH . WPINC . '/feed.php');

						// Get a SimplePie feed object from the specified feed source.
						$rss = fetch_feed('http://nrelate.com/blog/?feed=rss2');
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
			</div>

				

	</div>
	</div>
	</div>
<?php };?>