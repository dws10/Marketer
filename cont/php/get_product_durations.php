<?php
	$xajax->register(XAJAX_FUNCTION,"get_product_durations");
	function get_product_durations($formValues){
		$resp = new xajaxResponse();
		
		//get product id from the page
		$product_id = $formValues['product'];
		
		if($product_id != 0){
		
			//setup employee
			$employee = new Employee();
			//authenticate employee from session
			$employee->authenticateSession();
			//setup store with employee id
			$store_id = $employee->storeID;	
			$store = new Store($store_id);
	
			//get products category id
			$category_id = $store->getProduct($product_id)->category_id;
			//get ebay category real id
			$ebay_id = $store->getCategory($category_id)->ebay_category->real_id;
			
			//assign duration
			//provide usertoken, site id 3 = uk and api action
			$gateway = $store->ebay_gateway;
			
			$gateway->SetAction('GetCategoryFeatures');
			
			//open new ebay session
			$session = $gateway->OpenSession();
				
			//include categoryRequest class
			include('../class/GetCategoryFeaturesRequest.php');
			//instantiate a new get category request
			$xmlRequest = new GetCategoryFeaturesRequest($store->auth_token);
			//send the request and recieve the response			
			$response = $session->sendHttpRequest($xmlRequest->getListingDurations($ebay_id));
			//load the xml response
			$xmlResponse = simplexml_load_string($response);
			//navigate XML
			$Category = $xmlResponse->Category;
			foreach($Category->ListingDuration as $ListingDuration){
				if($ListingDuration['type'] == "StoresFixedPrice"){
					//get the duration type code
					$duration_type_code = (string) $ListingDuration;
				}
			}
			//build select options
			$opt = '<option value="0">Please select a listing duration</option>';
			foreach($xmlResponse->FeatureDefinitions->ListingDurations->ListingDuration as $ListingDuration){
				
				if($ListingDuration['durationSetID'] == $duration_type_code){
					foreach($ListingDuration->Duration as $Duration){
						if($Duration == 'GTC'){
							$code = (string)$Duration;
							$name = 'Good Till Cancelled';
						}else{
							$code = (string)$Duration;
							$name_split = explode('_',$Duration);
							$name = $name_split[1].' '.$name_split[0];
						}
						$opt .= "<option value='".$code."'>".$name."</option>";
					}
				}
			}
			//assign the select options to the page
			$resp->assign('duration_options','innerHTML',$opt);
		}
		return $resp;
	}




?>

