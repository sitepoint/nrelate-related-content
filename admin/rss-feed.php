<?php
/**
 * nrelate custom RSS feed
 *
 * @package nrelate
 * @subpackage Functions
 */

/**
 * Set up a unique nrelate key, for secure feed access
 *
 */
	$key = get_option( 'nrelate_key' );
	if ( empty( $key ) ) {
		$key = wp_generate_password( 24, false, false );
		update_option( 'nrelate_key', $key );
		add_defaults_nr_rc(); // Send the key to nrelate
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
 
        $options = get_option('nrelate_related_options');
        $customfield = $options['related_custom_field'];
            if ($customfield != null) {  
                $customfieldvalue = get_post_meta($post->ID, $customfield, $single = true);
                    if ($customfieldvalue != null) {        
                        $content = '<p><img src="' . $customfieldvalue . '" class="custom-field-image"/></p>' . $content;
                    }
            }
        return $content;    
    }




/**
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
		// Force the feed to return full content
		add_filter( 'pre_option_rss_use_excerpt', create_function( '', 'return 0;' ) );
		
		// Remove all filters from these functions
		// some plugins filter them and cause issues
		remove_all_filters ('the_title_rss');	
		remove_all_filters ('the_permalink_rss');
        
        // Get Post thumbnail
        add_filter('the_excerpt_rss', 'nrelate_get_post_thumb');
        add_filter('the_content_feed', 'nrelate_get_post_thumb');
        
        // Get custom field images
        add_filter('the_excerpt_rss', 'nrelate_get_custom_field_image');
        add_filter('the_content_feed', 'nrelate_get_custom_field_image');
        

		// Use WP's feed template
		do_feed_rss2( false );
		exit();
	}
}
add_action( 'template_redirect', 'nrelate_custom_feed', 0 );
?>