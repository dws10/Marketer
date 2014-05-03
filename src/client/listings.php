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

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Client Dashboard');

//assign active menu item
$smarty->assign('productsActive','active');


$listing_row = '';
$listings = $store->getListings();

foreach($listings as $id => $listing){

	$category_name = $listing->product->getCategoryName();
	$product_title = $listing->product->title;
	$price = $listing->price;
	$quantity = $listing->quantity;
	
	$activated = $listing->activated;
	if($activated){
		$gateway = $store->ebay_gateway;
		$requestURL = 'http://open.api.sandbox.ebay.com/shopping?';
		
		$request['callname'] = 'GetSingleItem';
		$request['responseencoding'] = 'XML';
		$request['appid'] = $gateway->appID;
		$request['siteid'] = $gateway->siteID;
		$request['version'] = $gateway->version;
		$request['ItemID'] = $listing->ebay_item_id;
		$request['IncludeSelector'] = 'Details';
		
		foreach($request as $key => $value){
			$requestURL.= $key.'='.$value.'&';
		}
		
		$requestURL = rtrim($requestURL, "&");
	
		$xml = new SimpleXMLElement(file_get_contents($requestURL));
		if((string)$xml->Ack != 'Failure'){
			$quantity = (string) $xml->Item->Quantity - $xml->Item->QuantitySold;
			$listing->setQuantity($quantity);
		}
		
	}
	
	
	$listing_row .= '<div class="row">';
		$listing_row .= '<div class="col-md-1">'.$id.'</div>';
		$listing_row .= '<div class="col-md-2">'.$category_name.'</div>';
		$listing_row .= '<div class="col-md-4">'.$product_title.'</div>';
		$listing_row .= '<div class="col-md-1">£'.number_format($price, 2).'</div>';
		$listing_row .= '<div class="col-md-1">'.$quantity.' in stock</div>';
		$listing_row .= '<div class="col-md-1">';
		if($activated){
			$listing_row .= '<a class="btn btn-danger btn-block" href="end_listing.php?listing_id='.$id.'">Delist</a>';
		}else{
			$listing_row .= '<a class="btn btn-success btn-block" href="list_listing.php?listing_id='.$id.'">List</a>';
		}
		$listing_row .= '</div>';
		$listing_row .= '<div class="col-md-1">';
		if(!$activated){
			$listing_row .= '<a class="btn btn-primary btn-block" href="edit_listing.php?listing_id='.$id.'">Edit</a>';
		}
		$listing_row .= '</div>';
		$listing_row .= '<div class="col-md-1">';
		if(!$activated){
			$listing_row .= '<button class="btn btn-danger btn-block">Delete</button>';
		}
		$listing_row .= '</div>';
	$listing_row .= '</div>';
	
	
}
$smarty->assign('listing_row',$listing_row);



//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/listings.tpl');
?>