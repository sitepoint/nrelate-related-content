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
 * Since 0.44.0
 */
function nrelate_post_count() {
	$count_posts = wp_count_posts();
	$published_posts = $count_posts->publish;
	
	echo "\t<count>" . $published_posts . "</count>\n";

}

/**
 * Get Post thumbnail
 *
 */
    function nrelate_get_post_thumb($content) {
        global $post;
            if (function_exists('has_post_thumbnail')) {
                if ( has_post_thumbnail( $post->ID ) ){
                    $content = '<p>' . get_the_post_thumbnail( $post->ID, 'large' ) . '</p>' . $content;
                }
            }
        return $content;
}

/**
 * Get custom field images
 *
 */
    function nrelate_get_custom_field_image($content) {
        global $post;
 
        $options = get_option('nrelate_admin_options');
        $customfield = $options['admin_custom_field'];
            if ($customfield != null) {  
                $customfieldvalue = get_post_meta($post->ID, $customfield, $single = true);
                    if ($customfieldvalue != null) {        
                        $content = '<p><img src="' . $customfieldvalue . '" class="custom-field-image"/></p>' . $content;
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
 * MAIN NRELATE FEED
 * Serve a custom full-text feed. Thwarts FeedBurner plugins
 */
function nrelate_custom_feed() {
	if ( isset( $_GET['nrelate_feed'] ) && $_GET['nrelate_feed'] == get_option( 'nrelate_key' ) ) {
		if ( isset( $_GET['posts_per_page'] ) )
			set_query_var( 'posts_per_page', $_GET['posts_per_page'] );
		else
			set_query_var( 'posts_per_page', 50 );
		
		// Query the posts. Defaults to 50, but we can override
		query_posts(
			array(
				'posts_per_page' => get_query_var( 'posts_per_page' ),
				'paged' => get_query_var( 'paged' )
			)
		);
		
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
		       
        // Get Post thumbnail
        add_filter('the_excerpt_rss', 'nrelate_get_post_thumb', 0);
        add_filter('the_content_feed', 'nrelate_get_post_thumb', 0);
        
        // Get custom field images
        add_filter('the_excerpt_rss', 'nrelate_get_custom_field_image', 0);
        add_filter('the_content_feed', 'nrelate_get_custom_field_image', 0);
		
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