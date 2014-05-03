// JavaScript Document

$(document).ready(function(){
	

	
	$('#image-container').sortable({
		cursor: 'pointer',
		revert: true
	});
	
	$('.update_image_order').click(function(){
		
		var image_id = new Array();
		image_id = [];
		
		$('#image-container li').each(function(index){
				image_id = $(this).find("form div input[name='image_id[]']").val()
				order = index + 1;
				xajax_update_image_order(image_id, order);
		});
		
		

		return false;
	});
	
});