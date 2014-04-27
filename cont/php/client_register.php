<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"client_register");
//register a new client
function client_register($formValues){
	$resp = new xajaxResponse();	
		//get form values
		$fname = $formValues['fname'];
		$sname = $formValues['sname'];
		$email = $formValues['email'];
		$password = $formValues['password'];
		$con_password = $formValues['con_password'];
		$store_name = $formValues['store_name'];
		$auth_token = $formValues['auth_token'];
		$paypal_email = $formValues['paypal_email'];
		$postcode = $formValues['address_postcode'];
		$building = $formValues['address_building'];
		$road1 = $formValues['address_road1'];
		$road2 = $formValues['address_road2'];
		$town = $formValues['address_town'];
		$county = $formValues['address_county'];
		//include and instantiate the validation flag
		include('./class/Validator.php');
		$validate = new Validation();
		//set validity flag to valid
		$flag = true;
		//build the error container
		$error_top = '<div class="alert alert-danger alert-dismissable navbar-right register-error">';
		$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
		$error_bot = '</div>';
		//clear errors for retry
		$resp->assign('fname-error','innerHTML','');
		$resp->assign('sname-error','innerHTML','');
		$resp->assign('email-error','innerHTML','');
		$resp->assign('password-error','innerHTML','');
		$resp->assign('con-password-error','innerHTML','');
		$resp->assign('store-name-error','innerHTML','');
		$resp->assign('auth-token-error','innerHTML','');
		$resp->assign('paypal-email-error','innerHTML','');
		$resp->assign('address-error','innerHTML','');
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
		if($validate->equals($password, '')){
			//assign error
			$error_msg = 'Password is required please enter a valid Password';
			$resp->assign('password-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if($validate->length_less($password, 8)||$validate->length_more($password, 16)){
				//assign error
				$error_msg = 'Password must be between 8 and 16 characters';
				$resp->assign('password-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate con_password
		if($validate->equals($con_password, '')){
			//assign error
			$error_msg = 'Confirm Password is required please Confirm your Password';
			$resp->assign('con-password-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if(!$validate->equals($password, $con_password)){
				//assign error
				$error_msg = 'Confirm Password does not match your Password please try again';
				$resp->assign('con-password-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate store_name
		$safechars = array("'",'.','-','@','!', '£','$','&','(', ')',' ');
		$store_name_clean = str_replace($safechars, '', $store_name);
		if($validate->equals($store_name, '')){
			//assign error
			$error_msg = 'Store Name is required please enter a Store Name';
			$resp->assign('store-name-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if(!$validate->alpha_numeric($store_name_clean)){
					//assign error
					$error_msg = 'Store Name must be alphanumeric or "\'", ".", "-", "@", "!", "£", "$", "&", "(", ")"';
					$resp->assign('store-name-error','innerHTML',$error_top.$error_msg.$error_bot);
					$flag = false;
			}
		}
		//validate auth_token (maybe)
		if($validate->equals($auth_token, '')){
			//assign error
			$error_msg = 'eBay Authentication Token is required please enter an eBay Authentication Token';
			$resp->assign('auth-token-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}
		//validate paypal email
		if($validate->equals($paypal_email, '')){
			//assign error
			$error_msg = 'The stores Paypal email address is required please enter a valid Email Address';
			$resp->assign('paypal-email-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}else{
			if(!$validate->email($paypal_email, '')){
				//assign error
				$error_msg = 'The Paypal email address is invalid please try again';
				$resp->assign('paypal-email-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;
			}
		}
		//validate address
		$error_msg = 'Address Error: <br/>';
		$address_valid = true;
		if($validate->equals($postcode, '')){
			//assign error
			$error_msg .= 'Postcode is required please enter a valid Postcode<br/>';
			$address_valid = false;
		}
		if($validate->equals($building, '')){
			//assign error
			$error_msg .= 'Building Name/Number is required please enter a Building Name/Number<br/>';
			$address_valid = false;
		}
		if($validate->equals($road1, '')){
			//assign error
			$error_msg .= 'Address Line One is required please enter Address Line One<br/>';
			$address_valid = false;
		}
		if($validate->equals($town, '')){
			//assign error
			$error_msg .= 'Town/City is required please enter a Town/City<br/>';
			$address_valid = false;
		}
		if($validate->equals($county, '')){
			//assign error
			$error_msg .= 'County is required please enter a County<br/>';
			$address_valid = false;
		}
		if(!$address_valid){
			//if address is invalid assign address error
			$resp->assign('address-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;
		}
		if($flag){
			//if the formvalues are valid include the guest class
			include('./class/Guest.php');
			//and instantiate a new guest
			$guest = new Guest();
			//register the guest
			$register = $guest->register($formValues);
			//assign success message
			$resp->assign('register', 'innerHTML', '<h3>Thank you for registering, please sign in using the form at the top of the page.</h3>');
		}
	return $resp;
}
?>