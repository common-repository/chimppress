<?php

class chimppress_editor {

	function chimppress_editor(){
		$this->__construct();
	} // function

	function __construct(){
		//add actions

		//add filters
		
   		$this->display_the_editor();
   		
	} // function 	
	
	function display_the_editor(){ 
	
		global $post;
	
		$CP_ERROR = get_option('CP_ERROR');
	
		if($CP_ERROR != ''){
			
			echo '<div id="message" class="error"><p>ERROR: ' . $CP_ERROR . '</p></div>';
			
			update_option('CP_ERROR', '');
			
		}
			
	    $chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

	    $mailchimp_id = get_post_meta($post->ID, 'mailchimp_id', true);

		$filters['campaign_id'] = $mailchimp_id;

		$campaign = $chimppress_mailchimp_api_functions->get_campaigns($filters);
		
		if (!is_array($campaign)){
			$status = 'Invalid ID';
			echo $campaign;
		}else{
			$status = $campaign['data'][0]['status'];
        }  		
		?>
	
		<script>
		appendLoading();
		jQuery(document).ready(function() {
		
			make_the_changes(); 	

		});
		
		function make_the_changes(){

			jQuery('#post-preview').attr('onClick', 'chimppress_preview("<?php echo CP_url; ?>");');
			jQuery('#post-preview').attr('target', '');
			jQuery('#post-preview').attr('href', '#');
			jQuery('#post-preview').attr('id', '');
			jQuery('#preview-action a').html('Preview');
			
			jQuery('.campaign_only').remove();
						
			if(jQuery('#chimppress_type_template').attr('checked') ){
				
				jQuery('#publish').val('Save Template');
				jQuery('.submitdelete').html('Delete Template');
				jQuery('#submitdiv h3.hndle span').html('Template Actions');
				jQuery('#delete-action').css('float', 'left');
				jQuery('#chimppress_campaign_template').show();
				jQuery('.campaign_only').remove();
				jQuery('.template_only').show();
				jQuery('#view-post-btn').hide();
				
			}else{
				
				jQuery('#publish').val('Save Campaign');
				jQuery('.submitdelete').html('Delete Campaign');
				jQuery('#publishing-action').append('<div class="campaign_only"><br><br><span class="description">Use the "Save Campaign" button to save without sending. When you are ready to send, use the "Send Campaign" button.<span><br><br><input type="button" name="chimppress_send" class="button" value="Send Campaign" onClick="chimppress_campaign_send(<?php echo $post->ID; ?>);" /><input type="hidden" name="send_the_campaign" id="send_the_campaign" value="no" /></div>');
				jQuery('#delete-action').css('float', 'right');
				jQuery('#delete-action').append('<div class="campaign_only"><br><br></div>');
				jQuery('#submitdiv h3.hndle span').html('Campaign Actions');
				jQuery('#chimppress_campaign_template').hide();
				jQuery('.template_only').hide();
				jQuery('#view-post-btn').show();
				
			}
			
			jQuery('#publish').attr('onClick', 'saveContent()');
			jQuery('#save-post').remove();
			jQuery('#misc-publishing-actions').remove();
			jQuery('#export_div, #import_div').hide();
			
			<?php if(($status == 'sent' || $status == 'sending') && $mailchimp_id != ''){ ?>
			
			jQuery('input, select').each(function(){
				jQuery(this).attr('disabled', 'disabled');
				jQuery('#chimppresscampaigntemplate .controls, #chimppresscampaigntemplate .edit_button, #chimppresscampaigntemplate .image_button, #chimppress_campaign_templates, #change-permalinks, .controls, #chimppress_campaign_currently_editing, #submitdiv, #chimppress_campaign_extras, #chimppress_campaign_widgets, #chimppress_campaign_colors, #chimppress_campaign_padding').remove();
				jQuery('#chimppresscampaigntemplate .sortable').css('border', 'none');
			});
			
			<?php } ?>
		
		}	
		</script>
		
		<input type="hidden" name="mailchimp_id" value="<?php echo get_post_meta($post->ID, 'mailchimp_id', true); ?>" />
		
		<div id="chimppress_campaign_content">
		
			<table width="100%" align="center">  
			    <tr>  
			        <td id="campaign_background"> 	
						<table id="chimppresscampaigntemplate" class="campaign_editor" style="table-layout:fixed; overflow:hidden;margin: 20px auto;" width="600" align="center" cellspacing="0">
							<tbody>
								<img id="select_template_graphic" src="<?php echo CP_url . '/img/select_template.png'; ?>" />
							</tbody>
						</table>	
			        </td>  
			    </tr>  
			</table> 
		
		</div>
		
		<div id="export_div">
		
			<h4>Select the code below and paste it in to a new template using the import button.</h4>
		
			<textarea name="campaign_content_input" id="campaign_content_input"><?php echo get_post_meta($post->ID, 'campaign_content_input', true); ?></textarea>
		
		</div>
		
		<div id="import_div">
		
			<h4>Select an existing template to import.</h4>
			
			<?php
			$args = array( 'numberposts' => -1, 'post_type' => 'chimppress' );
			$campaigns = get_posts( $args ); ?>
			<select name="import_campaign_select" id="import_existing">
			<?php foreach( $campaigns as $c ){ ?>
				<option value="<?php echo $c->ID; ?>"><?php echo $c->post_title; ?></option>
			<?php }
			wp_reset_postdata(); ?>
			</select>
			
			<h4>Or</h4>
		
			<h4>Paste your exported code in here and click update.</h4>
			
			<div id="preview_import">
				<a id="close_import_preview" class="button">Close Preview</a>
				<br>
				<br>
				<div></div>
			</div>
		
			<textarea name="" id="import_textarea"></textarea>
			
			<input name="save" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="Update" onclick="saveContent()">
		
		</div>
		
		<div id="chimppress_meta_data">
<!-- 			<input type="hidden" id="chimppress_widget_order" name="chimppress_widget_order" /> -->			
		</div>
		
		<div id="preview_html" style="display:none;"></div>

		<?php
		
		parse_str( get_post_meta($post->ID, 'chimppress_cells', true), $cells );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_bg_colors', true), $cell_bg_cols );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_text_colors', true), $cell_text_cols );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_padding_top', true), $cell_padding_top );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_padding_right', true), $cell_padding_right );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_padding_bottom', true), $cell_padding_bottom );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_padding_left', true), $cell_padding_left );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_valign', true), $cell_valign );
		
		parse_str( get_post_meta($post->ID, 'chimppress_cell_align', true), $cell_align );
				
		?><script>function update_cells(){ <?php echo "\n"; 


		foreach($cells as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').html('<?php echo stripslashes( str_replace(array("\r\n", "\n", "\r"), '\ \n', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_bg_cols as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').css('backgroundColor', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}	
		
		foreach($cell_text_cols as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').css('color', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_padding_top as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').css('paddingTop', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_padding_right as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').css('paddingRight', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_padding_bottom as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').css('paddingBottom', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_padding_left as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').css('paddingLeft', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_valign as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').attr('valign', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}
		
		foreach($cell_align as $cell => $value){
			$cell_array = explode('-', $cell);
			?>jQuery('#chimppresscampaigntemplate').find('#<?php echo $cell_array[0]; ?>').find('#<?php echo $cell_array[1]; ?>').attr('align', '<?php echo stripslashes(str_replace(array("\r\n", "\n", "\r"), '<br>', $value )); ?>');<?php echo "\n";		
		}?>		
		
		jQuery('#chimppresscampaigntemplate').css('backgroundColor', '<?php echo get_post_meta($post->ID, 'chimppress_campaign_body', true); ?>');
		jQuery('#chimppresscampaigntemplate').css('color', '<?php echo get_post_meta($post->ID, 'chimppress_campaign_txt_body', true); ?>');
		jQuery('#campaign_background').css('backgroundColor', '<?php echo get_post_meta($post->ID, 'chimppress_campaign_background', true); ?>');
		jQuery('#campaign_background').css('color', '<?php echo get_post_meta($post->ID, 'chimppress_campaign_txt_background', true); ?>');		

		};</script><?php
		
		echo '<br><br><a id="export" class="button">Export this template</a><a id="import" class="button">Import a template</a>';

	} //function
	
}

?>