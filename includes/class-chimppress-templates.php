<?php

class chimppress_templates {

	public $templates;

	function chimppress_templates(){
		$this->__construct();
	} // function

	function __construct(){
		//add default widgets
		$this->templates = array();
		add_action('cp_templates',array(&$this, 'default_templates'));
	} // function 	
	
	function display_templates(){ ?>
	
		<div id="chimppress_templates">
		
			<div id="chimppress_templates_templates">
		
				<?php do_action('cp_templates', $this->templates); 
				
				foreach($this->templates as $template){ ?>
					<a href="#" onClick="createDynamicTable(jQuery('#chimppresscampaigntemplate'), <?php echo $template['rows']; ?>, <?php echo $template['cols']; ?>, 1);">
						<div class="small_template">
							<table id="<?php echo $template['rows']; ?>x<?php echo $template['cols']; ?>" border="1">
								<tbody>
								</tbody>
							</table>
						</div>
					</a>
					
				    <script>
					jQuery(document).ready(function() {
						createDynamicTable(jQuery("#<?php echo $template['rows']; ?>x<?php echo $template['cols']; ?>"), <?php echo $template['rows']; ?>, <?php echo $template['cols']; ?>);
					});
				    </script>		
				<?php } ?>
			
			</div>
			
			<div id="chimppress_templates_notice" style="display:none">
				
				<?php echo 'Templates have now been disabled. You can add cells using the "extras" box to the right. If you want to use a new template you should probably add a new campaign from the left hand menu. :-)<br><br>Next step? Add a "Content" or "Image" widget by dragging it on to your campaign template using this icon <div style="float:none;" class="copy control"></div><br>If you really do want to start again you can do so by clicking <a onClick="show_templates();" style="cursor:pointer;">here</a>. <br><strong>Please be aware that you will lose anything you have added to the campaign below.</strong>'; ?>
				
			</div>
			
		</div>	
		
		<br style="clear:both;">	
	
	<?php } //function
	
	function default_templates($templates){
		
		$this->templates['1x1']['cols']=1;
		$this->templates['1x1']['rows']=1;
		
		$this->templates['3x3']['cols']=3;
		$this->templates['3x3']['rows']=3;
	
		$this->templates['2x3']['cols']=2;
		$this->templates['2x3']['rows']=3;
		
		$this->templates['1x3']['cols']=1;
		$this->templates['1x3']['rows']=3;
		
		$this->templates['3x2']['cols']=3;
		$this->templates['3x2']['rows']=2;	
		
		$this->templates['4x2']['cols']=4;
		$this->templates['4x2']['rows']=2;	
		
		$this->templates['4x3']['cols']=4;
		$this->templates['4x3']['rows']=3;								
		
	} //function	
	
	function extras(){ 
		global $post; ?>
		Add <input type="text" size="2" id="extra_rows" /> row(s) with <input type="text" size="2" id="extra_cols" /> cell(s) at the <select id="position_to_insert"><option value="top">top</option><option value="bottom">bottom</option></select> of the table. <input type="button" class="button" value="Go" onClick="add_row();"/>
		<input id="current_template_cols" name="current_template_cols" type="hidden" value="<?php echo get_post_meta($post->ID, 'current_template_cols', true); ?>" /><input id="current_template_rows" name="current_template_rows" type="hidden" value="<?php echo get_post_meta($post->ID, 'current_template_rows', true); ?>" />
		<input type="hidden" id="cells_array" name="cells_array" value="<?php echo get_post_meta($post->ID, 'chimppress_cells_array', true); ?>" />
	<?php }
	
}

?>