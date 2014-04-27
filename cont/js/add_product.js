// JavaScript Document

$(document).ready(function(){
	
	$('.add_product').click(function(){
		xajax_add_product(xajax.getFormValues('add_product'));
		return false;
	});
	
	$('.product_category').change(function(){
		xajax_get_ebay_recommended($(this).val());
		return false;
	});

	var sortableIn;
	$('.attribute-list').sortable({
		receive: function(e, ui) { sortableIn = 1; },
		over: function(e, ui) { sortableIn = 1; },
		out: function(e, ui) { sortableIn = 0; },
		beforeStop: function( event, ui ) {
			var name = ui.item.find('input[name="attribute_name[]"]').val();
			
			var removeChar = new Array("/"," ","_","'");
			var nameSafe = name;
			for(var i = 0; i < removeChar.length; i++){
				nameSafe = nameSafe.replace(removeChar[i], '', 'g');
			}
			
			var btn_name_id = '#' + nameSafe + '_btn';
			
			
			if(sortableIn == 0){
				ui.item.remove();
				
				$(btn_name_id).prop('disabled', false);
				$(btn_name_id).attr('class', 'btn btn-primary btn-block');
			}
		},
		cursor: 'pointer',
		revert: true
	})
	//
	
});