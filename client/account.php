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
include('../cont/php/client_update.php');

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Client Dashboard');

//assign active menu item
$smarty->assign('accountActive','active');



//assign page
$smarty->assign('page','Client Account Settings');

//get user details


$smarty->assign('user_id',$employee->employeeID);
$smarty->assign('fname',$employee->forename);
$smarty->assign('sname',$employee->surname);
$smarty->assign('email',$employee->email);


//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/account.tpl');
?>