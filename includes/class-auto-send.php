<?php

$chimppress_auto_send = new chimppress_auto_send;

class chimppress_auto_send {

	private $post_id;

	private $html;

	private $template_id;

	private $post;

	function chimppress_auto_send(){
		$this->__construct();
	} // function

	function __construct(){
		//add actions
		add_action('new_to_publish', array(&$this, 'send_emails'));
		add_action('draft_to_publish', array(&$this, 'send_emails'));
		add_action('auto-draft_to_publish', array(&$this, 'send_emails'));
		add_action('pending_to_publish', array(&$this, 'send_emails'));
		add_action('private_to_publish', array(&$this, 'send_emails'));
		add_action('future_to_publish', array(&$this, 'send_emails'));
		//add filters

	} // function

	function send_emails($post_id){

		if ( !wp_is_post_revision( $post_id ) ) {

			$this->post_id = $post_id;

			$this->post = get_post($this->post_id);

			$post_type = get_post_type( $post_id );

			$tax_array = wp_get_post_categories($post_id->ID, array('fields' => 'names'));

			$args = array(
				'post_type' => 'chimppress',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'template_post_type',
						'value' => $post_type,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'chimppress_type',
						'value' => 'template',
						'compare' => '=',
					)
				)
			);

			$templates = get_posts( $args );

			foreach($templates as $template){

				$send_email = false;

				$template_tax = get_post_meta($template->ID, 'template_taxonomies', TRUE);

				if($template_tax !=''){

					$tax = explode(',', $template_tax );

					foreach($tax as $t){

						if(in_array($t, $tax_array)){

							$send_email = true;

						}

					}

				}else{

					$send_email = true;

				}

				if($send_email){

					$this->template_id = $template->ID;

					$this->construct_email($template->ID);

					$this->send_email();

				}

			}

			//exit;

		}

	}

	function construct_email($id){

		$html = get_post_meta($id, 'campaign_content_input', true);

		$post = get_post($this->post_id);

		$content = nl2br($post->post_content);

		$title = $post->post_title;

		$link = $post->guid;

		$html = str_replace('%content%', $content, $html);

		$html = str_replace('%title%', $title, $html);

		$html = str_replace('%link%', $link, $html);

		$this->html = $html;

	}

	function send_email(){

		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

		$type = 'regular';

    	$opts['list_id'] = get_post_meta($this->template_id, 'campaign_list', true);
		$opts['subject'] = $this->post->post_title;
		$opts['title'] = $this->post->post_title;
		$opts['from_email'] = get_post_meta($this->template_id, 'chimppress_from_email', true);
		$opts['to_name'] = get_post_meta($this->template_id, 'chimppress_to_name', true);
		$opts['from_name'] = get_post_meta($this->template_id, 'chimppress_from_name', true);
		$opts['generate_text'] = true;

		$content = array(
			'html' => $this->html
		);

		$new_campaign_id = $chimppress_mailchimp_api_functions->create_campaign($type, $opts, $content);

		$pos = strpos($new_campaign_id, "ERROR");

		if ($pos === false) {

			$try_sending_it = $chimppress_mailchimp_api_functions->send_campaign($new_campaign_id);

			//if ($try_sending_it) {
				$this->add_to_history($new_campaign_id);
			//}

		}

	}

	function add_to_history($new_campaign_id){

		delete_post_meta($this->template_id, 'history');

		$history = get_post_meta($this->template_id, 'history', true);

		if(!is_array($history)){
			$history = array();
		}

		array_push($history, $new_campaign_id);

		update_post_meta($this->template_id, 'history', $history);

	}

}

?>