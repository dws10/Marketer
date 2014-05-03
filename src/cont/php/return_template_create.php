<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"return_template_create");
function return_template_create($formValues){
	$resp = new xajaxResponse();
	//instantiate a new employee
	$employee = new Employee();
	//authenticate the session retrieving the employee data 
	$employee->authenticateSession();
	//get their store id		
	$store_id = $employee->storeID;
	//get the form values
	$title = $formValues['template-title'];
	$duration = $formValues['return-duration'];
	$policy = $formValues['return-policy'];
	//instantiate a new return template
	$return_template = new ReturnTemplate($title, $duration, $policy);
	//save the template
	$return_template->saveTemplate($store_id);
	//reload the page
	$resp->redirect('./return_templates.php');
	return $resp;
}
?>