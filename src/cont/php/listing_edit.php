<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"listing_edit");
function listing_edit($formValues){
	$resp = new xajaxResponse();
		//clear error messages
		$resp->assign('product-error','innerHTML','');
		$resp->assign('condition-error','innerHTML','');
		$resp->assign('price-error','innerHTML','');
		$resp->assign('duration-error','innerHTML','');
		$resp->assign('shipping-error','innerHTML','');
		$resp->assign('returns-error','innerHTML','');
		$resp->assign('display-error','innerHTML','');
		//setup error HTML before error message
		$error_top = '<div class="alert alert-danger alert-dismissable navbar-right">';
		$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
		//setup error HTML after error message
		$error_bot = '</div>';
		//include the validator and instantiate it
		include('../class/Validator.php');
		$validate = new Validation();
		//set validity flag
		$flag = true;
		//get the listing id
		$listing_id = $formValues['listing_id'];
		//validate product id
		$product_id = $formValues['product'];
		if($validate->equals($product_id, 0)){
			//assign error
			$error_msg = 'Please select a product';
			$resp->assign('product-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate condition id
		$condition_id = $formValues['condition_name'];
		if($validate->equals($condition_id, 0)){
			//assign error
			$error_msg = 'Please select a condition';
			$resp->assign('condition-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		$condition_details = $formValues['condition_details'];
		//validate product price (required)
		$product_price = $formValues['price'];
		if($validate->equals($product_price, '')){
			//assign error
			$error_msg = 'Please specify a price in GBP';
			$resp->assign('price-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}else{
			//validate formatting
			$price = explode('.', $product_price);
			$pounds = $price[0];
			$pence = $price[1];
			if($validate->length_less($pounds, 1) && $validate->length_less($pence, 2) && $validate->numeric($pounds) && $validate->numeric($pence)){
				//assign error
				$error_msg = 'Invalid price format, please check the product price';
				$resp->assign('price-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;	
			}
		}
		//validate quantity
		$quantity = $formValues['quantity'];
		if($validate->equals($quantity, '')){
			//assign error
			$error_msg = 'Please provide a quantity';
			$resp->assign('quantity-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}else{
			if(!$validate->numeric($quantity)){
				//assign error
				$error_msg = 'Quantity must be a numeric value';
				$resp->assign('quantity-error','innerHTML',$error_top.$error_msg.$error_bot);
				$flag = false;	
			}
		}
		//validate duration (required)
		$duration = $formValues['duration'];
		if($validate->length_equals($duration, 1)){
			//assign error
			$error_msg = 'Please select a listing duration';
			$resp->assign('duration-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate shipping template (required)
		$shipping_template_id = $formValues['shipping_template'];
		if($validate->equals($shipping_template_id, 0)){
			//assign error
			$error_msg = 'Please select a shipping template';
			$resp->assign('shipping-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate return template (required)
		$return_template_id = $formValues['return_template'];
		if($validate->equals($return_template_id, 0)){
			//assign error
			$error_msg = 'Please select a return template';
			$resp->assign('returns-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//validate display template (required)
		$display_template_id = $formValues['display_template'];
		if($validate->equals($display_template_id, 0)){
			//assign error
			$error_msg = 'Please select a display template';
			$resp->assign('display-error','innerHTML',$error_top.$error_msg.$error_bot);
			$flag = false;	
		}
		//if validity flag is valid
		if($flag){
			//setup employee
			$employee = new Employee();
			//authenticate employee from session
			$employee->authenticateSession();
			//setup store with employee id
			$store = new Store($employee->storeID);
			//get thhe product from the store
			$product = $store->getProduct($product_id);
			//get thr shipping template from the store
			$shippingTemplate = $store->getShippingTemplate($shipping_template_id);
			//get the returns template from the store
			$returnTemplate = $store->getReturnTemplate($return_template_id);
			//instantiate a new listing	
			$listing = new Listing($store, $product, $condition_id, $condition_details, $product_price, $quantity, $duration, $shippingTemplate, $returnTemplate, $display_template_id);
			//set listing id as it already exists
			$listing->setListingID($listing_id);
			//and update the listing
			$listing->updateListing();
			//redirect to the listings page
			$resp->redirect('./listings.php');
		}
	return $resp;
}
?>