// JavaScript Document

$(document).ready(function(){
	$('.register-btn').click(function(){
		xajax_client_register(xajax.getFormValues('register'));
		return false;
	});
	//crafty clicks
	var cp_access_token = "XXXXX-XXXXX-XXXXX-XXXXX"; // ***** DON'T FORGET TO PUT YOUR ACCESS TOKEN HERE IN PLACE OF X's !!!! *****  	 
	
	var cp_obj_1 = CraftyPostcodeCreate();
	cp_obj_1.set("access_token", cp_access_token); 
	cp_obj_1.set("first_res_line", "Select Business Address"); 
	cp_obj_1.set("res_autoselect", "0");
	cp_obj_1.set("result_elem_id", "postcode-results");
	cp_obj_1.set("form", "register"); 
	
	// note this is the same as cp_obj_2
	// note the lines below are different to cp_obj_2
	//cp_obj_1.set("elem_company"  , "seller_name"); // optional
	cp_obj_1.set("elem_postcode" , "address_postcode");
	cp_obj_1.set("elem_house_num"  , "address_building");
	cp_obj_1.set("elem_street1"  , "address_road1");
	cp_obj_1.set("elem_street2"  , "address_road2"); // optional, but highly recommended
	cp_obj_1.set("elem_town"     , "address_town");
	cp_obj_1.set("elem_county"   , "address_county"); // optional
	cp_obj_1.set("single_res_autoselect" , 1); // don't show a drop down box if only one matching address is found  
	cp_obj_1.set("hide_result" , 1);
	cp_obj_1.set("max_width" , '100%');
	
	
	$('.postcode-lookup').click(function(){
		$('.address-value').val('');
		cp_obj_1.doLookup();
		return false;
	});
	
});