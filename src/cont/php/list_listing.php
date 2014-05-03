<?php 
	//register xajax function
	$xajax->register(XAJAX_FUNCTION,"list_listing");
	function list_listing($formValues){
		$resp = new xajaxResponse();
			//setup employee
			$employee = new Employee();
			//authenticate employee from session
			$employee->authenticateSession();
			//setup store with employee id	
			$store = new Store($employee->storeID);
			//get the listing id
			$listing_id = $formValues['listing_id'];
			//use the id to get the listing from the store
			$listing = $store->getListing($listing_id);
			//get the stores eBay gateway
			$gateway = $store->ebay_gateway;
			//set the action to the correct api call
			$gateway->SetAction('VerifyAddItem');
			//open the session with eBay
			$session = $gateway->OpenSession();
			//include and instantiate the add item request
			include('../class/AddItemRequest.php');
			$xmlRequest = new AddItemRequest($listing);
			//get the request response
			$responseXml = $session->sendHttpRequest($xmlRequest->VerifyRequest());
			//read it as XML with simple xml
			$xml = new SimpleXMLElement($responseXml);		
			if((string)$xml->Ack != 'Failure'){
				//if request success
				$fees = '<div class="col-md-2"></div>';
					$fees .= '<div class="col-md-8">';
						$fees .= '<ul>';
						//get the listing fees
						foreach($xml->Fees->Fee as $Fee){
							if((string) $Fee->Fee != 0){
								$fees .= '<li>'.(string) $Fee->Name. ' - £' . number_format((string) $Fee->Fee, 2) .'</li>';
							}
						}
						$fees .= '</ul>';
					$fees .= '</div>';
				$fees .= '<div class="col-md-2"></div>';
				//display the listing fees
				$resp->assign('preview', 'innerHTML', $fees);
				//create confirm listing button
				$btn = "<form>";
				  $btn .= "<input type='hidden' name='listing_id' value='".$listing_id."'/>";
				  $btn .= "<button class='btn btn-primary btn-block confirm-listing'>Confirm Listing Activiation</button>";
				$btn .= "</form>";
				//create the buttons event
				$script="$('.confirm-listing').click(function(){xajax_confirm_listing(xajax.getFormValues(this.form));return false;})";
				//assign button to the page
				$resp->assign('btn-holder', 'innerHTML', $btn);
				//assing the buttons event
				$resp->script($script);
			}
		return $resp;
	}
?>