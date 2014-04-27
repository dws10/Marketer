<?php
//include the generic page setup
include('./page.php');

//register page specific xajax functions
$xajax->register(XAJAX_FUNCTION,"test");

//page specific xajax functions
function test(){
	$resp = new xajaxResponse();
		//$resp->alert('test');	
	return $resp;
}

//include shared xajax functions
include('../cont/php/client_logout.php');
include('../cont/php/mark_as_dispatched.php');
include('../cont/php/package_transaction.php');
include('../cont/php/unpackage_transaction.php');
//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Client Store - Order Details');

//assign active menu item
$smarty->assign('shippingActive','active');



$order = $store->getOrder($_GET['order_id']);

$address = $order->shipping['name'].'<br/>';
$address .= $order->shipping['line_one'].'<br/>';
$address .= $order->shipping['city'].'<br/>';
$address .= $order->shipping['county'].'<br/>';
$address .= $order->shipping['postcode'].'<br/>';
$address .= $order->shipping['phone'].'<br/>';
$smarty->assign('shipping_address',$address);
$smarty->assign('order_id',$order->order_id);
$smarty->assign('external_order_id',$order->external_id);

$smarty->assign('purchase_date',$order->purchase_date);
$smarty->assign('order_status',$order->status);

$items = '';
$total = 0;
foreach($order->transactions as $transaction){
	$items .= '<div class="row">';
		$items .= '<div class="col-md-1"></div>';
		$items .= '<div class="col-md-3">';
			$items .= $transaction->listing->product->title;
		$items .= '</div>';
		$items .= '<div class="col-md-1">';
			$items .= '£'.number_format($transaction->listing->price,2);
			$total = $total + $transaction->listing->price;
		$items .= '</div>';
		$items .= '<div class="col-md-3">';
			$items .= $transaction->listing->conditionDetails;
		$items .= '</div>';
		$items .= '<div class="col-md-1" id="'.$transaction->transactionID.'_status">';
			$items .= $transaction->status;
		$items .= '</div>';
		$items .= '<div class="col-md-2">';
			$items .= '<form>';
				$items .= '<input type="hidden" value="'.$order->order_id.'" name="orderID">';
				$items .= '<input type="hidden" value="'.$transaction->transactionID.'" name="transactionID">';
				if($transaction->status != 'Packaged'){
					$items .= '<button class="btn btn-primary btn-block package-btn" id="'.$transaction->transactionID.'_btn">Package</button>';
				}else{
					$items .= '<button class="btn btn-danger btn-block unpackage-btn" id="'.$transaction->transactionID.'_btn">Unpackage</button>';
				}
			$items .= '</form>';
		$items .= '</div>';
	$items .= '</div>';
}
$smarty->assign('order_total_price','£'.number_format($total,2));

$smarty->assign('transaction_items',$items);

//setup dispatch button depending on status
$dispatch_btn = '';
if($order->status == 'Dispatched'){
	$dispatch_btn = '<button id="dispatch-btn" class="btn btn-primary btn-block mark-dispatch-btn" disabled="true">Dispatched</button>';
}else{
	$dispatch_btn = '<button id="dispatch-btn" class="btn btn-primary btn-block mark-dispatch-btn">Mark as Dispatched</button>';
}
$smarty->assign('dispatch_btn',$dispatch_btn);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/view_order.tpl');
?>