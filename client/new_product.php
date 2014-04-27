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
include('../cont/php/product_create.php');
include('../cont/php/get_ebay_recommended.php');
include('../cont/php/add_new_text_attribute.php');
include('../cont/php/add_new_select_attribute.php');

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Add Product');

//assign active menu item
$smarty->assign('productsActive','active');

//assign page
$smarty->assign('page','Add Products');

//get product categories

$store_id = $store->store_id;

$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);

$sql = "SELECT category_id, category_name FROM category_tbl WHERE category_store_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i',$store_id);

$opt = '';

if (!$stmt->execute()) {
	//if failure echo error
	echo "Failed to update category parentID: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
}else{
	$stmt->store_result();
	
	$stmt->bind_result($category_id, $category_name);
			
	if($stmt->num_rows > 0){
		$opt .= "<option selected disabled value='0'>Select a product category</option>";
		while($stmt->fetch()){
			$opt .= "<option value='".$category_id."'>".$category_name."</option>"	;
		}
	}else{
		$opt .= "<option selected disabled>No categories found please add categories</option>";
	}
}

$smarty->assign('categories',$opt);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/new_product.tpl');
?>