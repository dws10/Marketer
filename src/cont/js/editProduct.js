// JavaScript Document

//cancel changes
$(document).ready(function(){
	$('.cancel_edit').click(function(){
		xajax_cancel_edit();return false;
	});
	
	$('.edit_general').click(function(){
		xajax_edit_product_general(xajax.getFormValues('edit_general'));return false;
	});
	
	$('.edit_attributes').click(function(){
		xajax_edit_product_attributes(xajax.getFormValues('edit_attributes'));return false;
	});
});