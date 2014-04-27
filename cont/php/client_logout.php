<?php 
//register xajax function
	$xajax->register(XAJAX_FUNCTION,"client_logout");
	//log out of the system
	function client_logout(){
		$resp = new xajaxResponse();
			//instantiate employee
			$employee = new Employee();
			//logout
			$employee->logout();
			//redirect to public area
			$resp->redirect('../');
		return $resp;
	}
?>