<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"add_product");
function add_product($formValues){
	$resp = new xajaxResponse();
		//get the form values
		$category_id = $formValues['category'];
		$title = $formValues['title'];
		$description = $formValues['description'];
		//build the error container
		$error_top = '<div class="alert alert-danger alert-dismissable navbar-right">';
		$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
		$error_bot = '</div>';
		//include validator and instantiate it
		include('../class/Validator.php');
		$validate = new Validation();
		//set validity flag as valid
		$flag = true;
		//validate category
		if($validate->equals($category_id, 0)){
			//assign error
			$error_msg = 'Please select a category for your product';
			$resp->assign('category-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate title
		if($validate->equals($title, '')){
			//assign error
			$error_msg = 'Please give your product a title';
			$resp->assign('title-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate description
		if($validate->equals($description,'')){
			//assign error
			$error_msg = 'Please give your product a description';
			$resp->assign('description-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//get ebay attributes by looping the form values
		$ebay_attributes = array();
		$type = array();
		if(isset($formValues['attribute_name'])){
			$i = 0;
			foreach($formValues['attribute_name'] as $name)
			{
				$ebay_attributes[$name] = $formValues['attribute_value'][$i];
				$i++;
			}
		}
		//if valid
		if($flag){
			//setup employee
			$employee = new Employee();
			//authenticate employee from session
			$employee->authenticateSession();
			//setup store with employee id
			$store = new Store($employee->storeID);
			//instantiate a new product
			$product = new Product($store_id);
			//set the products attributes
			$product->setProductGeneral($title, $description, $category_id);
			$product->setEbayAttributes($ebay_attributes);
			//add the product and get the id
			$product_id = $product->addProduct();
			//redirect to the product edit page
			$resp->redirect('./edit_product.php?product_id='.$product_id);
		}
	return $resp;
}
?>