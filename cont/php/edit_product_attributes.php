<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"edit_product_attributes");
//update a products ebay attributes
function edit_product_attributes($formValues){
	$resp = new xajaxResponse();
		//get product id from form values
		$product_id = $formValues['product_id'];
		//get ebay attributes
		$ebay_attributes = array();
		if(isset($formValues['attribute_name'])){
			$i = 0;
			foreach($formValues['attribute_name'] as $name)
			{
				$ebay_attributes[$name] = $formValues['attribute_value'][$i];
				//$type[$i] = $formValues['attribute_type'];
				$i++;
			}
		}
		//setup employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store = new Store($employee->storeID);
		//instantiate a new product	
		$product = new Product($store_id);
		//set its attributes and id
		$product->setEbayAttributes($ebay_attributes);
		$product->setProductID($product_id);
		//update the product attributes
		$product->updateProductAttributes();
		//reload the page
		$resp->redirect('./edit_product.php?product_id='.$product_id);
	return $resp;
}
?>