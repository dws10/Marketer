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
include('../cont/php/get_product_durations.php');
include('../cont/php/listing_create.php');
//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Add Listing');

//assign active menu item
$smarty->assign('productsActive','active');

//assign page
$smarty->assign('page','Add Listing');


//get products
$products = $store->getProducts();
$opt = '';
foreach($products as $id => $product){
	$opt .= "<option value='".$id."'>".$product->title."</option>";
}
$smarty->assign('product_list',$opt);




//get shipping templates
$shipping_templates = $store->getShippingTemplates();
$opt = '';
foreach($shipping_templates as $id => $template){
	$opt .= "<option value='".$id."'>".$template->title."</option>";
}
$smarty->assign('shipping_template_list',$opt);

//get return templates
$return_templates = $store->getReturnTemplates();
$opt = '';
foreach($return_templates as $id => $template){
	$opt .= "<option value='".$id."'>".$template->title."</option>";
}
$smarty->assign('return_template_list',$opt);

//get display templates
$displayTemplates = $store->getDisplayTemplates();

$opt = '';
foreach($displayTemplates as $id => $path){
	$name =  str_replace('.php','', $path);
	$name =  str_replace('_',' ', $name);
	$opt .= "<option value='".$id."'>".$name."</option>";	
}
$smarty->assign('display_template_list',$opt);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/new_listing.tpl');
?>