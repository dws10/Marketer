// JavaScript Document
$(document).ready(function(){
	$(".product_select").change(function(){
		xajax_get_product_conditions(xajax.getFormValues(this.form));
		xajax_get_product_durations(xajax.getFormValues(this.form));
		return false;
	});	
	$(".edit_listing").click(function(){
		xajax_listing_edit(xajax.getFormValues(this.form));
		return false;
	});	
})