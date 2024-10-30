<?php

$chimppress_ajax = new chimppress_ajax;

class chimppress_ajax {

	function chimppress_ajax(){
		$this->__construct();
	} // function

	function __construct(){
		//add actions
		add_action('wp_ajax_get_campaign_html', array( &$this, 'get_campaign_html_callback') );
		//add filters
		   		
	} // function 	
	
	function get_campaign_html_callback() {
	
		global $wpdb; 

		$post_id = intval( $_POST['post_id'] );
		
		$content = get_post_meta($post_id, 'campaign_content_input', true);
		
		if (strpos($content,'select_template_graphic') !== false) {
    		echo '';
		}else{
			echo $content;
		}
		
		die();	
	} // function
			
}

?>