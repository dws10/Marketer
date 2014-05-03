<?php 
	//register the xajax function
	$xajax->register(XAJAX_FUNCTION,"update_image_order");
	//updates the order associated with an image
	function update_image_order($image_id, $order){
		$resp = new xajaxResponse();
		//instantiate a new employee
		$employee = new Employee();
		//authenticate the session retrieving the employee data 
		$employee->authenticateSession();
		//get their store id		
		$store_id = $employee->storeID;
		//instantiate a new store		
		$store = new Store($store_id);
		//instantiate a new product		
		$product = new Product($store_id);
		//update the image order by id
		if ($product->updateImageOrder($order, $image_id)) {
			//if the call is successful create success message
			$message = '<div class="alert alert-success alert-dismissable">';
				$message .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$message .= 'Image order has been saved!';
			$message .= '</div>';
			//assign it
			$resp->assign('image_order_message','innerHTML',$message);
		}else{
			//else create error message
			$message = '<div class="alert alert-danger alert-dismissable">';
				$message .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$message .= 'Image order error please contact system operator!';
			$message .= '</div>';
			//assign it
			$resp->assign('image_order_message','innerHTML',$message);
		}
		return $resp;
	}
?>