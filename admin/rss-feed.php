<?php
/**
 * nrelate custom RSS feed
 *
 * @package nrelate
 * @subpackage Functions
 */


 /**
 * Retrieve all of the post categories, formatted for use in feeds.
 *
 * @credit WordPress /includes/rss-feed.php
 *
 * Since 0.44.0
 */
function nrelate_get_the_category_rss() {
	$categories = get_the_category();
	$the_cats = '';
	$cat_names = array();

	$filter = 'rss';

	if ( !empty($categories) ) foreach ( (array) $categories as $category ) {
		$cat_names[] = sanitize_term_field('name', $category->name, $category->term_id, 'category', $filter);
	}

	$cat_names = array_unique($cat_names);

	foreach ( $cat_names as $cat_name ) {
		$the_cats .= "\t\t<category><![CDATA[" . @html_entity_decode( $cat_name, ENT_COMPAT, get_option('blog_charset') ) . "]]></category>\n";
	}

	return $the_cats;
}

 /**
 * Retrieve all of the post tags, formatted for use in feeds.
 *
 * @credit WordPress /includes/rss-feed.php
 *
 * Since 0.44.0
 */
function nrelate_get_the_tags_rss() {
	$tags = get_the_tags();
	$the_tags = '';
	$cat_names = array();

	$filter = 'rss';

	if ( !empty($tags) ) foreach ( (array) $tags as $tag ) {
		$cat_names[] = sanitize_term_field('name', $tag->name, $tag->term_id, 'post_tag', $filter);
	}
	$cat_names = array_unique($cat_names);

	foreach ( $cat_names as $cat_name ) {
		$the_tags .= "\t\t<tag><![CDATA[" . @html_entity_decode( $cat_name, ENT_COMPAT, get_option('blog_charset') ) . "]]></tag>\n";
	}

	return $the_tags;
}

 /**
 * Display Categories and Tags in feed
 *
 * @cred WordPress /includes/rss-feed.php
 *
 * Since 0.44.0
 */

function nrelate_cats_tags_rss() {
	echo nrelate_get_the_category_rss();
	echo nrelate_get_the_tags_rss();
}

/**
 * Get published post count
 *
 * Updated 0.45.0
 */
function nrelate_post_count() {
	global $wp_query;
	echo "\t<count>" . $wp_query->found_posts . "</count>\n";
}

/**
 * Get Custom Images
 *
 * Attempts to retrieve any custom images
 * set by user or themes/plugins
 */
    function nrelate_get_custom_images($content) {
        global $post;

		// Get custom field images if user set a custom field
		$options = get_option('nrelate_admin_options');
        $customfield = $options['admin_custom_field'];
            if ($customfield != null) {
                $customfieldvalue = get_post_meta($post->ID, $customfield, $single = true);
                    if ($customfieldvalue != null) {
                        $content = '<p><img src="' . $customfieldvalue . '" class="nrelate-image custom-field-image"/></p>' . $content;
                    }
            }

		// Simple Post Thumbnails
		// http://wordpress.org/extend/plugins/simple-post-thumbnails/
		// Since 0.45.0
		elseif (function_exists('p75HasThumbnail')){
			$p75image = p75GetOriginalImage($post->ID);
			$p75default = get_option('p75_default_thumbnail');
			$imageurl = get_bloginfo('wpurl') . $p75image;

			if (empty($p75image)) $imageurl = $p75default;

			$content = sprintf("<p><img class=\"nrelate-image p75-thumbnail\" src='%s' /></p>\n%s", $imageurl, $content);

		}

		// WordPress Featured Image (Post Thumbnails)
		elseif (function_exists('has_post_thumbnail') && has_post_thumbnail( $post->ID )) {
				$default_attr = array(
								'class'	=> "nrelate-image featured-image",
								);
				$content = '<p>' . get_the_post_thumbnail( $post->ID, 'large', $default_attr ) . '</p>' . $content;
		} else {


			// Last resort, search through all custom fields for image URL and grab the first
			// url must start with http and file must be a gif, png or jpg.
			// Since 0.45.0
			if (!$thumb_found) {
				foreach ( get_post_custom($post->ID) as $key => $values ) {
					$values = (array)$values;
					$imageurl = current($values);

					if ( preg_match('#^http:\/\/(.*)\.(gif|png|jpg|jpeg|tif|tiff|bmp)$#i', $imageurl) ) {
						$thumb_found = true;
						$content = sprintf("<p><img class=\"nrelate-image auto-custom-field-image\" src='%s' alt='post thumbnail' /></p>\n%s", $imageurl, $content);
						break;
					}
				}
			}
		}
		return $content;
	}

