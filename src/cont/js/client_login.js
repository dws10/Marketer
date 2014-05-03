//navigation triggers
	$(document).ready(function(){
		$('.login').click(function(){
			//call xajax login function
			xajax_client_login(xajax.getFormValues('login'));return false;
		});
		$('.login_error').hide();
	});