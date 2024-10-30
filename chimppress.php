<?php

/*
Plugin Name: ChimpPress
Description: Manage Mailchimp campaigns and design bespoke HTML layouts using an intuitive drag and drop editor.
Author: Fish Can't Whistle
Author URI: http://fishcantwhistle.com
Version: 0.8.9
*/

if(!defined("CP_url")){define("CP_url", WP_PLUGIN_URL."/chimppress");}

include_once('includes/class-MCAPI.php'); //MailChimp API

include_once('includes/class-mailchimp-api-functions.php'); //MailChimp API Functions

include_once('includes/class-chimppress-meta-boxes.php'); //Meta boxes

include_once('includes/class-chimppress-setup.php'); //Set up

include_once('includes/class-campaign-editor.php'); //Editor

include_once('includes/class-chimppress-widgets.php'); //Widgets

include_once('includes/class-chimppress-stats.php'); //Stats

include_once('includes/class-chimppress-templates.php'); //Templates

include_once('includes/class-chimppress-settings-page.php'); //Settings Page

include_once('includes/class-chimppress-subscribers-page.php'); //Subscribers Page

include_once('includes/class-chimppress-feedback-page.php'); //Feedback Page

include_once('includes/class-media-upload-edit.php'); //Media Upload editing

include_once('includes/class-chimppress-display.php'); //Browser rendering of campaign

include_once('includes/class-auto-send.php'); //Auto Send

include_once('includes/class-ajax.php'); //Ajax Functions

//NAG

/* Display a notice that can be dismissed */
add_action('admin_notices', 'cp_admin_notice');
function cp_admin_notice() {
    global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
    if ( ! get_user_meta($user_id, 'example_ignore_notice') ) {
        echo '<div class="updated"><p>';
        printf(__('<a href="%1$s">I have subscribed</a>'), '?example_nag_ignore=0');
        ?>
        <!-- Begin MailChimp Signup Form -->
		<link href="http://cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
		<style type="text/css">
			#mc_embed_signup{background:none; clear:left; font:14px Helvetica,Arial,sans-serif; }
			#mc_embed_signup .button{color: #333;};
			/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
			   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
		</style>
		<div id="mc_embed_signup">
		<form action="http://jealousdesigns.us2.list-manage.com/subscribe/post?u=a4a9840b607ebf25275bc7a46&amp;id=8ea7d99292" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
			<label for="mce-EMAIL">Sign up to our WordPress mailing list to receive news and updates about Fish Can't Whistle plugins</label>
			<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
			<input type="hidden" value="ChimpPress" name="MMERGE3">
			<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
		</form>
		</div>

		<!--End mc_embed_signup-->
        <?php
        echo "</p></div>";
    }
}
add_action('admin_init', 'cp_nag_ignore');
function cp_nag_ignore() {
    global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['example_nag_ignore']) && '0' == $_GET['example_nag_ignore'] ) {
             add_user_meta($user_id, 'example_ignore_notice', 'true', true);
    }
}

?>
