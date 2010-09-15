<?php
/**
 * nrelate custom RSS feed
 *
 * @package nrelate
 * @subpackage Functions
 */

// Set up a unique nrelate key, for secure feed access
	$key = get_option( 'nrelate_key' );
	if ( empty( $key ) ) {
		$key = wp_generate_password( 24, false, false );
		update_option( 'nrelate_key', $key );
		add_defaults_nr_rc(); // Send the key to nrelate
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
		// Use WP's feed template
		do_feed_rss2( false );
		exit();
	}
}
add_action( 'template_redirect', 'nrelate_custom_feed', 5 );
?>