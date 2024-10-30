<?php

new chimppress_setup;

class chimppress_setup {

	private $campaigns;

	function chimppress_setup(){
		$this->__construct();
	} // function

	function __construct(){

		new chimppress_meta_boxes; //set up meta boxes for custom post type page

		//add actions
		add_action( 'init', array( &$this, 'chimppress_register' ) );
		add_action('admin_menu',array(&$this,'add_menus'));
		add_action( 'manage_posts_custom_column', array( &$this, 'chimppress_columns' ) );
		add_action('admin_print_scripts', array( &$this, 'chimppress_enqueue_scripts' ) );
		add_action("admin_print_styles", array( &$this, 'chimppress_enqueue_styles' ));
		//add filters
		add_filter('manage_edit-chimppress_columns', array( &$this, 'chimppress_columns_content' ) );

		$chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

		$this->campaigns = $chimppress_mailchimp_api_functions->get_campaigns();

		//echo '<pre>';
		//print_r($this->campaigns);
		//echo '</pre>';

	} // function

	function chimppress_register() {

		$labels = array(
			'name' => _x('ChimpPress', 'post type general name'),
			'singular_name' => _x('Campaign', 'post type singular name'),
			'add_new' => _x('Add New Campaign', 'Campaign'),
			'add_new_item' => __('Add New Campaign'),
			'edit_item' => __('Edit Campaign'),
			'new_item' => __('New Campaign'),
			'view_item' => __('View online version'),
			'search_items' => __('Search Campaigns'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_in_menu ' => true,
			'menu_position' => 25,
			'exclude_from_search' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => WP_PLUGIN_URL . '/chimppress/img/chimppress_logo_16.png',
			'map_meta_cap' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'can_export' => true,
			'show_in_nav_menus' => false,
			'supports' => array('title')
		  );

		register_post_type( 'chimppress' , $args );
		flush_rewrite_rules();
	} //function

	function chimppress_columns_content($columns){ //displays the columns headings
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Campaign Name",
			"type" => "Type",
			"status" => "Status",
			"sendtime" => "Send Time",
			"emailssent" => "Emails Sent"
		);
		return $columns;
	} //function

	function chimppress_columns($column){ //content of columns

	    global $post;

	    $type = get_post_meta($post->ID, 'chimppress_type', true);

	    if($type == 'template'){

	    	switch ($column) {
				case 'type':
					echo 'Template';
					break;
		        case 'status':
		            echo 'Used for ' . str_replace(',', '\'s, ', get_post_meta($post->ID, 'template_post_type', true) ) . '\'s<br>Taxonomies: ' . str_replace(',', ', ', get_post_meta($post->ID, 'template_taxonomies', true) );
		       		break;
		       	case 'sendtime':
		       		echo 'N/A';
		       		break;
		       	case 'emailssent':
		       		$count = count(get_post_meta($post->ID, 'history', true));
		       		echo $count . ' campaign(s) sent';
		       		break;
		    }

	    }else{

		    $mailchimp_id = get_post_meta($post->ID, 'mailchimp_id', true);

		   	$pos = strpos($mailchimp_id, "ERROR");

			if ($pos === false && $mailchimp_id != '') {

			    $campaign = $this->search_array($this->campaigns['data'], 'id', $mailchimp_id );

		        $status = $campaign['status'];

		        $sendtime = $campaign['send_time'];

		        $emailssent = $campaign['emails_sent'];

				switch ($column) {
					case 'type':
						echo 'Standard';
						break;
			        case 'status':
			            echo $status;
			       		break;
			       	case 'sendtime':
			       		echo $sendtime;
			       		break;
			       	case 'emailssent':
			       		echo $emailssent;
			       		break;
			    }

			}

	    }

	} //function

