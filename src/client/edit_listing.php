<?php
//include the generic page setup
include('./page.php');

//register page specific xajax functions
$xajax->register(XAJAX_FUNCTION,"test");

//page specific xajax functions
function test(){
	$resp = new xajaxResponse();
		//$resp->alert('test');	
	return $resp;
}

//include shared xajax functions
include('../cont/php/client_logout.php');
include('../cont/php/get_product_conditions.php');
include('../cont/php/listing_edit.php');
//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Edit Listing');

//assign active menu item
$smarty->assign('productsActive','active');

//assign page
$smarty->assign('page','Edit Listing');

$listing_id = $_GET['listing_id'];
$smarty->assign('listing_id',$listing_id);

$listing = $store->getListing($listing_id);


//get products
$products = $store->getProducts();
$opt = '';
foreach($products as $id => $product){
	if($id == $listing->product->product_id){
		$opt .= "<option value='".$id."' selected>".$product->title."</option>";
	}else{
		$opt .= "<option value='".$id."'>".$product->title."</option>";
	}
}
$smarty->assign('product_list',$opt);

//get product conditions

//get products category id
$category_id = $product->category_id;
//get category from store with the id
$category = $store->getCategory($category_id);

//get the ebay category object from the category
$ebay_category = $category->ebay_category;
//get conditions for ebay category
$ebay_conditions = $ebay_category->getConditionOptions();
//loop through condition array to setup select options
$opt = "<option value='0'>Please select a condition</option>";
foreach($ebay_conditions as $id => $name){
	if($id == $listing->conditionCode){
		$opt .= "<option value='".$id."' selected>".$name."</option>";
	}else{
		$opt .= "<option value='".$id."'>".$name."</option>";
	}
}
//assign to page
$smarty->assign('condition_opts',$opt);

//assign condition description
$smarty->assign('condition_desc',$listing->conditionDetails);

//assign price
$smarty->assign('price',number_format($listing->price, 2));

//assign quantity
$smarty->assign('quantity',$listing->quantity);

//assign duration
//provide usertoken, site id 3 = uk and api action
$gateway = $store->ebay_gateway;
$gateway->SetAction('GetCategoryFeatures');

//open new ebay session
$session = $gateway->OpenSession();
		
$ebay_id = $ebay_category->real_id;		

//include categoryRequest class
include('../class/GetCategoryFeaturesRequest.php');
//instantiate a new get category request
$xmlRequest = new GetCategoryFeaturesRequest($store->auth_token);
//send the request and recieve the response			
$response = $session->sendHttpRequest($xmlRequest->getListingDurations($ebay_id));
//load the xml response
$xmlResponse = simplexml_load_string($response);

$Category = $xmlResponse->Category;


//var_dump($xmlCategory);

foreach($Category->ListingDuration as $ListingDuration){
	if($ListingDuration['type'] == "StoresFixedPrice"){
		$duration_type_code = (string) $ListingDuration;
	}
}
$duration_codes = array();
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
			$duration_codes[$code] = $name;
		}
	}
}

$opt = '';
foreach($duration_codes as $code => $name){
	if($code == $listing->duration){
		$opt .= "<option value='".$code."' selected>".$name."</option>";
	}else{
		$opt .= "<option value='".$code."'>".$name."</option>";
	}
}
$smarty->assign('duration_opt',$opt);

//get shipping templates
$shipping_templates = $store->getShippingTemplates();
$opt = '';
foreach($shipping_templates as $id => $template){
	if($id == $listing->shippingTemplate->id){
		$opt .= "<option value='".$id."' selected>".$template->title."</option>";
	}else{
		$opt .= "<option value='".$id."'>".$template->title."</option>";
	}
}
$smarty->assign('shipping_template_list',$opt);

//get return templates
$return_templates = $store->getReturnTemplates();
$opt = '';
foreach($return_templates as $id => $template){
	if($id == $listing->returnTemplate->id){
		$opt .= "<option value='".$id."' selected>".$template->title."</option>";
	}else{
		$opt .= "<option value='".$id."'>".$template->title."</option>";
	}
}
$smarty->assign('return_template_list',$opt);

//get display templates
$displayTemplates = $store->getDisplayTemplates();

$opt = '<option value="0" selected>Please select a display template</option>';
foreach($displayTemplates as $id => $path){
	$name =  str_replace('.php','', $path);
	$name =  str_replace('_',' ', $name);
	
	if($id == $listing->displayTemplate){
		$opt .= "<option value='".$id."' selected>".$name."</option>";
	}else{
		$opt .= "<option value='".$id."'>".$name."</option>";
	}
}
$smarty->assign('display_template_list',$opt);


$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/edit_listing.tpl');
?>