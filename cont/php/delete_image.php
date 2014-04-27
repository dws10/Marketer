<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"delete_image");
//delete a product image
function delete_image($image_id){
	$resp = new xajaxResponse();
	//setup employee
	$employee = new Employee();
	//authenticate employee from session
	$employee->authenticateSession();
	//setup store with employee id
	$store = new Store($employee->storeID);			
	//instantiate a new product	
	$product = new Product($store_id);
	//delete the products image by its id	
	if ($product->deleteImage($image_id)) {
		//if success build success message
		$message = '<div class="alert alert-success alert-dismissable">';
			$message .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			$message .= 'Image removed!';
		$message .= '</div>';
		sleep(1);
		//assign the message
		$resp->assign('image_order_message','innerHTML',$message);
	}else{
		//if failure build error message
		$message = '<div class="alert alert-danger alert-dismissable">';
			$message .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			$message .= 'Image remove error please contact system operator!';
		$message .= '</div>';
		sleep(1);
		//assign the message
		$resp->assign('image_order_message','innerHTML',$message);
	}
	return $resp;
}
?>