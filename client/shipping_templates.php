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

$template_row = '';
$templates = $store->getShippingTemplates();
foreach($templates as $id => $template){

	$title = $template->title;
	$dispatch_time = $template->dispatch_time;
	$cost = $template->service_cost;
	$additional_cost = $template->service_additional_cost;
	
	$template_row .= '<div class="row">';
		$template_row .= '<div class="col-md-1">'.$id.'</div>';
		$template_row .= '<div class="col-md-3">'.$title.'</div>';
		$template_row .= '<div class="col-md-2">Dispatched in '.$dispatch_time.' days</div>';
		$template_row .= '<div class="col-md-2">Costing £'.number_format($cost,2).'</div>';
		$template_row .= '<div class="col-md-2">And £'.number_format($additional_cost,2).' per additional item</div>';
		$template_row .= '<div class="col-md-1"><a class="btn btn-primary btn-block" href="edit_shipping_template.php?id='.$id.'">Edit</a></div>';
		$template_row .= '<div class="col-md-1"><button class="btn btn-danger btn-block">Delete</button></div>';
	$template_row .= '</div>';

}
$smarty->assign('shipping_templates',$template_row);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/shipping_templates.tpl');
?>