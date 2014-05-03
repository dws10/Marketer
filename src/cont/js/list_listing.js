// JavaScript Document
$(document).ready(function(){
	$(".list-listing").click(function(){
		xajax_list_listing(xajax.getFormValues(this.form));
		return false;
	});	
})