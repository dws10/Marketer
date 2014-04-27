<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"get_product_conditions");
function get_product_conditions($formValues){
	$resp = new xajaxResponse();
	//get product id from the page
	$product_id = $formValues['product'];
	if($product_id != 0){
		//setup employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store = new Store($employee->storeID);
		//get the product
		$product = $store->getProduct($product_id);
		//get products category id
		$category_id = $product->category_id;
		//get category from store with the id
		$category = $store->getCategory($category_id);
		//get the ebay category object from the category
		$ebay_category = $category->ebay_category;
		//get conditions for ebay category
		$ebay_conditions = $ebay_category->getConditionOptions();
		//loop through condition array to setup select options
		$opt = "<option value='0' selected>Please select a condition</option>";
		foreach($ebay_conditions as $id => $name){
			$opt .= "<option value='".$id."'>".$name."</option>";
		}
		//assign to page
		$resp->assign("condition_options", "innerHTML", $opt);
	}
	return $resp;
}
?>