<?php
//register xajax function
$xajax->register(XAJAX_FUNCTION,"unpackage_transaction");
function unpackage_transaction($formValues){
	$resp = new xajaxResponse();
		//instantiate a new employee
		$employee = new Employee();
		//authenticate the session retrieving the employee data 
		$employee->authenticateSession();
		//get their store id		
		$store_id = $employee->storeID;
		//instantiate a new store		
		$store = new Store($store_id);
		//get the order id from the form
		$order_id = $formValues['orderID'];
		//use order id to retrieve the order from the store
		$order = $store->getOrder($order_id);
		//get the transaction id from the store
		$transactionID = $formValues['transactionID'];
		//use the id to get the transaction
		$transaction = $order->getTransaction($transactionID);
		//mark transaction as unpackaged
		$transaction->unpackage();
		//update the DOM
		$resp->assign($transactionID.'_status', 'innerHTML', $transaction->status);
		$resp->assign($transactionID.'_btn', 'innerHTML', 'Package');
		$resp->assign($transactionID.'_btn', 'className', 'btn btn-primary btn-block package-btn');
	return $resp;
}
?>