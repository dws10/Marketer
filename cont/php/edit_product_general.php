<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"edit_product_general");
//update a products general attributes
function edit_product_general($formValues){
	$resp = new xajaxResponse();
		//get form values
		$product_id = $formValues['product_id'];
		$category_id = $formValues['category'];
		$title = $formValues['title'];
		$description = $formValues['description'];
		//build error container
		$error_top = '<div class="alert alert-danger alert-dismissable navbar-right">';
		$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
		$error_bot = '</div>';
		//include the validator
		include('../class/Validator.php');
		$validate = new Validation();
		//set validity flag to valid
		$flag = true;
		//validate category (required)
		if($validate->equals($category_id, 0)){
			//assign error
			$error_msg = 'Please select a category for your product';
			$resp->assign('category-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate title (required)
		if($validate->equals($title, '')){
			//assign error
			$error_msg = 'Please give your product a title';
			$resp->assign('title-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate description(required)
		if($validate->equals($description,'')){
			//assign error
			$error_msg = 'Please give your product a description';
			$resp->assign('description-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//if flag is valid
		if($flag){
			//setup employee
			$employee = new Employee();
			//authenticate employee from session
			$employee->authenticateSession();
			//setup store with employee id
			$store = new Store($employee->storeID);
			//instantiate a new product
			$product = new Product($store_id);
			//setup its general attributes and id
			$product->setProductGeneral($title, $description, $category_id);
			$product->setProductID($product_id);
			//update the general attributes
			$product->updateProductGeneral();
			//reload the page
			$resp->redirect('./edit_product.php?product_id='.$product_id);
		}
	return $resp;
}
?>