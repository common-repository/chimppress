<?php

include_once('../../../../wp-load.php');

include_once('../chimppress.php');

header( "Content-type: application/x-javascript" );

global $post;

$cells = explode(',', get_post_meta($post->ID, 'chimppress_cells', true));

echo '<pre>';
print_r($cells);
echo '</pre>';

$rows = get_post_meta($post->ID, 'current_template_rows', true);

$cols = get_post_meta($post->ID, 'current_template_cols', true);

$current_row = 1;

$current_col = 1;

?>

jQuery(document).ready(function() {<?php

foreach($cells as $cell){
	?>
		jQuery('#chimppresscampaigntemplate #<?php echo $current_row ?> #<?php echo $current_col; ?>').html('<?php echo $cell; ?>');
	<?php
	if($current_col < $cols){
		$current_col++;
	}else{
		$current_col = 1;
	}
	if ($current_col == 1){
		$current_row++;
	}			
}?>
	
});