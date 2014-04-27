<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"get_ebay_recommended");
//get ebay recommended attributes
function get_ebay_recommended($category_id){
	$resp = new xajaxResponse();
		//clear attribute list
		$resp->assign('attribute-list','innerHTML', '');
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to get the real ebay id
		$sql = "SELECT a.ebay_category_real_id FROM ebay_category_tbl a INNER JOIN category_tbl b ON a.ebay_category_id = b.category_ebay_id WHERE b.category_id = ?";
		//prepare the statement		
		$stmt = $mysqli->prepare($sql);
		//bind the parameters	
		$stmt->bind_param('i',$category_id);
		
		if (!$stmt->execute()) {
			//if failure echo error
			$resp->alert("Failed to update category parentID: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>');
		}else{
			//if success bind the results
			$stmt->bind_result($ebay_id);
			//and store them to use num rows
			$stmt->store_result();
			if($stmt->num_rows == 1){
				//if there is 1 result fetch it
				$stmt->fetch();
				//setup employee
				$employee = new Employee();
				//authenticate employee from session
				$employee->authenticateSession();
				//setup store with employee id
				$store_id = $employee->storeID;	
				$store = new Store($store_id);
				//get stores ebay gateway
				$gateway = $store->ebay_gateway;
				//set action to the correct api call 
				$gateway->SetAction('GetCategorySpecifics');
				//open new ebay session
				$session = $gateway->OpenSession();
				//instantiate a new get category request
				$xmlRequest = new GetCategorySpecifics($store->auth_token);
				//send the request and recieve the response			
				$response = $session->sendHttpRequest($xmlRequest->getCategorySpecifics($ebay_id));
				//load the xml response
				$xmlResponse = simplexml_load_string($response);
				if($xmlResponse->Ack == 'Success'){
					$btns = ''; 
					$script = "sameHeightByClass('top-articles');";
					//if success read through the XML
					foreach($xmlResponse->Recommendations->NameRecommendation as $NameRecommendation)
					{
						//get attribute name and type
						$Name = (string) $NameRecommendation->Name;
						$SelectMode = (string) $NameRecommendation->ValidationRules->SelectionMode;
						//clean the name to get an id
						$remove = array(" ","/","'","_");
						$id_name = str_replace($remove, '', $Name);
						if($SelectMode == 'SelectionOnly')
						{
							//if it is a select based option get the recommended options for the attribute
							$option = array();
							foreach($NameRecommendation->ValueRecommendation as $ValueRecommendation){
								$option[] = (string) $ValueRecommendation->Value;
							}
							//convert options to json
							$opt = json_encode($option, JSON_HEX_APOS | JSON_HEX_QUOT);
							//build the button
							$btns .= '<li class="list-group-item">';
							$btns .= "<button id='".$id_name."_btn' class='btn btn-primary btn-block'>".$Name."</button>";
							$btns .= '</li>';
							//add the buttons event with the options json
							$script .= "$('#".$id_name."_btn').click(function(){xajax_add_new_select_attribute(\"".$Name."\", ".$opt.");return false;});";							
						}else{ 
							//if an open text option build the button
							$btns .= '<li class="list-group-item">';
							$btns .= '<button id="'.$id_name.'_btn" class="btn btn-primary btn-block">'.$Name.'</button>';
							$btns .= '</li>';
							//add the buttons event
							$script .= "$('#".$id_name."_btn').click(function(){xajax_add_new_text_attribute(\"".$Name."\");return false;});";
						}
					}
					//assign buttons to the page
					$resp->assign('recommendation_btns','innerHTML',$btns); 
					//attach the events to the buttons
					$resp->script($script);
				}else{
					$resp->alert('error');	
				}	
			}
		}
	return $resp;
}
?>