<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"end_listing");
function end_listing($formValues){
	$resp = new xajaxResponse();
		//setup employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store = new Store($employee->storeID);
		//clear error messages
		$resp->assign('reason-error', 'innerHTML', '');
		//build error message container
		$error_top = '<div class="alert alert-danger alert-dismissable navbar-right">';
		$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
		$error_bot = '</div>';
		//include the validator class
		include('../class/Validator.php');
		//and instantiate it
		$validate = new Validation();
		//validity flag
		$flag = true;
		//get form values
		$reason = $formValues['reason'];
		//reason is required
		if($validate->equals($reason,'0')){
			//validity is invalid
			$flag = false;
			//set message
			$message = "Please select a reason for ending this listing";
			//assign error
			$resp->assign('reason-error', 'innerHTML', $error_top.$message.$error_bot);
		}
		//if the validity flag is valid
		if($flag){
			//get the listing id
			$listing_id = $formValues['listing_id'];
			//and use it to get the listing from the store
			$listing = $store->getListing($listing_id);
			//get ebay item id
			$ebay_id = $listing->ebay_item_id;
			//get the store ebay gateway
			$gateway = $store->ebay_gateway;
			//set the action to the correct api call
			$gateway->SetAction('EndItem');
			//open the session with eBay
			$session = $gateway->OpenSession();
			//include the request
			include('../class/EndItemRequest.php');
			//and instantiate it
			$xmlRequest = new EndItemRequest($store->auth_token, $reason, $ebay_id);
			//get the response from eBay
			$responseXml = $session->sendHttpRequest($xmlRequest->EndItem());
			//read it as a simpleXML element
			$xml = new SimpleXMLElement($responseXml);
			if((string)$xml->Ack != 'Failure'){
				//if success deactivate the listing
				$listing->deactivateListing();
				//and redirect
				$resp->redirect('./listings.php');
			}
		}
	return $resp;
}
?>