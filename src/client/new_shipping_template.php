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
include('../cont/php/shipping_template_create.php');
//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','New Shipping Template');

//assign active menu item
$smarty->assign('templatesActive','active');

//select shipping options
$mysqli = new mysqli(SQL_HOST,SQL_USER,SQL_PASS,SQL_DB);
$sql = "SELECT ebay_shipping_description, ebay_shipping_id FROM ebay_shipping_tbl";
$result = $mysqli->query($sql);
$opt = '';
while($row = $result->fetch_assoc()){
	$opt .= '<option value="'.$row['ebay_shipping_id'].'">'.$row['ebay_shipping_description'].'</option>';	
}
$smarty->assign('shipping_options',$opt);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/new_shipping_templates.tpl');
?>