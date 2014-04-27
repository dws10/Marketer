// JavaScript Document
$(document).ready(function(){
	$('.save_shipping_template').click(function(){
		xajax_shipping_template_create(xajax.getFormValues(this.form));
		return false;
	});
})