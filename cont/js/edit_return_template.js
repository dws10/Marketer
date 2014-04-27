// JavaScript Document

$(document).ready(function(){
	$('.save_return_template').click(function(){
		xajax_return_template_edit(xajax.getFormValues(this.form));	
		return false;
	})
});