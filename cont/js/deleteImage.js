// JavaScript Document
$(document).ready(function(){
	
	$('.delete_loading').hide();
	
	
	$('#image-container').on('click', '.remove_image', function(){
		
		var form = $(this).parent().parent().parent();
		var image_id_field = form.find('input[name="image_id[]"]');
		var image_id_value = image_id_field.val();
		var list_element = form.parent();
		
		$(this).parent().find('.delete_loading').show();
		
		xajax_delete_image(image_id_value);
		
		$(list_element).remove();
		
		return false;
	});
});