// JavaScript Document
$(document).ready(function(){
	$(".end-listing").click(function(){
		xajax_end_listing(xajax.getFormValues(this.form));
		return false;
	});	
})