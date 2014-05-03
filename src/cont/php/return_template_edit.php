<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"return_template_edit");
function return_template_edit($formValues){
	$resp = new xajaxResponse();
	//get the values from the form
	$template_id = $formValues['template-id'];
	$title = $formValues['template-title'];
	$duration = $formValues['return-duration'];
	$policy = $formValues['return-policy'];
	//instantiate a new return template
	$return_template = new ReturnTemplate($title, $duration, $policy);
	//update the return template
	$return_template->updateTemplate($template_id);
	//reload the page
	$resp->redirect('./return_templates.php');
	return $resp;
}
?>