<?php

$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

class chimppress_mailchimp_api_functions {

	var $mailchimp_api;

	function chimppress_mailchimp_api_functions(){
		$this->__construct();
	} // function

	function __construct(){
		
		//add actions

		//add filters 
		
		$this->mailchimp_api = new MCAPI(get_option('chimppress_api_key'));
	
	} // function 

	function get_campaigns($filters=""){
	
		if(is_array($filters)){
			$filters_string = implode(',', $filters);
		}else{
			$filters_string = $filters;
		}
				
		$cache = $this->check_cache('campaigns'.$filters_string);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaigns($filters);
						
			$this->update_cache('campaigns'.$filters_string, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}

	} // function
	
	function get_lists(){
	
		$cache = $this->check_cache('get_lists');
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->lists();
						
			$this->update_cache('get_lists', $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} //function
	
	function create_campaign($type, $opts, $content){
	
		$campaign_id = $this->mailchimp_api->campaignCreate($type, $opts, $content);
		
		if ($this->mailchimp_api->errorCode){
			update_option( 'CP_ERROR', $this->mailchimp_api->errorMessage );
			return 'ERROR ' . $this->mailchimp_api->errorMessage;
		} else {
			return $campaign_id;
		}
	
	} // function
	
	function update_campaign($id, $opts, $content){
		
		foreach($opts as $key => $value){
	
			$campaign_update = $this->mailchimp_api->campaignUpdate($id, $key, $value);
		
			if ($this->mailchimp_api->errorCode){
				update_option( 'CP_ERROR', $this->mailchimp_api->errorMessage );
				return 'ERROR ' . $this->mailchimp_api->errorMessage;
			}
			
		}
				
		$campaign_update = $this->mailchimp_api->campaignUpdate($id, 'content', $content);
	
		if ($this->mailchimp_api->errorCode){
			update_option( 'CP_ERROR', $this->mailchimp_api->errorMessage );
			return 'ERROR ' . $this->mailchimp_api->errorMessage;
		} else {
			return $campaign_update;
		}
	
	} // function
	
	function send_campaign($id){
	
		$this->mailchimp_api->campaignSendNow($id);
		
		if ($this->mailchimp_api->errorCode){
			update_option( 'CP_ERROR', $this->mailchimp_api->errorMessage );
			return 'ERROR ' . $this->mailchimp_api->errorMessage;
		} else {
			return true;
		}
	
	} // function
	
	function list_members($list){
	
		return $this->mailchimp_api->listMembers($list);
	
	}
	
	function list_member_info($list, $email){
	
		if(is_array($email)){
			$email_string = implode(',', $email);
		}else{
			$email_string = $email;
		}
	
		$cache = $this->check_cache('list_member_info'.$list.$email_string);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->listMemberInfo($list, $email);
						
			$this->update_cache('list_member_info'.$list.$email_string, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
	
	} // function
	
	function list_update_member($list, $id, $merge_vars){
	
		$this->mailchimp_api->listUpdateMember($list, $id, $merge_vars);
				
		if ($this->mailchimp_api->errorCode){
			return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
		} else {
			return '<div id="message" class="updated below-h2">Subscriber updated!</div>';
		}
	
	} // function
	
	function list_subscribe($list, $id, $merge_vars){
	
		$this->mailchimp_api->listSubscribe( $list, $id, $merge_vars );
		
		if ($this->mailchimp_api->errorCode){
			return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
		} else {
			return'<div id="message" class="updated below-h2">The subscriber has been sent a confirmation email!<br/>Once they have confirmed they will show up in your subscriber list!</div>';
		}
	
	} // function
	
	function list_unsubscribe($list, $id){
	
		return $this->mailchimp_api->listUnsubscribe($list, $id, '', FALSE);
	
	} // function
	
	function campaignStats($id){
	
		$cache = $this->check_cache('campaignStats'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignStats($id);
						
			$this->update_cache('campaignStats'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}		
	
	} // function
	
	function campaignMembers($id){
	
		$cache = $this->check_cache('campaignMembers'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignMembers($id);
						
			$this->update_cache('campaignMembers'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignOpenedAIM($id){
	
		$cache = $this->check_cache('campaignOpenedAIM'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignOpenedAIM($id);
						
			$this->update_cache('campaignOpenedAIM'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignNotOpenedAIM($id){
	
		$cache = $this->check_cache('campaignNotOpenedAIM'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignNotOpenedAIM($id);
						
			$this->update_cache('campaignNotOpenedAIM'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignGeoOpens($id){
	
		$cache = $this->check_cache('campaignGeoOpens'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignGeoOpens($id);
						
			$this->update_cache('campaignGeoOpens'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignClickStats($id){
	
		$cache = $this->check_cache('campaignClickStats'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignClickStats($id);
						
			$this->update_cache('campaignClickStats'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignBounceMessages($id){
	
		$cache = $this->check_cache('campaignBounceMessages'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignBounceMessages($id);
						
			$this->update_cache('campaignBounceMessages'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignAdvice($id){
	
		$cache = $this->check_cache('campaignAdvice'.$id);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignAdvice($id);
						
			$this->update_cache('campaignAdvice'.$id, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function campaignGeoOpensForCountry($id, $code){
	
		$cache = $this->check_cache('campaignGeoOpensForCountry'.$id.$code);
	
		if($cache){
		
			return $cache;
		
		}else{
		
			$data = $this->mailchimp_api->campaignGeoOpensForCountry($id, $code);
						
			$this->update_cache('campaignGeoOpensForCountry'.$id.$code, $data);
			
			if ($this->mailchimp_api->errorCode){
				return '<div id="message" class="error below-h2">' . $this->mailchimp_api->errorMessage . '</div>';
			} else {
				return $data;
			}
		
		}
		
	} // function
	
	function check_cache($option){
	
		$cached = get_option($option);
		
		if($cached != ''){
								
			if ($cached['timestamp'] > (time() - 10 * 60)) {
					
				return $cached['data'];
			
			}else{
			
				return false;
			
			}
					
		}else{
		
			return false;
		
		}
	
	}
	
	function update_cache($option, $data){
				
			$new_data = array('timestamp'=>time(), 'data'=>$data);
			
			update_option($option, $new_data);
	
	}
	
}

?>