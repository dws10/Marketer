<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"client_login");
function client_login($formValues){
	$resp = new xajaxResponse();
	//include and instantiate the guest
	include('./class/Guest.php');
	$guest = new Guest();
	//login
	$login = $guest->login($formValues);
	if(substr($login, 0, 5) == 'Error'){
		//if error
		$error = $login;
		//build the error message
		$msg = '<div class="alert alert-danger alert-dismissable login_error">';
			$msg .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			$msg .= $error;
		$msg .= '</div>';
		//assign the error message
		$resp->assign("login_error","innerHTML",$msg);
	}else{
		//redirect to the client area
		$resp->redirect('./client/index.php');	
	}
	return $resp;
}
?>