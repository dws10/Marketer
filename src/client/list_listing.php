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
include('../cont/php/list_listing.php');
include('../cont/php/confirm_listing.php');
//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Client Dashboard');

//assign active menu item
$smarty->assign('productsActive','active');


$listing_id = $_GET['listing_id'];
$smarty->assign('listing_id',$listing_id);

$listing = $store->getListing($listing_id);

$displayTemplateContent = $listing->getDisplayTemplateContent();
$smarty->assign('displayTemplateContent',$displayTemplateContent);
//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/list_listing.tpl');
?>