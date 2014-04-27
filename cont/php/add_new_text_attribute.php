<?php 
//register the xajax function
$xajax->register(XAJAX_FUNCTION,"add_new_text_attribute");
//adds a new text attribute to the ebay attribute list
function add_new_text_attribute($name){
	$resp = new xajaxResponse();
		//characters to remove
		$remove = array(" ","/","'","_");
		//id name from stripping non safe chars
		$id_name = str_replace($remove, '', $name);
		//build the button container
		$input = '<li class="list-group-item">';
			$input .= '<div class="form-group">';
				$input .= '<div class="input-group">';
					//set the attribute name input
					$input .= '<input type="hidden" name="attribute_name[]" autocomplete="off" value="'.$name.'"/>';
					$input .= '<input type="hidden" name="attribute_type[]" autocomplete="off" value="text"/>';
					$input .= '<label class="input-group-addon-sm input-group-addon">';
						//write the input label
						$input .= '<span class="glyphicon glyphicon-sort"></span>'.$name;
					$input .= '</label>';
					$input .= '<input type="text" class="form-control" placeholder="Enter '.$name.'" name="attribute_value[]" autocomplete="off">';
				$input .= '</div>';
			$input .= '</div>';
		$input .= '</li>';
		//append the input to the attribute list
		$resp->append('attribute-list','innerHTML',$input);
		//set the same height incase they change
		$resp->script("sameHeightByClass('top-articles');");
		//update the button with new colour and set to disabled 
		$resp->assign($id_name.'_btn', 'className', 'btn btn-success btn-block');
		$resp->assign($id_name.'_btn', 'disabled', true);
	return $resp;
}
?>