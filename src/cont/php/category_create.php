<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"category_create");
//create a new category
function category_create($formValues){
	$resp = new xajaxResponse();
		//instantiate a new employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store = new Store($employee->storeID);
		//instantiate a new category with the form values
		$category = new Category(0, $formValues['cat_name'], $formValues['ebay_cat'], $formValues['ebay_store_cat']);
		//add the new category
		$added = $category->addCategory($employee->storeID);
		//if success
		if($added){
			//reload the page
			$resp->redirect('./categories.php');
		}
	return $resp;
}
?>