	function chimppress_enqueue_scripts() {
		global $post_type, $pagenow;
		if((isset($_GET['post_type']) && $_GET['post_type'] == 'chimppress') || ($post_type == 'chimppress')){

			if($pagenow == 'post.php' || $pagenow == 'post-new.php'){

		    	//jquery
		        wp_enqueue_script( 'jquery' );
		        //jquery ui
		        wp_enqueue_script( 'jquery-ui-core' );
		        //jquery ui sortable
		        wp_enqueue_script( 'jquery-ui-sortable' );
		        //jquery ui draggable
		        wp_enqueue_script( 'jquery-ui-draggable' );
		        //jquery ui droppable
		        wp_enqueue_script( 'jquery-ui-droppable' );
		        //jquery ui resizable
		        wp_enqueue_script( 'jquery-ui-resizable');
		        //media upload
		        wp_enqueue_script('media-upload');
		        //jquery ui thickbox
		        wp_enqueue_script( 'thickbox' );
		        // scripts for meta boxes
				wp_enqueue_script('common');
				wp_enqueue_script('wp-lists');
				wp_enqueue_script('postbox');
		        //farbtastic
		        wp_enqueue_script('farbtastic');
		        //all other scripts not included with WP
		        wp_enqueue_script('scripts', CP_url.'/js/scripts.js');
		        //flot lirary
		        wp_enqueue_script('flot', CP_url.'/js/flot/jquery.flot.js');
		        // chimppress js
		        wp_register_script( 'chimppress-editor', CP_url . '/js/editor.js');
		        wp_enqueue_script( 'chimppress-editor' );
		        $stuff = array( 'CP_url' => CP_url );
				wp_localize_script( 'chimppress-editor', 'stuff', $stuff );

			}

        }

	} //function

	function chimppress_enqueue_styles(){
		global $wp_styles, $post_type, $pagenow;
		if((isset($_GET['post_type']) && $_GET['post_type'] == 'chimppress') || ($post_type == 'chimppress')){

			wp_enqueue_style('chimppress_editor', CP_url.'/css/editor.css');
			//farbtastic
			wp_enqueue_style('farbtastic', CP_url.'/css/farbtastic.css');
			//thickbox
			wp_enqueue_style('thickbox');
			//slider
			wp_enqueue_style('jslider-style', CP_url.'/js/slider-styles/jslider.css');
			wp_enqueue_style('jslider-style-round', CP_url.'/js/slider-styles/jslider.round.plastic.css');

			wp_register_style('jslider-style-plastic-ie6', CP_url.'/js/slider-styles/jslider.round.plastic.ie6.css');
			wp_register_style('jslider-style-ie6', CP_url.'/js/slider-styles/jslider.round.plastic.ie6.css');
			$wp_styles->add_data('jslider-style-plastic-ie6', 'conditional', 'IE');
			$wp_styles->add_data('jslider-style-ie6', 'conditional', 'IE');

			//jquery ui
			wp_enqueue_style('jquery-style', CP_url.'/js/smoothness/jquery-ui-1.8.20.custom.css');

		}

	} //function

	function add_menus(){
		add_submenu_page( 'edit.php?post_type=chimppress', 'Subscribers', 'Subscribers', 'manage_options', 'chimppress-subscribers', array(&$this, 'subscribers_page'));
		add_submenu_page( 'edit.php?post_type=chimppress', 'ChimpPress Settings', 'Settings', 'manage_options', 'chimppress-settings', array(&$this, 'settings_page'));
		add_submenu_page( 'edit.php?post_type=chimppress', 'Feedback', 'Feedback/About', 'manage_options', 'chimppress-feedback', array(&$this, 'feedback_page'));
	} //function

	function settings_page(){
		$chimppress_settings_page = new chimppress_settings_page;
		$chimppress_settings_page->settings_page();
	} //function

	function subscribers_page(){
		$chimppress_subscribers_page = new chimppress_subscribers_page;
		$chimppress_subscribers_page->subscribers_page();
	} //function

	function feedback_page(){
		$chimppress_feedback_page = new chimppress_feedback_page;
		$chimppress_feedback_page->feedback_page();
	} //function

	function search_array($array, $key, $value) {
	  $return = array();
	  foreach ($array as $k=>$subarray){
	    if (isset($subarray[$key]) && $subarray[$key] == $value) {
	      $return = $subarray;
	      return $return;
	    }
	  }
	} // function

} // class

?>