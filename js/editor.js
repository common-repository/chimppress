jQuery(document).ready(function() {

	jQuery('.icon32.icon32-posts-chimppress').css('background-image', stuff.CP_url+'/img/chimppress_logo.jpg');

	jQuery('.item').each(function (i) {
		var id = jQuery(this).attr('id');
		jQuery(this).attr('id', 'chimpresseditor_'+id)
	});
	jQuery('.widgets_meta_box .item').each(function (i) {
		var id = jQuery(this).attr('id');
		jQuery(this).attr('id', 'chimpresseditor_'+id)
	});

	jQuery("#chimppress_campaign_widgets .edit_image").html('<!--remove-->Image<br><input id="upload_image_button" class="button image_button" type="button" value="Upload New Image" /><!--remove-->');

	if(jQuery('#current_template_cols').val() != '' && jQuery('#current_template_rows').val() != ''){
		createDynamicTable(jQuery('#chimppresscampaigntemplate'), jQuery('#current_template_rows').val(), jQuery('#current_template_cols').val(), 1, '', '', jQuery('#cells_array').val());
		if(window.update_cells){ update_cells(); };
	}

	jQuery('img.inserted_image').resizable({
		stop: function(event, ui) {
			jQuery('img.inserted_image').each(function(){
				jQuery(this).attr('width', jQuery(this).css('width').replace('px', ''));
				jQuery(this).attr('height', jQuery(this).css('height').replace('px', ''));
			});
		}
	});

	jQuery('.ui-wrapper').css('position', 'relative');

	jQuery('#colorpicker_background').farbtastic(function(color){
		var cellarray = jQuery('#chimppress_color_cell').html().split('-');
		jQuery('#colorpicker_background_input').css('backgroundColor', color).val(color);
		clicked.css('background', color);
		get_widgets();

	});

	jQuery('#colorpicker_text').farbtastic(function(color){
		var cellarray = jQuery('#chimppress_color_cell').html().split('-');
		jQuery('#colorpicker_text_input').css('backgroundColor', color).val(color);
		clicked.css('color', color);
		get_widgets();
	});

	jQuery('#chimppress_valign').change(function(){
		clicked.attr('valign', jQuery(this).val());
		get_widgets();
	});

	jQuery('#chimppress_align').change(function(){
		clicked.attr('align', jQuery(this).val());
		get_widgets();
	});

	jQuery('#select_campaign_area_to_edit').change(function(){
		clicked = jQuery(jQuery(this).val());
		jQuery('#chimppress_color_cell').html(jQuery(this).find('option:selected').text());
	});

	jQuery('.chimppress_type').click(function(){
		make_the_changes();
	});

	jQuery('.show_hide_tax').click(function(){
		jQuery(this).next().slideToggle();
	})

	jQuery('.template_taxonomy_checkbox').click(function(){
		checkit = false;
		jQuery(this).parent().parent().find('.template_taxonomy_checkbox').each(function(){
			if(jQuery(this).attr('checked')){
				checkit = true;
			}
		});
		if(checkit){
			jQuery(this).parent().parent().parent().find('.template_post_type_checkbox').attr('checked', 'checked');
		}else{
			//jQuery(this).parent().parent().parent().find('.template_post_type_checkbox').removeAttr('checked');
		}
	});

	jQuery('.template_post_type_checkbox').click(function(){
		if(!jQuery(this).attr('checked')){
			jQuery(this).parent().find('.template_taxonomy_checkbox').removeAttr('checked');
		}
	});

	jQuery('#export').click(function(){
		export_template();
	});

	jQuery('#import').click(function(){
		import_template();
	});

	jQuery('#import_existing').change(function(){
		var data = {
			action: 'get_campaign_html',
			post_id: jQuery(this).val()
		};

		jQuery.post(ajaxurl, data, function(response) {
			if(response != ''){
				jQuery('#import_textarea').val(response);
				jQuery('#preview_import div').html(response);
				jQuery('#preview_import').slideDown();
			}else{
				alert('The selected campaign has no content');
			}
		});
	});

	jQuery('#close_import_preview').click(function(){
		jQuery('#preview_import').slideUp();
	});

	add_td_click();

	load_draggable();

	add_edit_content_buttons();

	add_controls();

	generate_saved_colors();

	removeLoading();

});

var clicked;

var selected_text_color;

var selected_background_color;

