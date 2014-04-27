// JavaScript Document

$(document).ready(function(){
	$('.update-account-btn').click(function(){
		xajax_client_update(xajax.getFormValues('client_update'));
		return false;
	});
});