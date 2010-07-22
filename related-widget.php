<?php
/**
 * nrelate Related Widget
 *
 * @package nrelate
 * @subpackage Widget
 */


 // Let's build a widget
class nrelate_Widget_Related extends WP_Widget {

	function nrelate_Widget_Related() {
		$widget_ops = array( 'classname' => 'nrelate-related-widget', 'description' => __('Show related posts', 'nrelate') );
		$control_ops = array( 'width' => 230, 'height' => 350, 'id_base' => 'nrelate-related' );
		$this->WP_Widget( 'nrelate-related', __('nrelate Related Widget', 'nrelate'), $widget_ops, $control_ops );
	}
	

	function widget( $args, $instance ) {
		extract( $args );
		
		echo "\n\t\t\t" . $before_widget;
		
		//Load the main function
		echo nrelate_related();
		
		echo "\n\t\t\t" . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['style'] = $new_instance['style'];
		
		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __('Related Posts:', 'nrelate') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div style="float:left;width:98%;"></div>
		<p>
		<a href="admin.php?page=nrelate-related"><?php _e( 'Adjust your settings','nrelate')?></a>
		</p>
		<div style="float:left;width:48%;">
				
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

?>