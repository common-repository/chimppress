<?php

class chimppress_feedback_page {

	function chimppress_feedback_page(){
		$this->__construct();
	} // function

	function __construct(){
		
		//add actions

		//add filters 
	
	} // function 	
	
	function feedback_page(){ 	
	
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
			
			<div id="icon-edit" class="icon32 icon32-posts-chimppress"><br></div><h2>ChimpPress Feedback</h2>
		    
			<div id="poststuff">
		    	
		    	<form method="post" enctype="multipart/form-data" action="<?php WP_ADMIN_URL ?>?post_type=chimppress&page=chimppress-feedback">
		    	
		    		<p>ChimpPress is currently in Beta and we would love to know what you think.</p>
		    		
		    		<p>If you find any bugs or would like to request a feature please fill in the form below.</p>
		    	
					Email (optional) <input type="text" name="email"/>
					
					<br><br>
					
					Message <textarea name="message" cols="50" rows="10"></textarea>
				
			        <p class="submit">
			        
			        <input type="submit" name="chimppress_submit_feedback" class="button-primary" id="submit" value="<?php esc_attr_e('Send feedback') ?>" />
			    	
			        </p>
			        
			    </form>		
			    
			    <h2>Donate</h2>
			    
			    <p>If you would like to help towards the development of this plugin (thanks!) you can use the donate button below.</p>
			    
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="NR9XLJENNGDKA">
					<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
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
		if(isset($_POST['chimppress_submit_feedback'])){
			
			$to = 'info@fishcantwhistle.com';
			
			$subject = 'ChimpPress Feedback';
			
			$message = 'From: ' . $_POST['email'] . ' Message:' . $_POST['message'];
			
			if(wp_mail( $to, $subject, $message )){
				echo '<div class="updated">Thank you! Your feedback has been sent.</div>';
			};
				
		}
	} //function
	
}

?>