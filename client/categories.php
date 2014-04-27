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
include('../cont/php/category_create.php');
include('../cont/php/category_update.php');

//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript());

//assign title
$smarty->assign('title','Client Dashboard');

//assign active menu item
$smarty->assign('inventoryActive','active');

//assign page
$smarty->assign('page','Client Dashboard');

//get top level categories
$top_categories = $store->getEbayTopCategories();
$top_cat_opt = '';
foreach($top_categories as $ebay_id => $name){
	$top_cat_opt .= '<option value="'.$ebay_id.'">'.$name.'</option>';
}
$smarty->assign('top_level_category_options',$top_cat_opt);

//get seller store categories

$store_categories = $store->getSellerEbayStoreCategories();
$store_cat_opt = '';
foreach($store_categories as $store_cat_id => $name){
	$store_cat_opt .= '<option value="'.$store_cat_id.'">'.$name.'</option>';
}
$smarty->assign('store_categories',$store_cat_opt);


//get exisitng categories
$categories = $store->getCategories();
$existing_cats = '';
if($categories != 'Error: No Results'){
	foreach($categories as $category){
	
		
		 $ebay_category = $category->getEbayCategory();
		
		 $existing_cats .= '<li class="list-group-item">';
			$existing_cats .= '<div class="btn-toolbar" role="toolbar">';
				$existing_cats .= '<form id="category_'.$category->category_id.'">';
					$existing_cats .= '<div class="col-md-3">';
						$existing_cats .= '<div class="form-group">';
							$existing_cats .= '<input type="hidden" name="cat_id" value="'.$category->category_id.'"/>';
							$existing_cats .= '<input type="text" class="form-control" name="cat_name" value="'.$category->category_name.'"/>';
						$existing_cats .= '</div>';
					$existing_cats .= '</div>';
					$existing_cats .= '<div class="col-md-4">';
						$existing_cats .= '<div class="form-group ebay_category_select_holder">';
							$existing_cats .= '<input type="hidden" name="ebay_cat" value="'.$ebay_category->id.'"/>';
							
							//get ebay category list for respective category and its parents
							$level = $ebay_category->level;
							$category_list = array();
							$category_list[] = $ebay_category;
							for($i = 1; $i < $level; $i++){
								$category_list[] = $ebay_category->returnParent();
								$ebay_category = $ebay_category->returnParent();
							}
							
							//reverse the list
							$category_list = array_reverse($category_list);
							
							
							//draw the top level select box
							$existing_cats .= '<select class="form-control ebay_category_select">';
							
							$top_cat_opt = '';
							foreach($top_categories as $ebay_id => $name){
								
								if($category_list[0]->id == $ebay_id){
									$existing_cats .= '<option value="'.$ebay_id.'" selected>'.$name.'</option>';
								}else{
									$existing_cats .= '<option value="'.$ebay_id.'">'.$name.'</option>';
								}
							}
							
							$existing_cats .= '</select>';
							
							//draw the child select boxes
							$category_list = array_splice($category_list, 1);
							foreach($category_list as $ebay_cat){
							
								$existing_cats .= '<select class="form-control ebay_category_select">';
	
								$children = $ebay_cat->returnParent()->getChildren();
								foreach($children as $id => $name){
									if($ebay_cat->returnParent()->id == $id){
										//do nothing
									}else if($ebay_cat->id == $id){
										$existing_cats .= '<option value="'.$id.'" selected>'.$name.'</option>';	
									}else{
										$existing_cats .= '<option value="'.$id.'">'.$name.'</option>';
									}
								}
								
								$existing_cats .= '</select>';
							
							}
							
							$existing_cats .= '</div>';
						
						$existing_cats .= '</div>';
						$existing_cats .= '<div class="col-md-4">';
							$existing_cats .= '<div class="form-group">';
								$existing_cats .= '<select class="form-control ebay_category_select" name="ebay_store_cat">';
									$existing_cats .= '<option value="" disabled selected>Select An eBay Store Category</option>';
										
										//$store_cats = $store->getSellerEbayStoreCategories();
										foreach($store_categories as $id => $name){
											if($category->category_ebay_store_id == $id){
												$existing_cats .= '<option value="'.$id.'" selected>'.$name.'</option>';
											}else{
												$existing_cats .= '<option value="'.$id.'">'.$name.'</option>';
											}
										}
										
								$existing_cats .= '</select>';
							$existing_cats .= '</div>';
						$existing_cats .= '</div>';
						$existing_cats .= '<div class="col-md-1">';
							$existing_cats .= '<div class="btn-group btn-group-sm pull-right">';
								$existing_cats .= '<button type="button" class="btn btn-default btn-block save_cat">Save</button>';
								$existing_cats .= '<button type="button" class="btn btn-default btn-block remove_cat">Remove</button>';
							$existing_cats .= '</div>';
						$existing_cats .= '</div>';
					$existing_cats .= '</form>';
				$existing_cats .= '</div>';
			$existing_cats .= '</li>';
	}
}
$smarty->assign('existing_cats',$existing_cats);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/categories.tpl');
?>