// JavaScript Document
$(document).ready(function(){
	$(".product_select").change(function(){
		xajax_get_product_conditions(xajax.getFormValues(this.form));
		xajax_get_product_durations(xajax.getFormValues(this.form));
		return false;
	});	
	$(".add_listing").click(function(){
		xajax_listing_create(xajax.getFormValues(this.form));
		return false;
	});	
})