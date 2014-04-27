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
include('../cont/php/get_ebay_recommended.php');
include('../cont/php/add_new_text_attribute.php');
include('../cont/php/add_new_select_attribute.php');


//include('../php/add_product_image.php');
include('../cont/php/edit_product_general.php');
include('../cont/php/edit_product_attributes.php');
include('../cont/php/update_image_order.php');
include('../cont/php/delete_image.php');


//process the xajax requests
$xajax->processRequest();

//assign xajax generated js to the page
$smarty->assign('xajax_js',$xajax->getJavascript('xajax'));

//assign title
$smarty->assign('title','Edit Product');

//assign active menu item
$smarty->assign('productsActive','active');

//assign page
$smarty->assign('page','Edit Products');

$product_id = $_GET['product_id'];
$smarty->assign('product_id',$product_id);

$product = array();
$product = $store->getProduct($product_id);

$smarty->assign('product_title',$product->title);
$smarty->assign('product_description',$product->description);

$categories = $store->getCategories();
$opt = '';
foreach ($categories as $category){
	if($category->category_id == $product->category_id){
		$opt .= "<option value='".$category->category_id."' selected>".$category->category_name."</option>";
	}else{
		$opt .= "<option value='".$category->category_id."'>".$category->category_name."</option>";
	}	
}
$smarty->assign('categories',$opt);

//instantiate ebay gateway
$gateway = $store->ebay_gateway;

//provide api action
$gateway->SetAction('GetCategorySpecifics');

//open new ebay session
$session = $gateway->OpenSession();
$input = '';
$load_attributes = array();
foreach($product->ebay_attributes as $key => $specific){
	
	$name = $key;
	$value = $specific['value'];
	$type = $specific['type'];
	
	$remove = array(" ","/","'","_");
	$load_attributes[] = str_replace($remove, '', $name).'_btn';
	
	if($specific['type'] == 'select'){
		
		$input .= '<li class="list-group-item">';
			$input .= '<div class="form-group">';
				$input .= '<div class="input-group">';
				  $input .= '<input type="hidden" name="attribute_name[]" autocomplete="off" value="'.$name.'"/>';
				  $input .= '<input type="hidden" name="attribute_type[]" autocomplete="off" value="select"/>';
				  $input .= '<label class="input-group-addon-sm input-group-addon">';
					$input .= '<span class="glyphicon glyphicon-sort"></span>'.$name;
				  $input .= '</label>';
				  $input .= '<select class="form-control" name="attribute_value[]">';
					  
					$opts = '<option value="" selected disabled>Select An Option</option>';
					
					//instantiate a new get category request
					$xmlRequest = new getCategorySpecifics($store->auth_token);
					//send the request and recieve the response			
					$response = $session->sendHttpRequest($xmlRequest->getCategorySpecific($category->ebay_category->real_id, $name));
					//load the xml response
					$xmlResponse = simplexml_load_string($response);
					
					if($xmlResponse->Ack == 'Success'){
						foreach($xmlResponse->Recommendations->NameRecommendation as $NameRecommendation)
						{
							$option = array();
							foreach($NameRecommendation->ValueRecommendation as $ValueRecommendation){
								$opts .= '<option value="'.(string) $ValueRecommendation->Value.'">'.(string) $ValueRecommendation->Value.'</option>';	
							}
						}
						$input .= $opts;
					}

				  $input .= '</select>';
				$input .= '</div>';
			$input .= '</div>';
		$input .= '</li>';

	}else{
		$input .= '<li class="list-group-item">';
			$input .= '<div class="form-group">';
				$input .= '<div class="input-group">';
					 $input .= '<input type="hidden" name="attribute_name[]" value="'.$name.'"/>';
					 $input .= '<input type="hidden" name="attribute_type[]" value="text"/>';
					 $input .= '<label class="input-group-addon-sm input-group-addon">';
						$input .= '<span class="glyphicon glyphicon-sort"></span>'.$name;
					 $input .= '</label>';
					 $input .= '<input type="text" class="form-control" placeholder="Enter '.$name.'" name="attribute_value[]" value="'.$specific['value'].'">';
				$input .= '</div>';
			$input .= '</div>';
		$input .= '</li>';
	}	
}
$smarty->assign('attribute_inputs',$input);

