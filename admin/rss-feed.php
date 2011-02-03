<?php
/**
 * nrelate custom RSS feed
 *
 * @package nrelate
 * @subpackage Functions
 */



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
		add_filter( 'pre_option_rss_use_excerpt', create_function( '', 'return 0;' ) );
		
		// Remove all filters from these functions
		// other plugins may filter them and cause issues
		remove_all_filters ('the_title_rss');	
		remove_all_filters ('the_permalink_rss');
		remove_all_filters ('the_content_feed');
		remove_all_filters ('the_excerpt_rss');
		
		// Convert all named entities into numbered entities
		// XML does not like most named entities
		// some plugins have been known to send named entities to the feed
		add_filter('the_title_rss', 'ent2ncr', 0);
		add_filter('the_content_feed', 'ent2ncr', 0);
		add_filter('the_excerpt_rss', 'ent2ncr', 0);
        
        // Get Post thumbnail
        add_filter('the_excerpt_rss', 'nrelate_get_post_thumb');
        add_filter('the_content_feed', 'nrelate_get_post_thumb');
        
        // Get custom field images
        add_filter('the_excerpt_rss', 'nrelate_get_custom_field_image');
        add_filter('the_content_feed', 'nrelate_get_custom_field_image');
		
        // Remove Javascript
		add_filter('the_excerpt_rss', 'nrelate_remove_script');
        add_filter('the_content_feed', 'nrelate_remove_script');
		        

		// Use WP's feed template
		do_feed_rss2( false );
		exit();
	}
}
add_action( 'template_redirect', 'nrelate_custom_feed', 0 );
?>