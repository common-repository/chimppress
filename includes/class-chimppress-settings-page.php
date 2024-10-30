<?php

class chimppress_settings_page {

	function chimppress_settings_page(){
		$this->__construct();
	} // function

	function __construct(){
		
		//add actions

		//add filters 
	
	} // function 	
	
	function settings_page(){ 	
	
		$this->save_data(); //save submitted data ?>
	
		<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			jQuery(document).ready( function(jQuery) {
				jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles('chimppress-settings');
			});			
			//]]>
		</script>		
	
		<div class="wrap">
		    
		    <div id="icon-edit" class="icon32 icon32-posts-chimppress"><br></div><h2>ChimpPress Settings</h2>
		    
			<div id="poststuff">
		    			    	
		    	<form method="post" enctype="multipart/form-data" action="<?php WP_ADMIN_URL ?>?post_type=chimppress&page=chimppress-settings">
		    	
					<?php wp_nonce_field( 'chimppress_settings', 'chimppress_settings_nonce', false, true );
			    	
					$meta_boxes = do_meta_boxes('chimppress-settings', 'side', null); ?>	
				
			        <p class="submit">
			        
			        <input type="submit" name="chimppress_submit_settings" class="button-primary" id="submit" value="<?php esc_attr_e('Update Settings') ?>" />
			    	
			        </p>
			        
			    </form>					
		
		    </div>
		    
		</div>	
	<?php } //function
	
	function chimppress_settings_api_inner(){ 
	
		$chimppress_api_key = get_option('chimppress_api_key'); ?>
	
        <input type="text" name="chimppress_api_key" id="" value="<?php echo $chimppress_api_key ?>" style="<?php if ($chimppress_api_key ==''){echo "border:2px solid red;";}?>"/>	
        <p>The API key generated for you in your Mailchimp account. (go <a href="https://us2.admin.mailchimp.com/account/api/" target="_blank">here</a> to grab it or make a new one)</p>	
        
	<?php } //function
	
	function save_data(){
		if(isset($_POST['chimppress_submit_settings'])){
			//check_admin_referer('chimppress_settings');
			
			$chimppress_api_key = $_POST['chimppress_api_key'];
			
			if(update_option('chimppress_api_key', $chimppress_api_key)){
				echo '<div class="updated">Your API key has been updated!</div>';
			};
				
		}
	} //function
	
}

?>