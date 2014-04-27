<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"confirm_listing");
//confirm activating a listing
function confirm_listing($formValues){
	$resp = new xajaxResponse();
		//setup employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store_id = $employee->storeID;	
		$store = new Store($store_id);
		//get listing id from the form values
		$listing_id = $formValues['listing_id'];
		//get listing form store by id
		$listing = $store->getListing($listing_id);
		//get the stores gateway
		$gateway = $store->ebay_gateway;
		//set the api action to the correct api call
		$gateway->SetAction('AddItem');
		//open an eBay session
		$session = $gateway->OpenSession();
		//incude and instantiate the request
		include('../class/AddItemRequest.php');
		$xmlRequest = new AddItemRequest($listing);
		//send the request and recieve the response
		$responseXml = $session->sendHttpRequest($xmlRequest->ConfirmRequest());
		//real the response as XML with simple xml
		$xml = new SimpleXMLElement($responseXml);
		if((string)$xml->Ack != 'Failure'){
			//if success get the eBay item id
			$item_id = (string) $xml->ItemID;
			//set the eBay item id
			$listing->setItemID($item_id);
			//activate the listing
			$listing->activateListing();
			//redirect to the listings page
			$resp->redirect('./listings.php');
		}
	return $resp;
}
?>