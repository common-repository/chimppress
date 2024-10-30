<?php

class chimppress_stats {

	var $chimppress_mailchimp_api_functions;

	var $campaign_id;

	var $campaign_ids;

	var $campaign_list;

	var $campaigns;

	function chimppress_stats(){
		$this->__construct();
	} // function

	function __construct(){

		global $post;

		$this->chimppress_mailchimp_api_functions = new chimppress_mailchimp_api_functions;

	    $this->campaigns = $this->chimppress_mailchimp_api_functions->get_campaigns();

		if(get_post_meta($post->ID, 'chimppress_type', true) == 'template'){

			$this->display_history();

		}else{

			$this->display_stats();

		}

	} // function

	function display_stats(){

		global $post;

	    $this->campaign_id = get_post_meta($post->ID, 'mailchimp_id', true);

	    $this->campaign_list = get_post_meta($post->ID, 'campaign_list', true);

		$campaign = $this->search_array($this->campaigns['data'], 'id', $this->campaign_id );

        $status = $campaign['status'];

        if(($status != 'sent' && $status != 'sending') || $this->campaign_id == ''){

        	?>

        	<script type="text/javascript">

        		jQuery(document).ready(function() {

        			jQuery('#chimppress_campaign_stats').remove();

        		});

        	</script>

        	<?php

        }else{

		?>

		<script type="text/javascript">

			jQuery(document).ready(function(){
				jQuery('#tabs div').hide();
				jQuery('#tabs div:first').show();
				jQuery('#tabs ul li:first').addClass('active');

				jQuery('#tabs ul li a').click(function(){
					jQuery('#tabs ul li').removeClass('active');
					jQuery(this).parent().addClass('active');
					var currentTab = jQuery(this).attr('href');
					jQuery('#tabs div').not('.highcharts-container').hide();
					jQuery(currentTab).show();
					return false;
				});
			});
			function changeTab(tab){
				jQuery('#tabs ul li').removeClass('active');
				jQuery('a[href="' + tab + '"]').parent().addClass('active');
				jQuery('#tabs div').not('.highcharts-container').hide();
				jQuery(tab).show();
				return false;
			}

		</script>

		<style type="text/css">

			#tabs ul{
				width:100%;
				float:left;
				clear: left;
				border-bottom: 1px solid #ccc;
				margin-bottom: 10px;
			}

			#tabs ul li{
				display: inline;
				float: left;
				margin: 2px 2px -1px 2px;
				background: #eee;
				border: 1px solid #ccc;
				padding: 10px;
				border-radius: 10px 10px 0px 0px;
				-webkit-border-radius: 10px 10px 0px 0px;
				-moz-border-radius: 10px 10px 0px 0px;
			}

