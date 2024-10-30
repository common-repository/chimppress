<?php

class chimppress_widgets {

	function chimppress_widgets(){
		$this->__construct();
	} // function

	function __construct(){
		//add default widgets
		add_action('cp_widgets',array(&$this, 'title_widget'));
		add_action('cp_widgets',array(&$this, 'image_widget'));
		$this->display_widgets();
	} // function 	
	
	function display_widgets(){ ?>
	
		<div class="sortable widgets_meta_box sortable_area">
			<?php do_action('cp_widgets'); ?>
		</div>	
		
		<br style="clear:both;">	
	
	<?php } //function
	
	function title_widget(){
		echo '
			<p>Use the content widget to add content to your campaign.</p>
			<div class="template_only">
				<p>As well as adding formatted content you can also use the following tags within your campaign-</p>
				<ul>
					<li><em>%title%</em> - The title of the post</li>
					<li><em>%content%</em> - The content of the post</li>
					<li><em>%link%</em> - The link to the post on this website</li>
				</ul>
			</div>
		';
		echo '<div class="item" id="defaulttitle"><div id="title_widget_edit" class="edit">Content</div></div>';
	}
	
	function image_widget(){
		echo '<div class="item" id="defaultimage"><div id="image_widget_edit" class="edit_image">Image Widget</div></div>';
	}
	
}

?>