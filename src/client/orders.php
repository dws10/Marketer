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

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Client Store - Orders & Shipping');

//assign active menu item
$smarty->assign('shippingActive','active');

$store->getNewOrders();

$order_row = '';

$orders = $store->getOrders();
foreach($orders as $order){
	$order_row .= '<div class="row">';
		$order_row .= '<div class="col-md-3">';
			$order_row .= $order->external_id.'<br/>';
			$order_row .= $order->purchase_date;
		$order_row .= '</div>';
		$order_row .= '<div class="col-md-3">';
			$order_row .= $order->shipping['name'].'<br/>';
			$order_row .= $order->shipping['phone'].'<br/>';
		$order_row .= '</div>';
		$order_row .= '<div class="col-md-3">';
			$order_row .= $order->shipping['line_one'].'<br/>';
			$order_row .= $order->shipping['city'].'<br/>';
			$order_row .= $order->shipping['county'].'<br/>';
			$order_row .= $order->shipping['postcode'].'<br/>';
		$order_row .= '</div>';
		$order_row .= '<div class="col-md-1">';
			$order_row .= $order->status;
		$order_row .= '</div>';
		$order_row .= '<div class="col-md-2">';
			$order_row .= '<a href="./view_order.php?order_id='.$order->order_id.'" class="btn btn-primary btn-block">View Order</a>';
		$order_row .= '</div>';
	$order_row .= '</div>';
}

$smarty->assign('order_row',$order_row);



//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/orders.tpl');
?>