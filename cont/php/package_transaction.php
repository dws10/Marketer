<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"package_transaction");
function package_transaction($formValues){
	$resp = new xajaxResponse();
		//instantiate a new employee
		$employee = new Employee();
		//authenticate the employee session
		$employee->authenticateSession();
		//instantiate a new store with the employee store id
		$store = new Store($employee->storeID);
		//get form values
		$order_id = $formValues['orderID'];
		$transactionID = $formValues['transactionID'];
		//get the order from the store by order id
		$order = $store->getOrder($order_id);
		//get the transaction from the store by transaction id
		$transaction = $order->getTransaction($transactionID);
		//set to packaged
		$transaction->markAsPackaged();
		//assign to DOM
		$resp->assign($transactionID.'_status', 'innerHTML', $transaction->status);
		$resp->assign($transactionID.'_btn', 'innerHTML', 'Unpackage');
		$resp->assign($transactionID.'_btn', 'className', 'btn btn-danger btn-block unpackage-btn');
	return $resp;
}
?>