<?php

$chimppress_display = new chimppress_display;

class chimppress_display {

	function chimppress_display(){
		$this->__construct();
	} // function

	function __construct(){
	
		add_action('template_redirect', array( &$this, 'front_end_header' ), 1000 );
	
	} //function
	
	function front_end_header(){
	
		if(get_post_type() == 'chimppress'){
		
			global $post;
			
			//print_r($post);
			
			?>
			
			<style type="text/css">
			
				body{
					margin: 0px;
				}
			
				.header_bar{
					width: 100%;
					background: whiteSmoke;
					top: 0;
					float: left;
					left: 0;
					padding: 10px;
					margin-bottom: 20px;
				}
			
				.back_button{
					background: #F2F2F2 url(<?php echo get_admin_url(); ?>/images/white-grad.png) repeat-x scroll left top;
					text-shadow: rgba(255, 255, 255, 1);
					line-height: 15px;
					padding: 3px 10px;
					white-space: nowrap;
					border-radius: 10px;
					-webkit-border-radius: 10px;
					-moz-border-radius: 10px;
					border-color: #BBB;
					color: #464646;
					cursor: pointer;
					border-width: 1px;
					border-style: solid;
					text-decoration: none;
					font-size: 12px!important;
					font-family: sans-serif;
				}
				
				.details{
					font-family: sans-serif;
					color: #464646;
					margin-left: 30px;
					font-size: 12px!important;
				}
			
			</style>
			
			<?php
			
	    			$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

	    			$mailchimp_id = get_post_meta($post->ID, 'mailchimp_id', true);
	    			
	    			$filters['campaign_id'] = $mailchimp_id;

					$campaign = $chimppress_mailchimp_api_functions->get_campaigns($filters);
			
			echo 	'<div class="header_bar">
						<a class="back_button" href="' . get_bloginfo('url') . '">
							Back to ' . get_bloginfo('') . '
						</a>
						<span class="details">
							<strong>' . get_the_title() . '</strong> <em>sent on ' . $campaign['data'][0]['send_time'] . '</em>
						</span>
					</div>';
		
			echo get_post_meta($post->ID, 'campaign_content_input', true);
			
			exit;
					
		}
			
	}
	
}

?>