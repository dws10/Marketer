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
include('./cont/php/client_login.php');

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Smarty is working');

//assign active menu item
$smarty->assign('homeActive','active');

//assign page
$smarty->assign('page','Smarty is working');

//display the page
$smarty->display('extends:main.tpl|./nav/public.tpl|./banners/main.tpl|./public_content/home.tpl');
?>