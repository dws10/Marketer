<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"mark_as_dispatched");
function mark_as_dispatched($formValues){
	$resp = new xajaxResponse();
		//setup employee
		$employee = new Employee();
		//authenticate employee from session
		$employee->authenticateSession();
		//setup store with employee id
		$store = new Store($employee->storeID);
		//get order id from form
		$order_id = $formValues['order_id'];
		//get the order from store by id
		$order = $store->getOrder($order_id);
		//clear errors
		$resp->assign('dispatch-error', 'innerHTML', '');
		//set validity flag to true
		$valid = true;
		//number of unpackaged transactions
		$count = 0;
		$transactions = $order->transactions;
		foreach($transactions as $transaction){	
			if($transaction->status != 'Packaged'){
				//if not packaged you cannot dispatch an order
				$valid = false;
				$count++;	
			}
		}
		//if the action is invalid
		if(!$valid){
			//build the error
			$error_top = '<div class="alert alert-danger alert-dismissable navbar-right register-error">';
			$error_top .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			$error_top .= '<span class="glyphicon glyphicon-chevron-up"></span>';
			$message = "There are currently ".$count." Unpackaged items, please package these first.";
			$error_bot = '</div>';
			//assign the error
			$resp->assign('dispatch-error', 'innerHTML', $error_top.$message.$error_bot);
		}else{
			//if the action is valid mark order as dispatched
			$order->markAsDispatched();
			//update the orders status and disable to button
			$resp->assign('order-status', 'innerHTML', 'Dispatched');
			$resp->assign('dispatch-btn', 'innerHTML', 'Dispatched');
			$resp->assign('dispatch-btn', 'disabled', true);
		}
	return $resp;
}
?>