//get category specifics buttons
//instantiate a new get category request
$xmlRequest = new GetCategorySpecifics($store->auth_token);

//send the request and recieve the response			
$response = $session->sendHttpRequest($xmlRequest->getCategorySpecifics($category->ebay_category->real_id));

//load the xml response
$xmlResponse = simplexml_load_string($response);
$btns = ''; 
$script = '';
	
if($xmlResponse->Ack == 'Success'){
	foreach($xmlResponse->Recommendations->NameRecommendation as $NameRecommendation)
	{
		$Name = (string) $NameRecommendation->Name;
		$SelectMode = (string) $NameRecommendation->ValidationRules->SelectionMode;
		
		//if it is a select based option
		if($SelectMode == 'SelectionOnly')
		{
			$option = array();
			foreach($NameRecommendation->ValueRecommendation as $ValueRecommendation){
				$option[] = (string) $ValueRecommendation->Value;
			}
			$opt = json_encode($option, JSON_HEX_APOS | JSON_HEX_QUOT);
			
			//$resp->alert($opt);
			$remove = array(" ","/","'","_");
			$id_name = str_replace($remove, '', $Name);

			$btns .= '<li class="list-group-item">';
		
			if(in_array($id_name.'_btn', $load_attributes)){
				$btns .= "<button id='".$id_name."_btn' class='btn btn-success btn-block' disabled>".$Name."</button>";
			}else{
				$btns .= "<button id='".$id_name."_btn' class='btn btn-primary btn-block' >".$Name."</button>";
			}
			
			
			$btns .= '</li>';
			
			$script .= "$('#".$id_name."_btn').click(function(){xajax_add_new_select_attribute(\"".$Name."\", ".$opt.");return false;});";
			//;
			
		}else{ //or an open text option
			$remove = array(" ","/","'","_");
			$id_name = str_replace($remove, '', $Name);
			
			$btns .= '<li class="list-group-item">';
			if(in_array($id_name.'_btn', $load_attributes)){
				$btns .= "<button id='".$id_name."_btn' class='btn btn-success btn-block' disabled>".$Name."</button>";
			}else{
				$btns .= "<button id='".$id_name."_btn' class='btn btn-primary btn-block' >".$Name."</button>";
			}
			$btns .= '</li>';
			
			$script .= "$('#".$id_name."_btn').click(function(){xajax_add_new_text_attribute(\"".$Name."\");return false;});";

		}
	}
	$scriptHTML = '<script type="text/javascript">';
	$scriptHTML .= $script;
	$scriptHTML .= '</script>';

	$smarty->assign('specific_btns',$btns);
	$smarty->assign('specific_btns_script',$scriptHTML);
}
$i = 0;
$image = '';
foreach($product->images as $id => $path ){
	$image .= '<li class="list-group-item">';
		$image .= '<form id="image_'.$id.'">';
			$image .= '<div class="row">';
				$image .='<input type="hidden" name="image_id[]" value="'.$id.'" />';
				$image .= '<div class="col-md-1"></div>';
				$image .= '<div class="col-md-3">';
					$image .= '<a href="../images/products/'.$path.'" title="Image_'.$i.'">';
						$image .= '<img src="../images/products/'.$path.'" alt="Image_'.$i.'" class="img-responsive">';
					$image .= '</a>';
				$image .= '</div>';
				$image .= '<div class="col-md-5"></div>';
				$image .= '<div class="col-md-2">';
					$image .= '<button class="btn btn-danger remove_image">Delete</button>';
					$image .= '<img src="../images/loading.gif" height="30" width="30" class="delete_loading"/>';
				$image .= '</div>';
				$image .= '<div class="col-md-1"></div>';
			$image .= '</div>';
		$image .= '</form>';
	$image .= '</li>';
	$i++;
}
$smarty->assign('product_images',$image);

//display the page
$smarty->display('extends:main.tpl|./nav/client.tpl|./client_content/edit_product.tpl');
?>