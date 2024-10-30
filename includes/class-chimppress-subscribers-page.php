<?php

class chimppress_subscribers_page {

	private $list_members;

	function chimppress_subscribers_page(){
		$this->__construct();
	} // function

	function __construct(){
	
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false, false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false, false );	
		
		if (isset($_GET['list']) && $_GET['list'] != ''){
	
	    	add_meta_box( 
	        	'chimppress_subscribers',
	       		__( 'Subscribers', 'chimppress' ),
	    	    array( &$this, 'chimppress_subscribers_inner' ),
	    	    'chimppress-subscribers',
	    	    'side',
	    	    'core'
	    	);	
	    
	    }
	    
	    if (isset($_GET['id'])){
	    
	    	add_meta_box( 
	    	    'chimppress_subscriber',
	    	    __( 'Subscriber', 'chimppress' ),
	    	    array( &$this, 'chimppress_subscriber_inner' ),
	    	    'chimppress-subscribers',
	    	    'side',
	    	    'core'
	    	);
	    
	    }
	    
    	add_meta_box( 
    	    'chimppress_subscribers_list',
    	    __( 'Select List', 'chimppress' ),
    	    array( &$this, 'chimppress_subscribers_list_inner' ),
    	    'chimppress-subscribers',
    	    'side',
    	    'core'
    	);
	    
	    
	
	} // function 	
	
	function subscribers_page(){ ?>
	
		<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			jQuery(document).ready( function(jQuery) {
				jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles('chimppress-settings');
			});			
			//]]>
		</script>		
	
		<div class="wrap">
			
			<div id="icon-edit" class="icon32 icon32-posts-chimppress"><br></div><h2>Subscribers  <a class="add-new-h2" href="<?php echo admin_url(); ?>edit.php?post_type=chimppress&page=chimppress-subscribers&id=addnew">Add New</a></h2>
		    
			<div id="poststuff">
		    			    	
				<?php wp_nonce_field( 'chimppress_settings', 'chimppress_settings_nonce', false, true );
			    	
				$meta_boxes = do_meta_boxes('chimppress-subscribers', 'side', null); ?>					
		
		    </div>
		    
		</div>	
		
		<script type="text/javascript">
		function selectlist(){
			document.forms["selectlistform"].submit();
		}
		function deletecheck(id){
			var agree = confirm("Are you sure you want to delete this subscriber?");
			if (agree){
				document.forms["deletesub" + id].submit();
			}
		}
		</script>


	<?php } //function
	
	function chimppress_subscribers_list_inner(){ 
	
		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;
		
		$lists = $chimppress_mailchimp_api_functions->get_lists();
	
		?>
	
		<form name="selectlistform" method="GET" action="<?php echo admin_url(); ?>edit.php?post_type=chimppress&page=chimppress-subscribers">
			<input type="hidden" name="post_type" value="<?php echo esc_attr($_GET['post_type']); ?>" />
			<input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>" />
			<select name="list" onchange="selectlist()">
				<option value="">Select list</option>
				<?php
		
				foreach ($lists['data'] as $list){?>
					<option <?php if(isset($_GET['list']) && esc_attr($_GET['list']) == $list['id']){echo "selected ";}?>value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>	
				<?php } ?>

			</select>	
		</form>
        
	<?php } //function
	
	function chimppress_subscribers_inner(){ 
			
		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;
		
		if (isset($_POST['deleteid'])) {
			$deletesub = $chimppress_mailchimp_api_functions->list_unsubscribe(esc_attr($_GET['list']), $_POST['deleteid']);
		};
			
		$members = $chimppress_mailchimp_api_functions->list_members($_GET['list']);
			
		if (isset($_GET['list']) && $members['total'] > 0) {?>
		
			<p>
			
				<?php 
				echo $members['total'] . ' subscriber';
				if($members['total'] > 1){
					 echo 's';
				} ?>
			</p>
			
			<?php $this->generate_table($members, $_GET['list'], $_GET['post_type'], $_GET['page']);
				
		}elseif(isset($_GET['list']) && $members['total'] == 0 && $_GET['list'] <> ''){ ?>
			<p>There are no subscribers in this list. <a class="" href="<?php echo admin_url(); ?>edit.php?post_type=chimppress&page=chimppress-subscribers&id=addnew">Add one!</a></p>
		<?php }else{ ?>
			<p>Please select a list</p>
		<?php } ?>	
        
	<?php } //function
	
	function chimppress_subscriber_inner(){ 
	
		$edit = false;
	
		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;
		
		if (isset($_POST['Edit'])){

			$merge_vars = array('FNAME'=>$_POST['first_name'], 'LNAME'=>$_POST['last_name']);
	
			$message = $chimppress_mailchimp_api_functions->list_update_member($_POST['listid'], $_POST['email_address'], $merge_vars);
	
			$edit=true;

		}
		
		if (isset($_POST['Submit'])){

			$merge_vars = array('FNAME'=>$_POST['first_name'], 'LNAME'=>$_POST['last_name']);
	
			$message = $chimppress_mailchimp_api_functions->list_subscribe( $_POST['listid'], $_POST['email_address'], $merge_vars );

		}
		
		$lists = $chimppress_mailchimp_api_functions->get_lists();
		
		if ($_GET['id'] != 'addnew'){
		
			$memberinfo = $chimppress_mailchimp_api_functions->list_member_info($_GET['list'], $_GET['id']);
			
			$edit = true;
			
		}
	
		?>
		
		<a name="subscriber"></a>
		
		<?php echo (isset($message) ? $message : ''); ?>
		
	    <form method="post" enctype="multipart/form-data" action="<?php echo admin_url(); ?>edit.php?post_type=<?php echo esc_attr($_GET['post_type']); ?>&page=<?php echo esc_attr($_GET['page']); ?>&list=<?php echo (isset($_GET['list']) ? esc_attr($_GET['list']) : '' ); ?>&id=<?php if($edit){ echo $memberinfo['data'][0]['id']; }else{ echo (isset($_GET['id']) ? $_GET['id'] : 'addnew'); } ; ?>#subscriber">
		         
			<label>First Name</label> 
					
			<input type="text" value="<?php echo (isset($memberinfo['data'][0]['merges']['FNAME']) ? $memberinfo['data'][0]['merges']['FNAME'] : ''); ?>" name="first_name" />
			
			<p class="description">The Subscribers first name.</p>				
		
			<label>Last Name</label>
		
			<input type="text" value="<?php echo (isset($memberinfo['data'][0]['merges']['LNAME']) ? $memberinfo['data'][0]['merges']['LNAME'] : ''); ?>" name="last_name" />
			
			<p class="description">The Subscribers last name.</p>				
		        
			<label>Email Address</label>
					
			<input type="text" value="<?php echo (isset($memberinfo['data'][0]['merges']['EMAIL']) ? $memberinfo['data'][0]['merges']['EMAIL'] : ''); ?>" name="email_address" />
			
			<p class="description">The Subscribers email address.</p>				
			
			<?php if($edit){?>
		
				<input type="hidden" value="<?php echo esc_attr($_GET['list']); ?>" name="listid" />
			
			<?php }else{ ?>
					
				<label>List</label> 
								
					<select name="listid">
				
						<?php foreach ($lists['data'] as $list){?>
							<option <?php if(isset($_GET['list']) && esc_attr($_GET['list']) == $list['id']){echo "selected ";} ?>value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>	
						<?php } ?>
				
					</select>
				
					<p class="description">Select the list to add this Subscriber to.</p>
							
			<?php } ?>

			<input type="hidden" value="<?php echo (isset($_GET['id']) ? intval($_GET['id']) : ''); ?>" name="fanid"/>
		
       		<p class="submit">
        
        		<?php if ($edit){ ?>
        
        			<input type="submit" name="Edit" class="button-primary" id="Submit" value="<?php esc_attr_e('Edit Subscriber') ?>" />
        
        		<?php }else{ ?>
        
        			<input type="submit" name="Submit" class="button-primary" id="submit" value="<?php esc_attr_e('Add Subscriber') ?>" />
        
        		<?php } ?>
    	
        	</p>
        
    	</form>	
		
		<?php
	
	} // function
	
	function generate_table($members, $list, $post_type="", $page="", $edit_delete=true, $timestamp=true, $region=true, $action=false, $opened=false){

		$i=0;
		
		$emails = array();
		
		foreach($members['data'] as $member){ 

			if(is_array($member)){
				$emails[$i] = $member['email'];
			}else{
				$emails[$i] = $member;
			}
		
			$i++;				
		
		}
		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;
	
		?>
	
			<table cellspacing="0" class="widefat post fixed">
				<thead>
					<tr>
						<th style="" class="manage-column" id="" scope="col">Name</th>
						<th style="" class="manage-column" id="" scope="col">Email Address</th>
						<th style="" class="manage-column" id="" scope="col">Member Rating <a href="http://www.mailchimp.com/kb/article/how-do-you-determine-the-ratings-for-my-member-activity" target="_blank">(i)</a></th>
						<?php if($action){ ?>
							<th style="" class="manage-column" id="" scope="col">Delivery Status</th>
						<?php } ?>
						<?php if($opened){ ?>
							<th style="" class="manage-column" id="" scope="col">Open Count</th>
						<?php } ?>
						<?php if($region){ ?>
							<th style="" class="manage-column" id="" scope="col">Region</th>
						<?php } ?>
						<?php if($timestamp){ ?>
							<th style="" class="manage-column" id="" scope="col">Became a Subscriber</th>
						<?php } ?>
						<?php if($edit_delete){ ?>
							<th style="" class="manage-column" id="" scope="col"></th>
							<th style="" class="manage-column" id="" scope="col"></th>
						<?php } ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="" class="manage-column" id="" scope="col">Name</th>
						<th style="" class="manage-column" id="" scope="col">Email Address</th>
						<th style="" class="manage-column" id="" scope="col">Member Rating <a href="http://www.mailchimp.com/kb/article/how-do-you-determine-the-ratings-for-my-member-activity" target="_blank">(i)</a></th>
						<?php if($action){ ?>
							<th style="" class="manage-column" id="" scope="col">Delivery Status</th>
						<?php } ?>	
						<?php if($opened){ ?>
							<th style="" class="manage-column" id="" scope="col">Open Count</th>
						<?php } ?>											
						<?php if($region){ ?>
							<th style="" class="manage-column" id="" scope="col">Region</th>
						<?php } ?>
						<?php if($timestamp){ ?>
							<th style="" class="manage-column" id="" scope="col">Became a Subscriber</th>
						<?php } ?>
						<?php if($edit_delete){ ?>
							<th style="" class="manage-column" id="" scope="col"></th>
							<th style="" class="manage-column" id="" scope="col"></th>
						<?php } ?>
					</tr>
				</tfoot>
				<tbody>	
					<?php $i =0; 
					foreach($members['data'] as $member){ 
					
						$this->list_members = $chimppress_mailchimp_api_functions->list_member_info($list, $member['email']);
								
						if(is_array($member)){
							$memberinfo = $this->search_array($this->list_members['data'], 'email', $member['email'] );
						}else{
							$memberinfo = $this->search_array($this->list_members['data'], 'email', $member );
						} ?>
			
						<tr style="line-height:25px;">
							<td class=""><?php echo $memberinfo['merges']['FNAME'] . " " . $memberinfo['merges']['LNAME']; ?></td>
							<td class=""><?php echo $member['email']; ?></td>
							<td class="">
								<?php $rating =  $memberinfo['member_rating']; 
								$ii=1;
								while($ii<=$rating){
  									echo '<img src="' . CP_url . '/img/star.png" />';
  									$ii++;
								}
								while($ii<=5){
									echo '<img src="' . CP_url . '/img/star_empty.png" />';
									$ii++;
								}
								?>
							</td>
							<?php if($action){ ?>
								<td class=""><?php echo $member['status']; ?></td>
							<?php } ?>
							<?php if($opened){ ?>
								<td class=""><?php echo $member['open_count']; ?></td>
							<?php } ?>							
							<?php if($region){ ?>
								<td class=""><?php echo (isset($memberinfo['geo']['region']) ? $memberinfo['geo']['region'] : ''); ?></td>
							<?php } ?>
							<?php if($timestamp){ ?>
								<td class=""><?php echo $member['timestamp']; ?></td>
							<?php } ?>
							<?php if($edit_delete){ ?>
								<td class=""><a  class="button" style="display:block; width:28px;" href="<?php echo admin_url(); ?>edit.php?post_type=<?php echo $post_type; ?>&page=<?php echo $page; ?>&list=<?php echo $list; ?>&id=<?php echo $memberinfo['id']; ?>#subscriber">EDIT</a></td>
								<td class="">
									<form id="deletesub<?php echo $i; ?>" name="deletesub<?php echo $i; ?>" method="POST" enctype="multipart/form-data" action="<?php echo admin_url(); ?>edit.php?post_type=<?php echo $post_type; ?>&page=<?php echo $page; ?>&list=<?php echo $list; ?>">
										<input type="hidden" name="deleteid" value="<?php echo $memberinfo['email']; ?>"/>
										<a onclick="deletecheck(<?php echo $i; ?>)" class="deletion">Delete</a>
									</form>
								</td>
							<?php } ?>
						</tr>
						<?php $i++; 
					} ?>
				</tbody>
			</table>
			
			<?php
	
	} // function
	
	function search_array($array, $key, $value) {
	  $return = array();   
	  if(is_array($array)){
	 	 foreach ($array as $k=>$subarray){  
	 	   if (isset($subarray[$key]) && $subarray[$key] == $value) {
	 	     $return = $subarray;
	 	     return $return;
	 	   } 
	  	}
	  }
	} // function
	
}

?>