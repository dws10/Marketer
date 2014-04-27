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
$smarty->assign('title','Client Dashboard');

//assign active menu item
$smarty->assign('productsActive','active');

$product_row = '';
$products = $store->getProducts();

foreach($products as $id => $product){
	
	$product_row .= '<div class="row">';
		$product_row .= '<div class="col-md-1">'.$product->product_id.'</div>';
		$product_row .= '<div class="col-md-2">'.$product->getCategoryName().'</div>';
		$product_row .= '<div class="col-md-7">'.$product->title.'</div>';
		$product_row .= '<div class="col-md-1"><a class="btn btn-primary btn-block" href="edit_product.php?product_id='.$id.'">Edit</a></div>';
		$product_row .= '<div class="col-md-1"><button class="btn btn-danger btn-block">Delete</button></div>';
	$product_row .= '</div>';
	
	
}
$smarty->assign('product_row',$product_row);



//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/products.tpl');
?>