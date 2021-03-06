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
$smarty->assign('dashboardActive','active');

//assign username to welcome message
$smarty->assign('username',$employee->forename.' '.$employee->surname);

//assign page
$smarty->assign('page','Client Dashboard');

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/dashboard.tpl');
?>