function load_draggable(){

	var i = new Date().getTime();

	jQuery(".sortable").sortable({
	  handle : '.sort',
	  placeholder: 'placeholder',
	  connectWith: '.sortable_area',
/* 	  tolerance: 'pointer', */
	  stop: function(event, ui) {
		if (ui.item.parent().hasClass('widgets_meta_box')){
			ui.item.remove();
		}

		if (ui.item.parent().hasClass('campaign_editor')){
			var id = ui.item.attr('id');
			var itemclass = ui.item.attr('class');
			if(!ui.item.hasClass('current')){
				ui.item.attr('class', itemclass+' current ');
				ui.item.attr('id', id+i);
				var id = ui.item.children('.edit').attr('id');
				ui.item.children('.edit').attr('id', id+i);
				ui.item.children('.edit_button').attr('onClick', 'editsave(\''+id+i+'\')');
				var id = ui.item.children('.edit_image').attr('id');
				ui.item.children('.edit_image').attr('id', id+i);
				ui.item.find('.image_button').attr('onClick', 'upload_image_cp(\''+id+i+'\')');
				i++;
			}
		}

		get_widgets();

	  }
	});

	jQuery(".item").draggable({
	  connectToSortable: '.sortable',
	  handle: '.copy',
	  helper: 'clone',
	  placeholder: 'placeholder',
	  zIndex: 999,
	});

	jQuery(".item .remove").live('click', function() {
		jQuery(this).closest('.item').remove();
		get_widgets();
	});
}

