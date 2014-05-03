<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"client_update");
//update the client
function client_update($formValues){
	$resp = new xajaxResponse();
		//get form values
		$user_id = $formValues['user_id'];
		$fname = $formValues['fname'];
		$sname = $formValues['sname'];
		$email = $formValues['email'];
		$password = $formValues['password'];
		$con_password = $formValues['con_password'];
		//include and instantiate the validator
		include('../class/Validator.php');
		$validate = new Validation();
		//set validity flag
		$flag = true;
		//build the error container
		$error_top = '<div class="alert alert-danger alert-dismissable navbar-right register-error">';
		$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
		$error_msg = '';
		$error_bot = '</div>';
		//clear errors for retry
		$resp->assign('fname-error','innerHTML','');
		$resp->assign('sname-error','innerHTML','');
		$resp->assign('email-error','innerHTML','');
		$resp->assign('password-error','innerHTML','');
		$resp->assign('con-password-error','innerHTML','');
		//validate fname
		$safechars = array("'");
		$fname_clean = str_replace($safechars, '', $fname);
		if($validate->equals($fname, '')){
			//assign error
			$error_msg = 'First Name is required please enter a First Name';
			$resp->assign('fname-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if(!$validate->alpha($fname_clean)){
				//assign error
				$error_msg = 'First Name can only use letters please remove numbers and characters.';
				$resp->assign('fname-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate sname
		$safechars = array("'",'-');
		$sname_clean = str_replace($safechars, '', $sname);
		if($validate->equals($sname, '')){
			//assign error
			$error_msg = 'Surname is required please enter a Surname';
			$resp->assign('sname-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if(!$validate->alpha($sname_clean)){
				//assign error
				$error_msg = 'Surname can only use letters please remove numbers and characters.';
				$resp->assign('sname-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate email
		if($validate->equals($email, '')){
			//assign error
			$error_msg = 'Email Address is required please enter a valid Email Address';
			$resp->assign('email-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if(!$validate->email($email, '')){
				//assign error
				$error_msg = 'Email Address is invalid please try again';
				$resp->assign('email-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate password
		if(!$validate->equals($password, '')){
			if($validate->length_less($password, 8)||$validate->length_more($password, 16)){
				//assign error
				$error_msg = 'Password must be between 8 and 16 characters';
				$resp->assign('password-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate con_password
		if(!$validate->equals($password, '')){
			if(!$validate->equals($password, $con_password)){
				//assign error
				$error_msg = 'Confirm Password does not match your Password please try again';
				$resp->assign('con-password-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//if form values are valid
		if($flag){
			//setup employee
			$employee = new Employee();
			//authenticate employee from session
			$employee->authenticateSession();
			//update the employee with the form values
			if($employee->update($formValues)){
				//if successful reload the page
				$resp->redirect('./account.php');
			}
		}
	return $resp;
}
?>