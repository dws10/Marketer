<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"shipping_template_create");
function shipping_template_create($formValues){
	$resp = new xajaxResponse();
	//instantiate a new employee
	$employee = new Employee();
	//authenticate the session retrieving the employee data 
	$employee->authenticateSession();
	//get their store id		
	$store_id = $employee->storeID;
	//instantiate a new store		
	$store = new Store($store_id);
	//get the form values
	$title = $formValues['title'];
	$shipping_id = $formValues['shipping_type'];
	$dispatch_time = $formValues['shipping_dispatch_time'];
	$cost = $formValues['shipping_cost'];
	$additional_cost = $formValues['shipping_additional_cost'];
	//instantiate a new shipping template
	$shipping_template = new ShippingTemplate($title, $shipping_id, $dispatch_time, $cost, $additional_cost);
	//save the template
	$shipping_template->saveTemplate($store_id);
	//reload the page
	$resp->redirect('./shipping_templates.php');
	return $resp;
}
?>