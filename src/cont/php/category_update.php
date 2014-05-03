<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"category_update");
//update a category
function category_update($formValues){
	$resp = new xajaxResponse();
		//setup employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store = new Store($employee->storeID);
		//instantiate a new category with the form values			
		$category = new Category($formValues['cat_id'], $formValues['cat_name'], $formValues['ebay_cat'], $formValues['ebay_store_cat']);
		//update the category
		$category->updateCategory();
		//reload the page
		$resp->redirect('./categories.php');
	return $resp;
}
?>