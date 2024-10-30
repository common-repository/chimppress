<?php

class chimppress_meta_boxes {

	function chimppress_meta_boxes(){
		$this->__construct();
	} // function

	function __construct(){
		//add actions

		add_action( 'admin_init', array( &$this, 'chimppress_add_meta_boxes' ) );
		add_action( 'post_updated', array( &$this, 'chimppress_meta_boxes_save_data' ), 1, 2 );
		//add filters

	} // function

	function chimppress_add_meta_boxes() {
		//---EDITOR PAGE
		// the headers box
	    add_meta_box(
	        'chimppress_campaign_header',
	        __( 'Campaign Headers', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_headers_inner' ),
	        'chimppress' ,
	        'normal',
	        'high'
	    );
	    // the type box
	    add_meta_box(
	        'chimppress_campaign_type',
	        __( 'Campaign Type', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_type_inner' ),
	        'chimppress' ,
	        'normal',
	        'high'
	    );
	    //the templates
	    add_meta_box(
	        'chimppress_campaign_templates',
	        __( 'Templates', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_templates_inner' ),
	        'chimppress' ,
	        'normal',
	        'high'
	    );
	    //extra cols&rows
	    add_meta_box(
	        'chimppress_campaign_extras',
	        __( 'Extras', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_extras_inner' ),
	        'chimppress' ,
	        'side',
	        'low'
	    );
	    //stats
	    add_meta_box(
	        'chimppress_campaign_stats',
	        __( 'Campaign Stats', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_stats_inner' ),
	        'chimppress' ,
	        'normal',
	        'high'
	    );
	    //the editor
	    add_meta_box(
	        'chimppress_campaign_editor',
	        __( 'Campaign', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_editor_inner' ),
	        'chimppress' ,
	        'normal',
	        'high'
	    );
	    //widgets
	    add_meta_box(
	        'chimppress_campaign_widgets',
	        __( 'Widgets', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_widgets_inner' ),
	        'chimppress' ,
	        'side',
	        'low'
	    );
	    add_meta_box(
	        'chimppress_campaign_lists',
	        __( 'Lists', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_lists_inner' ),
	        'chimppress' ,
	        'side',
	        'high'
	    );
	    //currently editing
	    add_meta_box(
	        'chimppress_campaign_currently_editing',
	        __( 'Currently Editing', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_currently_editing_inner' ),
	        'chimppress' ,
	        'side',
	        'high'
	    );
	    //colors
	    add_meta_box(
	        'chimppress_campaign_colors',
	        __( 'Colors', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_colors_inner' ),
	        'chimppress' ,
	        'side',
	        'low'
	    );
	    //alignment
	    add_meta_box(
	        'chimppress_alignment',
	        __( 'Alignment', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_align_inner' ),
	        'chimppress' ,
	        'side',
	        'low'
	    );
	    //padding
	    add_meta_box(
	        'chimppress_campaign_padding',
	        __( 'Padding', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_padding_inner' ),
	        'chimppress' ,
	        'side',
	        'low'
	    );
	    //template settings
	    add_meta_box(
	        'chimppress_campaign_template',
	        __( 'Template Settings', 'chimppress' ),
	        array( &$this, 'chimppress_campaign_template_inner' ),
	        'chimppress' ,
	        'side',
	        'high'
	    );
	    //---SETTINGS PAGE
	    //nonces for saving meta boxes
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false, false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false, false );
	    $chimppress_settings_page = new chimppress_settings_page;
	    add_meta_box(
	        'chimppress_settings_api',
	        __( 'API key', 'chimppress' ),
	        array( &$chimppress_settings_page, 'chimppress_settings_api_inner' ),
	        'chimppress-settings',
	        'side',
	        'core'
	    );

	} //function

	function chimppress_campaign_headers_inner() {

		global $post;

		wp_nonce_field( plugin_basename( __FILE__ ), 'chimppress_campaign_headers_nonce' );
		//from email address
		echo '<label for="chimppress_from_email">';
		_e("The from email address of your campaign", 'chimppress' );
		echo '</label><br><br> ';
		echo '<input type="text" id="chimppress_from_email" name="chimppress_from_email" value="'.get_post_meta($post->ID, 'chimppress_from_email', true).'" size="25" /><br>';
		//from name
		echo '<label for="chimppress_from_name">';
		_e("<br>The from name of your campaign (not an email address)", 'chimppress' );
		echo '</label><br><br> ';
		echo '<input type="text" id="chimppress_from_name" name="chimppress_from_name" value="'.get_post_meta($post->ID, 'chimppress_from_name', true).'" size="25" /><br>';
		//to name
		echo '<label for="chimppress_to_name">';
		_e("<br>The to name of your campaign (not an email address)", 'chimppress' );
		echo '</label><br><br> ';
		echo '<input type="text" id="chimppress_to_name" name="chimppress_to_name" value="'.get_post_meta($post->ID, 'chimppress_to_name', true).'" size="25" /><br>';
	} //function

	function chimppress_campaign_type_inner() {

		global $post;

		echo '<label for="chimppress_type">';
		_e("A Standard campaign will be sent when you click \"Send\".<br>A Template campaign can be configured to be sent when ever a new post is published.", 'chimppress' );
		echo '</label><br><br> ';
		echo '<input type="radio" class="chimppress_type" id="chimppress_type_standard" name="chimppress_type" '; if(get_post_meta($post->ID, 'chimppress_type', true)=='standard'){echo ' checked ';}; echo ' value="standard"  /> Standard<br>';
		echo '<input type="radio" class="chimppress_type" id="chimppress_type_template" name="chimppress_type" '; if(get_post_meta($post->ID, 'chimppress_type', true)=='template'){echo ' checked ';}; echo ' value="template"  /> Template<br>';

	} //function

	function chimppress_campaign_editor_inner(){
		new chimppress_editor; //the campaign editor
	}//function

	function chimppress_campaign_widgets_inner(){
		new chimppress_widgets; //the widgets
	} //function

	function chimppress_campaign_templates_inner(){
		$templates_class = new chimppress_templates;
		$templates_class->display_templates(); //the templates
	} //function

	function chimppress_campaign_extras_inner(){
		$templates_class = new chimppress_templates;
		$templates_class->extras(); //the extras
	} //function

	function chimppress_campaign_lists_inner(){

		global $post;

		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

		$lists = $chimppress_mailchimp_api_functions->get_lists();

		?>
		<select name="campaign_list">
			<?php

			foreach ($lists['data'] as $list){

				echo '<option value="' . $list['id'] . '"' ;
				if(get_post_meta($post->ID, 'campaign_list', true) == $list['id']){
					echo 'selected="selected"';
				}
				echo '>' . $list['name'] . '</option>';
			}

			?>
		</select>
		<?php

	} //function

	function chimppress_campaign_currently_editing_inner(){

		_e("Click on a section of your campaign or select a section below then alter the layout settings using the Padding & Colors sections.", 'chimppress' ); ?>
		<br><br>
		<select id="select_campaign_area_to_edit">
			<option value="">---Please select---</option>
			<option value="#chimppresscampaigntemplate">Campaign Body</option>
			<option value="#campaign_background">Campaign Background</option>
		</select><?php
		echo '<h2>Currently editing <span id="chimppress_color_cell">none</span></h2>';

	}

	function chimppress_campaign_colors_inner(){

		global $post;

		 ?>
		 <h2>Background Color</h2><br>
		 <div id="colorpicker_background"></div>
		 <input type="text" value="" id="colorpicker_background_input" />
		 <input type="button" value="Save this color" class="button" onClick="saveColor('colorpicker_background_input');"/><?php
		 echo '<h2>Text Color</h2><br>';
		 ?><div id="colorpicker_text"></div>
		 <input type="text" value="" id="colorpicker_text_input" />
		 <input type="button" value="Save this color" class="button" onClick="saveColor('colorpicker_text_input');"/>
		 <br><?php
		 echo "<h2>Saved Colors</h2>";
		 $saved_colors = get_post_meta($post->ID, 'chimppress_saved_colors', true);?>
		 <input id="chimppress_saved_colors" name="chimppress_saved_colors" type="hidden" value="<?php echo get_post_meta($post->ID, 'chimppress_saved_colors', true); ?>"/>
		 <div id="saved_colors"></div>
		 <br style="clear:both;">

	<?php } //function

	function chimppress_campaign_align_inner(){

		global $post;

		 ?>
		 <p>
		 	Vertical Align:
		 	<select name="chimppress_valign" id="chimppress_valign">
		 		<option value="">Select</option>
		 		<option value="top">Top</option>
		 		<option value="bottom">Bottom</option>
		 	</select>
		 </p>
		 <p>
		 	Horizontal Align:
		 	<select name="chimppress_align" id="chimppress_align">
		 		<option value="">Select</option>
		 		<option value="right">Right</option>
		 		<option value="left">Left</option>
		 	</select>
		 </p>
		 <br style="clear:both;">

	<?php } //function

	function chimppress_campaign_padding_inner(){

		?>

		<script type="text/javascript">
			jQuery(document).ready( function() {
				jQuery(".padding_amount_top").slider({
					from: 0,
					to: 50,
					step: 1,
					round: 1,
					dimension: 'px',
					skin: "round_plastic",
					callback: function(v){
						paddingTop(v);
					}
				});
				jQuery(".padding_amount_right").slider({
					from: 0,
					to: 50,
					step: 1,
					round: 1,
					dimension: 'px',
					skin: "round_plastic",
					callback: function(v){
						paddingRight(v);
					}
				});
				jQuery(".padding_amount_bottom").slider({
					from: 0,
					to: 50,
					step: 1,
					round: 1,
					dimension: 'px',
					skin: "round_plastic",
					callback: function(v){
						paddingBottom(v);
					}
				});
				jQuery(".padding_amount_left").slider({
					from: 0,
					to: 50,
					step: 1,
					round: 1,
					dimension: 'px',
					skin: "round_plastic",
					callback: function(v){
						paddingLeft(v);
					}
				});
			});
		</script>

<!-- 		 <h2>Padding</h2><br> -->
		 <span style="width:20%; float:left;">Top:</span>
		<div id="padding_slider_top" style="width:75%; float:right;">
			<input class="padding_amount_top" type="slider" name="cell_padding_top" value="0" />
		</div>
		<br style="clear:both;">
		<br style="clear:both;">
		<br style="clear:both;">
		<span style="width:20%; float:left;">Right:</span>
		<div id="padding_slider_right" style="width:75%; float:right;">
			<input class="padding_amount_right" type="slider" name="cell_padding_right" value="0" />
		</div>
		<br style="clear:both;">
		<br style="clear:both;">
		<br style="clear:both;">
		<span style="width:20%; float:left;">Bottom:</span>
		<div id="padding_slider_bottom" style="width:75%; float:right;">
			<input class="padding_amount_bottom" type="slider" name="cell_padding_bottom" value="0" />
		</div>
		<br style="clear:both;">
		<br style="clear:both;">
		<br style="clear:both;">
		<span style="width:20%; float:left;">Left:</span>
		<div id="padding_slider_left" style="width:75%; float:right;">
			<input class="padding_amount_left" type="slider" name="cell_padding_left" value="0" />
		</div>
		 <br style="clear:both;">
		 <br style="clear:both;">
		 <br style="clear:both;">

		 <?php

	}

	function chimppress_campaign_template_inner(){

		global $post;

		$template_post_type = explode(',',get_post_meta($post->ID, 'template_post_type', true)); ?>

		<label for="template_post_type">Use this template to notify users when the following post types are updated.<br>Optionally select categories or taxonomies to be more specific. Pleas note, if no categories or taxonomies are selected, the template will be used when any posts within that post type are updated.</label>

		<?php $post_types=get_post_types(array('public' => true),'names');

		$excluded_terms = array();

		$excluded_taxonomies = array('post_tag', 'post_format');

		foreach ($post_types as $post_type ) {

			if($post_type != 'attachment'){ ?>

				<div class="template_post_type_single"><input class="template_post_type_checkbox" type="checkbox" value="<?php echo $post_type; ?>" name="template_post_type[]" <?php if(in_array($post_type,$template_post_type)){echo 'checked="checked"';} ?> /><strong><?php echo $post_type; ?></strong>

				<?php $taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type ) );

				if  ($taxonomies) {

					foreach ($taxonomies  as $taxonomy ) {

						if(!in_array($taxonomy, $excluded_taxonomies)){

							$terms = get_terms( $taxonomy, array('hide_empty'=>false) );

							$count = count($terms);

							if ( $count > 0 ){

								echo '<img src="'.CP_url.'/img/down_arrow.png" class="show_hide_tax" alt="Show/Hide taxonomies" />';

								echo "<div class=\"terms\">";

									foreach ( $terms as $term ) {

										if(!in_array($term->name, $excluded_terms)){

											$template_taxonomies = explode(',',get_post_meta($post->ID, 'template_taxonomies', true)); ?>

											<p><input class="template_taxonomy_checkbox" type="checkbox" value="<?php echo $term->name; ?>" name="template_taxonomies[]" <?php if(in_array($term->name,$template_taxonomies)){echo 'checked="checked"';} ?> /><?php echo $term->name; ?></p>

										<?php }

									}

								echo "</div>";
							}

						}

					}

				}

				echo "</div><br style=\"clear:both; width:100%;\">";

			}

		}

		echo "<br style=\"clear:both; width:100%;\">";

	}

	function chimppress_meta_boxes_save_data( $post_id, $post ) {
		//begin taken directly from http://codex.wordpress.org/Function_Reference/add_meta_box

		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times

		if(isset($_POST['chimppress_campaign_headers_nonce'])){

			if ( !wp_verify_nonce( $_POST['chimppress_campaign_headers_nonce'], plugin_basename( __FILE__ ) ) )
				return;

			//[...]

			// OK, we're authenticated: we need to find and save the data


			$chimppress_meta_data['chimppress_type']  = (isset($_POST['chimppress_type']) ? $_POST['chimppress_type'] : null);

			$chimppress_meta_data['template_post_type']  = (isset($_POST['template_post_type']) ? $_POST['template_post_type'] : null);
			$chimppress_meta_data['template_taxonomies']  = (isset($_POST['template_taxonomies']) ? $_POST['template_taxonomies'] : null);

			$chimppress_meta_data['chimppress_from_email'] = (isset($_POST['chimppress_from_email']) ? $_POST['chimppress_from_email'] : null);
			$chimppress_meta_data['chimppress_from_name'] = (isset($_POST['chimppress_from_name']) ? $_POST['chimppress_from_name'] : null);
			$chimppress_meta_data['chimppress_to_name'] = (isset($_POST['chimppress_to_name']) ? $_POST['chimppress_to_name'] : null);
			$chimppress_meta_data['current_template_cols'] = (isset($_POST['current_template_cols']) ? $_POST['current_template_cols'] : null);
			$chimppress_meta_data['current_template_rows'] = (isset($_POST['current_template_rows']) ? $_POST['current_template_rows'] : null);

			if(isset($_POST['cell']) && is_array($_POST['cell'])){ $cell_data = http_build_query( $_POST['cell'] ); }else{ $cell_data = null; };
			if(isset($_POST['cell_color']) && is_array($_POST['cell_color'])){ $cell_bg_col = http_build_query( $_POST['cell_color'] ); }else{ $cell_bg_col = null; };
			if(isset($_POST['cell_text_color']) && is_array($_POST['cell_text_color'])){ $cell_text_col = http_build_query( $_POST['cell_text_color'] ); }else{ $cell_text_col = null; };
			if(isset($_POST['cell_padding_top']) && is_array($_POST['cell_padding_top'])){ $cell_padding_top = http_build_query( $_POST['cell_padding_top'] ); }else{ $cell_padding_top = null; };
			if(isset($_POST['cell_padding_right']) && is_array($_POST['cell_padding_right'])){ $cell_padding_right = http_build_query( $_POST['cell_padding_right'] ); }else{ $cell_padding_right = null; };
			if(isset($_POST['cell_padding_bottom']) && is_array($_POST['cell_padding_bottom'])){ $cell_padding_bottom = http_build_query( $_POST['cell_padding_bottom'] ); }else{ $cell_padding_bottom = null; };
			if(isset($_POST['cell_padding_left']) && is_array($_POST['cell_padding_left'])){ $cell_padding_left = http_build_query( $_POST['cell_padding_left'] ); }else{ $cell_padding_left = null; };
			if(isset($_POST['cell_valign']) && is_array($_POST['cell_valign'])){ $cell_valign = http_build_query( $_POST['cell_valign'] ); }else{ $cell_valign = null; };
			if(isset($_POST['cell_align']) && is_array($_POST['cell_align'])){ $cell_align = http_build_query( $_POST['cell_align'] ); }else{ $cell_align = null; };
				//	print_r($_POST); exit;
			$chimppress_meta_data['chimppress_cells'] = $cell_data;
			$chimppress_meta_data['chimppress_cell_bg_colors'] = $cell_bg_col;
			$chimppress_meta_data['chimppress_cell_text_colors'] = $cell_text_col;
			$chimppress_meta_data['chimppress_cell_padding_top'] = $cell_padding_top;
			$chimppress_meta_data['chimppress_cell_padding_right'] = $cell_padding_right;
			$chimppress_meta_data['chimppress_cell_padding_bottom'] = $cell_padding_bottom;
			$chimppress_meta_data['chimppress_cell_padding_left'] = $cell_padding_left;
			$chimppress_meta_data['chimppress_cell_valign'] = $cell_valign;
			$chimppress_meta_data['chimppress_cell_align'] = $cell_align;

			$chimppress_meta_data['chimppress_cells_array'] = (isset($_POST['cells_array']) ? $_POST['cells_array'] : null);

			$chimppress_meta_data['chimppress_campaign_body'] = (isset($_POST['chimppress_campaign_body']) ? $_POST['chimppress_campaign_body'] : null);
			$chimppress_meta_data['chimppress_campaign_txt_body'] = (isset($_POST['chimppress_campaign_txt_body']) ? $_POST['chimppress_campaign_txt_body'] : null);
			$chimppress_meta_data['chimppress_campaign_background'] = (isset($_POST['chimppress_campaign_background']) ? $_POST['chimppress_campaign_background'] : null);
			$chimppress_meta_data['chimppress_campaign_txt_background'] = (isset($_POST['chimppress_campaign_txt_background']) ? $_POST['chimppress_campaign_txt_background'] : null);

			$chimppress_meta_data['chimppress_saved_colors'] = (isset($_POST['chimppress_saved_colors']) ? $_POST['chimppress_saved_colors'] : null);

			$chimppress_meta_data['campaign_list'] = (isset($_POST['campaign_list']) ? $_POST['campaign_list'] : null);

			$chimppress_meta_data['mailchimp_id'] = (isset($_POST['mailchimp_id']) ? $_POST['mailchimp_id'] : null);

			$chimppress_meta_data['campaign_content_input'] = (isset($_POST['campaign_content_input']) ? $_POST['campaign_content_input'] : null);

			$chimppress_meta_data['chimppress_campaign_status'] = 'Edited. Not sent.';

		    foreach ($chimppress_meta_data as $key => $value) { // Cycle through the $chimppress_meta_data array!
		        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
		            update_post_meta($post->ID, $key, $value);
		        } else { // If the custom field doesn't have a value
		            add_post_meta($post->ID, $key, $value);
		        }
		        //echo $key." - " . $value."<br>";
		        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
		    }

		    $chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

		    $mailchimp_id = get_post_meta($post->ID, 'mailchimp_id', true);

	    	$type = 'regular';

	    	$opts['list_id'] = get_post_meta($post->ID, 'campaign_list', true);
			$opts['subject'] = get_the_title();
			$opts['title'] = get_the_title();
			$opts['from_email'] = get_post_meta($post->ID, 'chimppress_from_email', true);
			$opts['to_name'] = get_post_meta($post->ID, 'chimppress_to_name', true);
			$opts['from_name'] = get_post_meta($post->ID, 'chimppress_from_name', true);
			$opts['generate_text'] = true;

			$content = array(
				'html' => get_post_meta($post->ID, 'campaign_content_input', true)
			);

		    $pos = strpos($mailchimp_id, "ERROR");
		    if ($pos === false) {
		        $id_error = false;
		    } else {
		        $id_error = true;
		    }

		    if($mailchimp_id == '' || $id_error ){

		    	$new_campaign_id = $chimppress_mailchimp_api_functions->create_campaign($type, $opts, $content);

		        if(get_post_meta($post->ID, 'mailchimp_id', FALSE)) {
		            update_post_meta($post->ID, 'mailchimp_id', $new_campaign_id);
		        } else {
		            add_post_meta($post->ID, 'mailchimp_id', $new_campaign_id);
		        }

		    }else{

		    	$update = $chimppress_mailchimp_api_functions->update_campaign($mailchimp_id, $opts, $content);

		    }

		    if(isset($_POST['send_the_campaign']) && $_POST['send_the_campaign'] == 'yes'){

		    	$mailchimp_id = get_post_meta($post->ID, 'mailchimp_id', true);

		    	if($mailchimp_id != ''){

		    		$chimppress_mailchimp_api_functions->send_campaign($mailchimp_id);

		    		delete_option('campaigns');

		    		delete_option('campaigns'.$mailchimp_id);

		    	}

		    }

	    }
	/*

	    echo '<pre>';
	    print_r($_POST);
	    echo '</pre>';exit;
*/

	}//function

	function chimppress_campaign_stats_inner(){

		$chimppress_stats = new chimppress_stats;

	}

	function chimppress_campaign_history_inner(){

		$chimppress_stats = new chimppress_stats;

	}

}

?>