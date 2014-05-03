// JavaScript Document

$(document).ready(function(){
	
	$('.add_cat').click(function(){
		xajax_category_create(xajax.getFormValues($(this).closest("form").attr('id')));
		return false;
	});
	
	$('.save_cat').click(function(){
		xajax_category_update(xajax.getFormValues($(this).closest("form").attr('id')));
		return false;
	});
	
	$('.ebay_category_select_holder').on('change', 'select', function(){
			
		//set this element for use later
		var this_element = $(this);
		
		//remove any select before the element that is changed
		$(this).nextAll().remove()
		
		$.get( "../AJAXviews/ebay_categories_by_parent.php",{parentID : $(this).val()},function(data) {
			//alert( "success" );
		})
		.done(function(data) {
			//alert(data)
			if(data == 'null'){
				//if no options returned set category value to the id of the leaf category
				this_element.parent().find('input[name=ebay_cat]').val(this_element.val());
			}else{
				//if select is returned add it after this (changed select box) element 
				$(data).insertAfter( this_element );
			}
		})
		.fail(function() {
			//ajax error
			alert( "error" );
		})
		.always(function() {
			//actions to perform after request finishes
			//alert( "finished" );
		});
		
		return false;
	});

	
});