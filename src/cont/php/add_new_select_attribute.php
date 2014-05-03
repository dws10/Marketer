<?php 
//register xajax function
$xajax->register(XAJAX_FUNCTION,"add_new_select_attribute");
//add a new select attribute to the product attributes section
function add_new_select_attribute($name, $opts){
	$resp = new xajaxResponse();
		//characters to remove
	  	$remove = array(" ","/","'","_");
	  	//get the id from the name
	  	$id_name = str_replace($remove, '', $name);
  		//build the select element container
	  	$select = '<li class="list-group-item">';
			$select .= '<div class="form-group">';
				$select .= '<div class="input-group">';
					//set the attribute name and type
					$select .= '<input type="hidden" name="attribute_name[]" autocomplete="off" value="'.$name.'"/>';
				  	$select .= '<input type="hidden" name="attribute_type[]" autocomplete="off" value="select"/>';
				  	$select .= '<label class="input-group-addon-sm input-group-addon">';
						//create the input label
						$select .= '<span class="glyphicon glyphicon-sort"></span>'.$name;
				 	$select .= '</label>';
					//make the select
				  	$select .= '<select class="form-control" name="attribute_value[]">';
					  	$select .= '<option value="" selected disabled>Select An Option</option>';
						 foreach ($opts as $value){
							 //assign options to the select
						 	$select .= '<option value="'.$value.'">'.$value.'</option>';	
						 }
				  $select .= '</select>';
				$select .= '</div>';
			$select .= '</div>';
		$select .= '</li>';
	  	//append the select to the attribute list
		$resp->append('attribute-list','innerHTML',$select);
	  	//set articles to be the same height incase expands
		$resp->script("sameHeightByClass('top-articles');");
		//assign button colour and disable it
		$resp->assign($id_name.'_btn', 'className', 'btn btn-success btn-block');
		$resp->assign($id_name.'_btn', 'disabled', true);
	return $resp;
}
?>