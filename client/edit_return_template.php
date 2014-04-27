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
include('../cont/php/return_template_edit.php');

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','New Return Template');

//assign active menu item
$smarty->assign('templatesActive','active');

$template_id = $_GET['id'];
$smarty->assign('template_id',$template_id);

$template = $store->getReturnTemplate($template_id);

$title = $template->title;
$smarty->assign('template_title',$title);

$duration = $template->duration;
$duration_values = array('Days_14' ,'Days_30' ,'Days_60' );

$opt = '';
foreach($duration_values as $value){
	$text = explode('_', $value);
	$text = $text[1] . ' ' . $text[0];
	
	if($value == $duration){
		$opt .= '<option value="'.$value.'" selected>'.$text.'</option>';
	}else{
		$opt .= '<option value="'.$value.'">'.$text.'</option>';
	}
}
$smarty->assign('template_duration',$opt);

$policy = $template->description;
$smarty->assign('template_policy',$policy);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/edit_return_template.tpl');


?>