			#tabs ul li.active{
				background: #fff;
			}

		</style>

		<div id="tabs">

   			<ul>
   				<li><a href="#stats-overview">Overview</a></li>
     			<li><a href="#sent-to">Sent To</a></li>
     			<li><a href="#opened">Opened</a></li>
     			<li><a href="#not-opened">Not Opened</a></li>
     			<li><a href="#urls-clicked">URL's Clicked</a></li>
     			<li><a href="#bounced">Bounces</a></li>
   			</ul>

   			<div id="stats-overview">
				<?php $this->overview(); ?>
   			</div>

   			<div id="sent-to">
				<?php $this->sent_to(); ?>
   			</div>

   			<div id="opened">
				<?php $this->opened(); ?>
   			</div>

   			<div id="not-opened">
				<?php $this->not_opened(); ?>
   			</div>

      		<div id="urls-clicked">
				<?php $this->urls_clicked(); ?>
   			</div>

      		<div id="bounced">
				<?php $this->bounced(); ?>
   			</div>

		</div>

		<?php

        }

	} // function

	function display_history(){

		global $post;

	    $this->campaign_ids = get_post_meta($post->ID, 'history', true);

	    $this->campaign_list = get_post_meta($post->ID, 'campaign_list', true);

	    if(is_array($this->campaign_ids)){

		    foreach($this->campaign_ids as $campaign_id){

		    	$this->campaign_id = $campaign_id;

				$filters['campaign_id'] = $this->campaign_id;

				$campaign = $this->search_array($this->campaigns['data'], 'id', $this->campaign_id );

		        $status = $campaign['status'];

		        if($status != 'sent'){
					return;
				}

		    	?><div class="postbox history_result">

		    		<div class="handlediv" title="Click to toggle"><br></div>

		    		<h3 class="hndle"><span><?php echo $campaign['title'] . ' Sent: ' . $campaign['send_time']; ?></span></h3>

		    		<div class="inside">

						<style type="text/css">

							#tabs<?php  echo $this->campaign_id; ?> ul{
								width:100%;
								float:left;
								clear: left;
								border-bottom: 1px solid #ccc;
								margin-bottom: 10px;
							}

							#tabs<?php  echo $this->campaign_id; ?> ul li{
								display: inline;
								float: left;
								margin: 2px 2px -1px 2px;
								background: #eee;
								border: 1px solid #ccc;
								padding: 10px;
								border-radius: 10px 10px 0px 0px;
								-webkit-border-radius: 10px 10px 0px 0px;
								-moz-border-radius: 10px 10px 0px 0px;
							}

							#tabs<?php  echo $this->campaign_id; ?> ul li.active{
								background: #fff;
							}

						</style>

						<script type="text/javascript">

							jQuery(document).ready(function(){
								jQuery('#tabs<?php  echo $this->campaign_id; ?> div').hide();
								jQuery('#tabs<?php  echo $this->campaign_id; ?> div:first').show();
								jQuery('#tabs<?php  echo $this->campaign_id; ?> ul li:first').addClass('active');

								jQuery('#tabs<?php  echo $this->campaign_id; ?> ul li a').click(function(){
									jQuery('#tabs<?php  echo $this->campaign_id; ?> ul li').removeClass('active');
									jQuery(this).parent().addClass('active');
									var currentTab = jQuery(this).attr('href');
									jQuery('#tabs<?php  echo $this->campaign_id; ?> div').not('.highcharts-container').hide();
									jQuery(currentTab).show();
									return false;
								});
							});
							function changeTab(tab){
								jQuery('#tabs<?php  echo $this->campaign_id; ?> ul li').removeClass('active');
								jQuery('a[href="' + tab + '"]').parent().addClass('active');
								jQuery('#tabs<?php  echo $this->campaign_id; ?> div').not('.highcharts-container').hide();
								jQuery(tab).show();
								return false;
							}

						</script>

						<div id="tabs<?php  echo $this->campaign_id; ?>">

				   			<ul>
				   				<li><a href="#stats-overview<?php  echo $this->campaign_id; ?>">Overview</a></li>
				     			<li><a href="#sent-to<?php  echo $this->campaign_id; ?>">Sent To</a></li>
				     			<li><a href="#opened<?php  echo $this->campaign_id; ?>">Opened</a></li>
				     			<li><a href="#not-opened<?php  echo $this->campaign_id; ?>">Not Opened</a></li>
				     			<li><a href="#urls-clicked<?php  echo $this->campaign_id; ?>">URL's Clicked</a></li>
				     			<li><a href="#bounced<?php  echo $this->campaign_id; ?>">Bounces</a></li>
				   			</ul>

				   			<div id="stats-overview<?php  echo $this->campaign_id; ?>">
								<?php $this->overview(); ?>
				   			</div>

				   			<div id="sent-to<?php  echo $this->campaign_id; ?>">
								<?php $this->sent_to(); ?>
				   			</div>

				   			<div id="opened<?php  echo $this->campaign_id; ?>">
								<?php $this->opened(); ?>
				   			</div>

				   			<div id="not-opened<?php  echo $this->campaign_id; ?>">
								<?php $this->not_opened(); ?>
				   			</div>

				      		<div id="urls-clicked<?php  echo $this->campaign_id; ?>">
								<?php $this->urls_clicked(); ?>
				   			</div>

				      		<div id="bounced<?php  echo $this->campaign_id; ?>">
								<?php $this->bounced(); ?>
				   			</div>

						</div>

					</div>

				</div>

			<?php }

		}

		?>
		<script type="text/javascript">

			jQuery(document).ready(function(){

				jQuery('.history_result').addClass('closed');

			});

		</script>

		<?php

	} // function

	function overview(){

		?>

		<style type="text/css">

			#total_emails_sent, #total_bounced, #hard_bounced, #soft_bounced{
				width: 20%;
				float: left;
				border: 1px solid #333;
				border-radius: 10px;
				padding: 10px;
				text-align: center;
				background: #DDD;
				color: #333;
				margin-right: 10px;
				margin-bottom: 10px
			}
			#total_emails_sent p.total{
				font-size: 16px;
				font-weight: bold;
			}
			#total_emails_sent span, #total_bounced span, #hard_bounced span, #soft_bounced span{
				font-size: 30px;
			}
			.pointer{
				cursor: pointer;
			}

			.campaign_advice{
				border: 1px solid #fff;
				border-radius: 10px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				padding: 10px;
				float: left;
				width: 70%;
				margin-bottom: 10px;
				font-size: 16px;
			}

		</style>

		<?php

		$campaignStats = $this->chimppress_mailchimp_api_functions->campaignStats($this->campaign_id);

		$campaign = $this->search_array($this->campaigns['data'], 'id', $this->campaign_id );

		echo 	'<span id="total_emails_sent">
					<p class="total">Total Sent</p>
						<a class="pointer" onClick="changeTab(\'#sent-to\');">
							<span>' . $campaignStats['emails_sent'] . '</span>
						</a>
					<p>
						<a class="pointer" onClick="changeTab(\'#opened\');">
							' . $campaignStats['opens'] . ' opens.
						</a>
						<br>(The last one was opened at ' .$campaignStats['last_open']. '
					</p>
					<p>
						' . $campaignStats['forwards'] . ' forwards.
					</p>
					<p>
						<a class="pointer" onClick="changeTab(\'#urls-clicked\');">
							' . $campaignStats['unique_clicks'] . ' clicks.
						</a>
						<br>(' . $campaignStats['clicks'] . ' unique)
					</p>
					<p>
						<a class="pointer" onClick="changeTab(\'#bounced\');">
						' . $campaignStats['hard_bounces'] . ' hard bounces.
						</a>
					</p>
					<p>
						<a class="pointer" onClick="changeTab(\'#bounced\');">
							' . $campaignStats['soft_bounces'] . ' soft bounces.
						</a>
					</p>
					<p>
						' . $campaignStats['unsubscribes'] . ' unsubscribes.
					</p>
					<p>
						<a href="' . $campaign['archive_url'] . '" target="_blank">View Archive</a>.
					</p>
				</span>';

				echo '<br>' .  $this->campaign_advice();

		?>

		<div id="visualization<?php  echo $this->campaign_id; ?>" style="width: 600px; height: 400px;float:left;"></div>
<?php// print_r($campaignStats['timeseries']); ?>
		<script>

		jQuery(function () {

		     var d =[<?php
		  			$i = 0;
		  			foreach($campaignStats['timeseries'] as $stat ){
		  				$timestamp = explode(' ', $stat['timestamp']);
		  				echo '['.strtotime($stat['timestamp']) * 1000 . ', ' . $stat['unique_opens'].'],';
		  				$i++;
		  			} ?>]

	/*
	    jQuery.plot(jQuery("#visualization<?php  echo $this->campaign_id; ?>"), [
		        {
		            data: d5,
		            lines: { show: true },
		            points: { show: true },
		            xaxis: {
			        	mode: "time",
			            //timeformat: "%Y-%m-%d"
			       }
		        }
*/

		        jQuery.plot(jQuery("#visualization<?php  echo $this->campaign_id; ?>"), [d], { xaxis: { mode: "time" } });


		    jQuery("#visualization<?php  echo $this->campaign_id; ?>").show();

		});

/*
			var chart1; // globally available
			jQuery(document).ready(function() {
				chart = new Highcharts.Chart({
		            chart: {
		                renderTo: 'visualization<?php  echo $this->campaign_id; ?>',
		                type: 'line',
		                marginRight: 130,
		                marginBottom: 75,
		                backgroundColor: 'whiteSmoke',
		            },
		            title: {
		                text: 'First 24 hours of campaign',
		                x: -20 //center
		            },
		            xAxis: {
		                categories: [<?php
						  			$i = 0;
						  			foreach($campaignStats['timeseries'] as $stat ){
						  				$timestamp = explode(' ', $stat['timestamp']);
						  				echo "'" . $timestamp[1] . "',";
						  				$i++;
						  			} ?>],
						labels: {
                			rotation: 90
            			}
		            },
		            yAxis: {
		                title: {
		                    text: 'Opens'
		                },
		                plotLines: [{
		                    value: 0,
		                    width: 1,
		                    color: '#808080'
		                }]
		            },
		            tooltip: {
		                formatter: function() {
		                        return '<b>'+ this.series.name +'</b><br/>'+
		                        this.x +': '+ this.y;
		                }
		            },
		            legend: {
		                layout: 'vertical',
		                align: 'right',
		                verticalAlign: 'top',
		                x: -10,
		                y: 100,
		                borderWidth: 0
		            },
		            series: [{
		                name: 'Emails opened',
		                data: 	[<?php
					  			$i = 0;
					  			foreach($campaignStats['timeseries'] as $stat ){
					  				echo $stat['unique_opens'] . ', ';
					  				$i++;
					  			} ?>]
		            }]
		        });

	    	});
*/

		</script>

    	<br><br>

		<?php

		echo '<br style="clear:both;">';

	}

	function sent_to(){

		$sent_to = $this->chimppress_mailchimp_api_functions->campaignMembers($this->campaign_id);

		echo 'Sent to ' . $sent_to['total'] . ' subscribers.<br>';

		$chimppress_subscribers_page = new chimppress_subscribers_page;

		$chimppress_subscribers_page->generate_table($sent_to, $this->campaign_list, '', '', false, false, false, true);

	} // function

	function opened(){

		$opened = $this->chimppress_mailchimp_api_functions->campaignOpenedAIM($this->campaign_id);

		echo 'Opened by ' . $opened['total'] . ' subscribers.<br>';

		$chimppress_subscribers_page = new chimppress_subscribers_page;

		$chimppress_subscribers_page->generate_table($opened, $this->campaign_list, '', '', false, false, false, false, true);

		$opened_map = $this->chimppress_mailchimp_api_functions->campaignGeoOpens($this->campaign_id);

		//print_r($opened_map);

		?>

  		<!--
<script type='text/javascript'>

			google.load('visualization', '1', {'packages': ['geochart']});
   			google.setOnLoadCallback(drawRegionsMap);

    		function drawRegionsMap() {
      			var data = new google.visualization.DataTable();
      			data.addRows(<?php echo count($opened_map); ?>);
      			data.addColumn('string', 'Country');
      			data.addColumn('string', 'Opened');
      			<?php $i = 0;
      			foreach($opened_map as $map){
      			if($map['code'] == 'UK'){
      				$code = 'GB';
      			}else{
      				$code = $map['code'];
      			}
      			$details = $code . ': ' . $map['opens'] . ' opens. ';
      			if($map['region_detail'] == 1 ){
      				$extra_info = $this->chimppress_mailchimp_api_functions->campaignGeoOpensForCountry($this->campaign_id, $map['code']);
      				foreach($extra_info as $info){
      					$details .= $info['name'] . ' - ' . $info['opens'] . '. ';
      				}
      			}
      			?>
      				data.setValue(<?php echo $i; ?>, 0, '<?php echo $code; ?>');
      				data.setValue(0, 1, '<?php echo $details; ?>');
      				<?php $i++;
      			} ?>

      			var container = document.getElementById('map_canvas<?php  echo $this->campaign_id; ?>');
      			var geochart = new google.visualization.GeoChart(container);
      			geochart.draw(data, {title:"",
        		    			width:600,height:400,backgroundColor:'whiteSmoke',colors:['#c1ff8a','#fdff84'],fontName:'sans-serif',fontSize:11,
        					    hAxis: {title: ""}} );
  			};


  		</script>
-->

  		<span style="margin: 0 auto; width: 600px; display: block;" id="map_canvas<?php  echo $this->campaign_id; ?>"></span>

		<?php

	} // function

	function not_opened(){

		$not_opened = $this->chimppress_mailchimp_api_functions->campaignNotOpenedAIM($this->campaign_id);

		echo 'Not opened by ' . $not_opened['total'] . ' subscribers.<br>';

		$chimppress_subscribers_page = new chimppress_subscribers_page;

		//print_r($not_opened);

		$chimppress_subscribers_page->generate_table($not_opened, $this->campaign_list, '', '', false, false, false, false, false);

	} // function

	function urls_clicked(){

		$urls_clicked = $this->chimppress_mailchimp_api_functions->campaignClickStats($this->campaign_id);

		?>

		<table cellspacing="0" class="widefat post fixed">
			<thead>
				<tr>
					<th style="" class="manage-column" id="" scope="col">URL</th>
					<th style="" class="manage-column" id="" scope="col">Clicks</th>
					<th style="" class="manage-column" id="" scope="col">Unique</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th style="" class="manage-column" id="" scope="col">URL</th>
					<th style="" class="manage-column" id="" scope="col">Clicks</th>
					<th style="" class="manage-column" id="" scope="col">Unique</th>
				</tr>
			</tfoot>

		<?php

		foreach($urls_clicked as $key => $val){

			echo '<tr style="line-height:25px;">';

				echo '<td>' . $key . '</td><td>' . $val['clicks'] . '</td><td>' . $val['unique'] . '</td>';

			echo '</tr>';

		}

		echo '</table>';

	} // function

	function bounced(){

		$bounced = $this->chimppress_mailchimp_api_functions->campaignBounceMessages($this->campaign_id);

		$campaignStats = $this->chimppress_mailchimp_api_functions->campaignStats($this->campaign_id);

		echo '<span id="total_bounced"><span>' . $bounced['total'] . '</span><br>Total Bounces</span>';

		echo '<span id="hard_bounced"><span>' . $campaignStats['hard_bounces'] . '</span><br>Hard Bounces <a href="http://kb.mailchimp.com/article/whats-the-difference-between-hard-and-soft-bounce-backs" target="_blank">(?)</a></span>';

		echo '<span id="soft_bounced"><span>' . $campaignStats['soft_bounces'] . '</span><br>Soft Bounces <a href="http://kb.mailchimp.com/article/whats-the-difference-between-hard-and-soft-bounce-backs" target="_blank">(?)</a></span>';

		?>

		<table cellspacing="0" class="widefat post fixed">
			<thead>
				<tr>
					<th style="" class="manage-column" id="" scope="col">Date</th>
					<th style="" class="manage-column" id="" scope="col">Email</th>
					<th style="" class="manage-column" id="" scope="col">Message</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th style="" class="manage-column" id="" scope="col">Date</th>
					<th style="" class="manage-column" id="" scope="col">Email</th>
					<th style="" class="manage-column" id="" scope="col">Message</th>
				</tr>
			</tfoot>

		<?php

		foreach($bounced['data'] as $bounce){

			echo '<tr style="line-height:25px;">';

				echo '<td>' . $bounce['date'] . '</td><td>' . $bounce['email'] . '</td><td>' . $bounce['message'] . '</td>';

			echo '</tr>';

		}

		echo '</table>';

	} // function

	function campaign_advice(){

		$advice = $this->chimppress_mailchimp_api_functions->campaignAdvice($this->campaign_id);
		//print_r($advice);
		$return = '<span class="campaign_advice">';

		if(is_array($advice)){

			if($advice[0]['type'] == 'negative'){
				$return .= '<img src="' . CP_url . '/img/orange_warning.gif" height="13" width="13" />';
			}elseif($advice[0]['type'] == 'positive'){
				$return .= '<img src="' . CP_url . '/img/green_tick.png" height="13" width="13" />';
			}elseif($advice[0]['type'] == 'neutral'){
				$return .= '<img src="' . CP_url . '/img/blue_circles.png" height="13" width="13" />';
			}

			$return .= $advice[0]['msg'];

		}else{

			$return .= $advice;

		}

		$return .= '</span>';

		return $return;

	} // function

	function search_array($array, $key, $value) {
	  $return = array();
	  foreach ($array as $k=>$subarray){
	    if (isset($subarray[$key]) && $subarray[$key] == $value) {
	      $return = $subarray;
	      return $return;
	    }
	  }
	} // function

}

?>