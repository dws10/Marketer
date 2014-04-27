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
$smarty->assign('templatesActive','active');

$return_templates = $store->getReturnTemplates();

$template_row = '';

if(count($return_templates) > 0){
	foreach($return_templates as $id => $template){
		$duration = explode('_' , $template->duration);
		$duration = $duration[1]. ' ' .$duration[0];
		$template_row .= '<div class="row">';
			$template_row .= '<div class="col-md-1">1</div>';
			$template_row .= '<div class="col-md-2">'.$template->title.'</div>';
			$template_row .= '<div class="col-md-2">'.$duration.'</div>';
			$template_row .= '<div class="col-md-4">'.$template->description.'</div>';
			$template_row .= '<div class="col-md-1"><a href="edit_return_template.php?id='.$id.'" class="btn btn-primary">Edit</a></div>';
			$template_row .= '<div class="col-md-1"><a href="#" class="btn btn-danger">Delete</a></div>';
		$template_row .= '</div>';
	}
}else{
	$template_row .= '<div class="row">';
		$template_row .= '<div class="col-md-12">No Templates Found</div>';
	$template_row .= '</div>';
}
$smarty->assign('return_templates',$template_row);


//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/return_templates.tpl');
?>