/**
 * Remove Javascript from our feed
 *
 */
function nrelate_remove_script($content) {
	global $post;
	$content = preg_replace('#(\n?<script[^>]*?>.*?</script[^>]*?>)|(\n?<script[^>]*?/>)#is', '', $content);
	return $content;
}

/**
 * Debug mode
 * Since v45.1
 */
function nrelate_debug() {
	
	$options = get_option('nrelate_admin_options');
	if (function_exists('nrelate_related')) $options += get_option('nrelate_related_options');
		
	echo '<pre>';
	print_r($options);
	echo '</pre>';
}

/**
 * MAIN NRELATE FEED
 * Serve a custom full-text feed. Thwarts FeedBurner plugins
 */
function nrelate_custom_feed() {
	if ( isset( $_GET['nrelate_feed'] ) && $_GET['nrelate_feed'] == get_option( 'nrelate_key' ) ) {
		if ($_GET['debug']) {
			nrelate_debug();
			exit();
		}
		
		if ( isset( $_GET['posts_per_page'] ) )
			set_query_var( 'posts_per_page', $_GET['posts_per_page'] );
		else
			set_query_var( 'posts_per_page', 50 );

		// Exclude categories
		$options = get_option('nrelate_admin_options');

		// Fix for pagination
		if(isset($_GET['paged'])){
			$paged = $_GET['paged'];
		}else{
			$paged = 1;
		}

		// Query the posts. Defaults to 50, but we can override
		query_posts(
			array(
				'posts_per_page' => get_query_var( 'posts_per_page' ),
				'paged' => $paged,
				'category__not_in' => (array) $options['admin_exclude_categories']
			)
		);

		// WP Super Cache: disable
		if ( function_exists('add_cacheaction') ) { define('DONOTCACHEPAGE', true); }

		//W3 Total Cache: disable
		if ( function_exists('w3tc_add_action') ) {
			define('DONOTCACHEPAGE', true);
			define('DONOTCACHEDB', true);
			define('DONOTMINIFY', true);
			define('DONOTCACHCEOBJECT', true);
		}

		// Show full content even if MORE tag is present
		global $more;
		$more = 1;

		// Force the feed to return full content
		add_filter( 'pre_option_rss_use_excerpt', create_function( '', 'return 0;' ), 0 );

		// Remove all filters from RSS functions
		remove_all_filters ('wp_title');
		remove_all_filters ('wp_title_rss');
		remove_all_filters ('the_title_rss');
		remove_all_filters ('the_permalink_rss');
		remove_all_filters ('the_content_feed');
		remove_all_filters ('the_content_rss');
		remove_all_filters ('the_excerpt_rss');
		remove_all_filters ('comment_author_rss');
		remove_all_filters ('comment_text_rss');
		remove_all_filters ('bloginfo_rss');
		remove_all_filters ('the_author');
		remove_all_filters ('the_category_rss');

		// Bring back standard WP RSS filters
		add_filter( 'the_title_rss',      'strip_tags'      );
		add_filter( 'the_title_rss',      'ent2ncr',      0 );
		add_filter( 'the_title_rss',      'esc_html'        );
		add_filter( 'the_content_rss',    'ent2ncr',      0 );
		add_filter( 'the_content_feed',   'ent2ncr',      0 );
		add_filter( 'the_excerpt_rss',    'convert_chars'   );
		add_filter( 'the_excerpt_rss',    'ent2ncr',      0 );
		add_filter( 'comment_author_rss', 'ent2ncr',      0 );
		add_filter( 'comment_text_rss',   'ent2ncr',      0 );
		add_filter( 'comment_text_rss',   'esc_html'        );
		add_filter( 'bloginfo_rss',       'ent2ncr',      0 );
		add_filter( 'the_author',         'ent2ncr',      0 );


        // Get custom images
        add_filter('the_excerpt_rss', 'nrelate_get_custom_images', 0);
        add_filter('the_content_feed', 'nrelate_get_custom_images', 0);

        // Remove Javascript
		add_filter('the_excerpt_rss', 'nrelate_remove_script', 0);
        add_filter('the_content_feed', 'nrelate_remove_script', 0);

		// Add post count
		add_action ('rss2_head', 'nrelate_post_count');

		//Show separate categories and tags in feed
		add_filter('the_category_rss', 'nrelate_cats_tags_rss', 0);


		// Use WP's feed template
		do_feed_rss2( false );
		exit();
	}
}
add_action( 'template_redirect', 'nrelate_custom_feed', 0 );
?>