function get_widgets(cells_array){
	var cells = new Array();
	var rowid = '';
	var cellid ='';
	var cellcontent = '';
	jQuery('#chimppress_meta_data').html('');

	var body_bg_color = jQuery('#chimppresscampaigntemplate').css('backgroundColor');
	var body_text_color = jQuery('#chimppresscampaigntemplate').css('color');
	var campaign_bg_color = jQuery('#campaign_background').css('backgroundColor');
	var campaign_text_color = jQuery('#campaign_background').css('color');

	jQuery('<input type="hidden" id="chimppress_campaign_body" name="chimppress_campaign_body" value="" />').appendTo('#chimppress_meta_data');
	jQuery('#chimppress_campaign_body').val(body_bg_color);

	jQuery('<input type="hidden" id="chimppress_campaign_txt_body" name="chimppress_campaign_txt_body" value="" />').appendTo('#chimppress_meta_data');
	jQuery('#chimppress_campaign_txt_body').val(body_text_color);

	jQuery('<input type="hidden" id="chimppress_campaign_background" name="chimppress_campaign_background" value="" />').appendTo('#chimppress_meta_data');
	jQuery('#chimppress_campaign_background').val(campaign_bg_color);

	jQuery('<input type="hidden" id="chimppress_campaign_txt_background" name="chimppress_campaign_txt_background" value="" />').appendTo('#chimppress_meta_data');
	jQuery('#chimppress_campaign_txt_background').val(campaign_text_color);

	var cols = 0;
	jQuery('#chimppresscampaigntemplate').find('tr:first td').each(function () {
		var cspan = jQuery(this).attr('colspan');
        if (!cspan) cspan = 1;
        cols += parseInt(cspan, 10);
    });

	var rows = jQuery('#chimppresscampaigntemplate tr').length;

    jQuery('#current_template_cols').val(cols);
	jQuery('#current_template_rows').val(rows);

    jQuery('#chimppresscampaigntemplate td').each(function() {

    	var rowid = jQuery(this).parent().attr('id');
		var cellid = jQuery(this).attr('id');
		var bg_color = jQuery(this).css('backgroundColor');
		var text_color = jQuery(this).css('color');
		var padding_top = jQuery(this).css('paddingTop');
		var padding_right = jQuery(this).css('paddingRight');
		var padding_bottom = jQuery(this).css('paddingBottom');
		var padding_left = jQuery(this).css('paddingLeft');
		var valign = jQuery(this).attr('valign');
		var align = jQuery(this).attr('align');

		cells[rowid+cellid] = jQuery(this).attr('colspan');

		jQuery('#' + rowid+'-'+cellid).val('');
		jQuery('<input type="hidden" id="'+rowid+'-'+cellid+'" name="cell['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');

		jQuery('<input type="hidden" id="color_'+rowid+'-'+cellid+'" name="cell_color['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#color_' + rowid + '-' + cellid).val(bg_color);

		jQuery('<input type="hidden" id="text_color_'+rowid+'-'+cellid+'" name="cell_text_color['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#text_color_' + rowid + '-' + cellid).val(text_color);

		jQuery('<input type="hidden" id="padding_top_'+rowid+'-'+cellid+'" name="cell_padding_top['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#padding_top_' + rowid + '-' + cellid).val(padding_top);

		jQuery('<input type="hidden" id="padding_right_'+rowid+'-'+cellid+'" name="cell_padding_right['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#padding_right_' + rowid + '-' + cellid).val(padding_right);

		jQuery('<input type="hidden" id="padding_bottom_'+rowid+'-'+cellid+'" name="cell_padding_bottom['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#padding_bottom_' + rowid + '-' + cellid).val(padding_bottom);

		jQuery('<input type="hidden" id="padding_left_'+rowid+'-'+cellid+'" name="cell_padding_left['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#padding_left_' + rowid + '-' + cellid).val(padding_left);

		jQuery('<input type="hidden" id="valign_'+rowid+'-'+cellid+'" name="cell_valign['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#valign_' + rowid + '-' + cellid).val(valign);

		jQuery('<input type="hidden" id="align_'+rowid+'-'+cellid+'" name="cell_align['+rowid+'-'+cellid+']" value="" />').appendTo('#chimppress_meta_data');
		jQuery('#align_' + rowid + '-' + cellid).val(align);

    	jQuery(this).children('.item').each(function(){
    		var cellcontent = jQuery(this).parent().html();
    		var cellsplit = cellcontent.split('<!--remove-->');
    		//alert(cellsplit_one[0]+cellsplit_two[1]);
    		if(cellsplit[4] != null){
				jQuery('#' + rowid+'-'+cellid).val(cellsplit[0]+cellsplit[2]+cellsplit[4]);
			}else{
				jQuery('#' + rowid+'-'+cellid).val(cellsplit[0]+cellsplit[2]);
			}
    	});
    });

	//if(cells_array == 1){
		jQuery('#cells_array').val('');
		for(var i=0;i<cells.length;i++){
			if(cells[i] != null){
				jQuery('#cells_array').val(jQuery('#cells_array').val() +i+"=>"+cells[i]+',');
			}
		}
	//}

}

function add_controls(){
	jQuery(".sortable .item").append('<!--remove--><div class="controls"><div class="sort control"></div><div class="copy control"></div><div class="remove control"></div></div><!--remove-->');
}

function add_edit_content_buttons(){
	jQuery(".edit").each(function(){
		jQuery(this).parent().append('<!--remove--><input id="edit_button" type="button" class="button edit_button" value="Edit" onClick="editsave(\''+jQuery(this).attr('id')+'\')"/><!--remove-->')
	});
}

function add_td_click(){
	jQuery('#chimppresscampaigntemplate td').click(function(){
		jQuery('#chimppress_color_cell').html('cell ' + jQuery(this).parent().attr('id')+'-'+jQuery(this).attr('id'));
		var bg_col = jQuery(this).css('background-color');
		jQuery(this).stop().css("border-color", "#ffffff").animate({ borderTopColor: '#000000', borderLeftColor: '#000000', borderRightColor: '#000000', borderBottomColor: '#000000' }, 1500);
		clicked = jQuery(this);
	});
}

function saveColor(div){
	var selected_color = jQuery('#' + div).val();
	jQuery('#chimppress_saved_colors').val(jQuery('#chimppress_saved_colors').val() + selected_color);
	generate_saved_colors();
}

function generate_saved_colors(){
	var saved_colors = jQuery('#chimppress_saved_colors').val();
	var saved_colors_array = saved_colors.split('#');
	jQuery('#saved_colors').html('');
	jQuery.each(saved_colors_array, function(i, val) {
		if(val != ''){
			jQuery('#saved_colors').append('<a style="cursor:pointer;" onClick="add_saved_colors(\'#'+val+'\');"><div class="saved_color" style="background-color:#'+val+'"></div></a>');
		}
	});

}

function add_saved_colors(color){
	clicked.css('background', color);
	get_widgets();
}

function paddingTop(v){
	clicked.css('paddingTop', v + 'px');
	get_widgets();
}

function paddingRight(v){
	clicked.css('paddingRight', v + 'px');
	get_widgets();
}

function paddingBottom(v){
	clicked.css('paddingBottom', v + 'px');
	get_widgets();
}

function paddingLeft(v){
	clicked.css('paddingLeft', v + 'px');
	get_widgets();
}

//------ LOADING OVERLAY FUNCTIONS

function appendLoading(){
	jQuery('#wpbody').append('<div id="loading_div"><span id="inner">Loading...<br><br><img src="../wp-content/plugins/chimppress/img/ajax-loader.gif" /></span></div>');
}

function removeLoading(){
	jQuery('#loading_div').remove();
}

//-------- EDIT FUNCTIONS


var nicedit;

function editsave(id) {
var open = false;
	if(nicedit){
		jQuery('td div.edit').each(function(){
			if(jQuery(this).parent().find('.nicEdit-main').length > 0){
			nicedit.removeInstance(jQuery(this).attr('id'));
				if(id != jQuery(this).attr('id')){
					open = true;
				}
			}
		});
		nicedit = null;
		get_widgets();
		if(open){
			nicedit = new nicEditor({fullPanel : false, buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'indent', 'hr', 'image', 'link', 'unlink', 'xhtml', 'fontSize', 'fontFamily', 'fontFormat'], iconsPath: '../wp-content/plugins/chimppress/img/nicEditorIcons.gif'}).panelInstance(id,{hasPanel : true});
		}
	}else{
		nicedit = new nicEditor({fullPanel : false, buttonList: ['bold', 'italic', 'underline', 'left', 'center', 'right', 'indent', 'hr', 'image', 'link', 'unlink', 'xhtml', 'fontSize', 'fontFamily', 'fontFormat'], iconsPath: '../wp-content/plugins/chimppress/img/nicEditorIcons.gif'}).panelInstance(id,{hasPanel : true});
	}

};


//-------- UPLOAD FUNCTION
var id;
function upload_image_cp(idin) {
 id = idin;
 post_id = jQuery('#post_ID').val();
 formfield = jQuery('#upload_image').attr('name');
 tb_show('', 'media-upload.php?post_id='+post_id+'&amp;type=image&tabsremove=true&TB_iframe=true');

	window.send_to_editor = function(html) {
	 imgurl = jQuery('img',html).attr('src');
	 img = jQuery('<div>').append(jQuery('img',html).clone()).html();
	 document.getElementById(id).innerHTML = img;

	 jQuery('#'+id+' img').addClass("inserted_image");

	 tb_remove();
	 get_widgets();
	}

 return false;
};

//---------- TEMPLATE FUNCTIONS

function createDynamicTable(tbody, rows, cols, clear, rowstart, insert_position, cells_array) {
	if (tbody == null || tbody.length < 1) return;
	if (cells_array != null){
		var colspanarray = cells_array.split(',');
	}
	if (clear == 1){
 		tbody.html('');
 		jQuery('#select_template_graphic').remove();
		jQuery('#current_template_cols').val(cols);
		jQuery('#current_template_rows').val(rows);
		jQuery('#chimppress_templates_templates').css('display', 'none');
		jQuery('#chimppress_templates_notice').css('display', 'block');
	}
	var extra = false;
	if(rowstart > 0){
		extra = true;
		rowstart++;
		jQuery('#current_template_rows').val(rowstart);
	}
	for (var r = 1; r <= rows; r++) {
		if(rowstart == null || rowstart == ''){
			rowstart = r;
		}
		var trow = jQuery("<tr>").attr('id', rowstart);
		var total_cols_so_far = 0;
		colsloop: for (var c = 1; c <= cols; c++) {
			var amount_short = 1;
			if(c == cols && c < jQuery('#current_template_cols').val() && extra == true){
				var amount_short = (jQuery('#current_template_cols').val() - c)+1;
			}else{
				if(clear == 1 && cells_array != null){
					for (x in colspanarray){
						cell_colspan_array = colspanarray[x].split('=>');
						if(cell_colspan_array[0] == r.toString() + c.toString()){
							var amount_short = cell_colspan_array[1];
						}
					}
				}else{
					var amount_short = 1;
				}
			}
			total_cols_so_far = parseFloat(total_cols_so_far) + parseFloat(amount_short);

			jQuery("<td>")
			.addClass("sortable campaign_editor sortable_area")
			.data("col", c)
			.attr('colSpan', amount_short)
			.attr('id', c.toString())
			.appendTo(trow);
			if(c == cols && c < jQuery('#current_template_cols').val() && extra == true){
			}else{
				if(clear == 1 && cells_array != null){
					//alert(total_cols_so_far);
					for (x in colspanarray){
						cell_colspan_array = colspanarray[x].split('=>');
						if(cell_colspan_array[0] == r.toString() + c.toString()){

							if (cell_colspan_array[1] >= cols){
								break colsloop;
							}else{
								if(total_cols_so_far == cols){
									break colsloop;
								}
							}
						}
					}
				}
			}
		}
		var position = 'append';
		if(insert_position != null){
			if(insert_position == 'top'){
				position = 'prepend';
			}else if(insert_position == 'bottom'){
				position = 'append';
			}
		}
		if(position == 'append'){
			trow.appendTo(tbody);
		}else{
			trow.prependTo(tbody);
			var rownumber = 1;
			jQuery('#chimppresscampaigntemplate tr').each(function(){
				jQuery(this).attr('id', rownumber);
				rownumber++;
			})
		}
		rowstart++;
	}
	load_draggable();
	get_widgets();
	add_td_click();
};

function show_templates(){
	jQuery('#chimppress_templates_templates').css('display', 'block');
	jQuery('#chimppress_templates_notice').css('display', 'none');
}

//--------- EXTRAS FUNCTIONS

function add_row(){
	if(jQuery('#extra_cols').val() <= jQuery('#current_template_cols').val()){
		createDynamicTable(jQuery('#chimppresscampaigntemplate'), jQuery('#extra_rows').val(), jQuery('#extra_cols').val(), 0, jQuery('#current_template_rows').val(), jQuery('#position_to_insert').val());
	}else{
		alert('You can not add a new row with more columns than are already present.');
	};
};

//----- PREVIEW FUNCTIONS

function chimppress_preview(url){

	appendLoading();

	jQuery('#chimppresscampaigntemplate .controls, #chimppresscampaigntemplate .edit_button, #chimppresscampaigntemplate .image_button').remove();

	jQuery.post(url+"/functions/preview.php", { html: jQuery('#chimppress_campaign_content').html() },
		function(data) {
   			//alert(data);
 			jQuery('#preview_html').html(data);
 			jQuery('#preview_html #chimppresscampaigntemplate').attr('id', '');
 			jQuery('#preview_html td, #preview_html div').each(function(){
 				jQuery(this).attr('class', '');
 				jQuery(this).attr('id', '');
 			})
 			removeLoading();
 			tb_show('','#TB_inline?inlineId=preview_html&width=640&height=640');

     		jQuery("#chimppresscampaigntemplate .sortable .item").append('<div class="controls"><div class="sort control"></div><div class="copy control"></div><div class="remove control"></div></div>');
			jQuery("#chimppresscampaigntemplate .edit").parent().append('<input id="edit_button" type="button" class="button edit_button" value="Edit" />');
			//jQuery("#chimppresscampaigntemplate .edit_image").html('Image<br><input id="upload_image_button" class="button image_button" type="button" value="Upload New Image" />');
			jQuery('#chimppresscampaigntemplate .edit_button').each(function(){
				jQuery(this).attr('onClick', 'editsave(\''+jQuery(this).parent().children('div').attr('id')+'\')');
			});
			jQuery('#chimppresscampaigntemplate .image_button').each(function(){
				jQuery(this).attr('onClick', 'upload_image_cp(\''+jQuery(this).parent().attr('id')+'\')');
			});
			load_draggable();
   		}
   	);

}

//----- EXPORT FUNCTIONS

function export_template(){

	get_widgets();

	jQuery('#import_div').slideUp();

	jQuery('#export_div').slideToggle();


}

//----- IMPORT FUNCTIONS

function import_template(){

	get_widgets();

	jQuery('#export_div').slideUp();

	jQuery('#import_div').slideToggle();


}

//----- SAVE

function saveContent(){

	jQuery('#preview_import div').html('');
	jQuery('#preview_import').slideUp();

	if(jQuery('#import_textarea').val() != ''){

		var import_content = jQuery('#import_textarea').val();

		jQuery('#chimppress_campaign_content').html(import_content.replace(/(\r\n|\n|\r)/gm,""));

		get_widgets();

	}

	if(nicedit){
		jQuery('td div.edit').each(function(){
			nicedit.removeInstance(jQuery(this).attr('id'));
		});
	}


	jQuery('.ui-wrapper > img.inserted_image').unwrap();

	jQuery('#chimppress_campaign_content tr').each(function(){

		var remove = true;

		jQuery(this).find('td').each(function(){

			if(jQuery(this).html().trim() != ''){
				remove = false;
			}

		});

		if(remove){
			jQuery(this).remove();
		}

	}).delay(500);

	jQuery('#chimppresscampaigntemplate .controls, #chimppresscampaigntemplate .edit_button, #chimppresscampaigntemplate .image_button, #chimppresscampaigntemplate .ui-resizable-handle, .ui-wrapper').remove();

	jQuery('img.inserted_image').removeClass('ui-resizable');

	get_widgets();

	jQuery('#campaign_content_input').val(jQuery('#chimppress_campaign_content').html());

	//return false;
}

//----- COLOR FUNCTIONS

function activateColors(){



}


function chimppress_campaign_send(id){
	if(confirm("Are you sure you are ready to send this campaign?\n\nThis can not be undone!")){
		jQuery('#send_the_campaign').val('yes');
		jQuery('#publish').click();
	}
}