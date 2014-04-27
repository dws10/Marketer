<?php
//validation class
class Validation{
	//validate argument 1 equals argument 2
	public function equals($arg1, $arg2){
		if($arg1 == $arg2){
			return true;
			
		}else{
			return false;
		}
	}
	//validate argument is alphabetic
	public function alpha($arg){
		if(ctype_alpha($arg)){
			return true;
		}else{
			return false;
		}
	}
	//validate argument is numeric
	public function numeric($arg){
		if(ctype_digit($arg)){
			return true;
		}else{
			return false;
		}
	}
	//validate argument is alphanumeric
	public function alpha_numeric($arg){
		if(ctype_alnum($arg)){
			return true;
		}else{
			return false;
		}
	}
	//validate argument 1 is the length specified by argument 2
	public function length_equals($arg, $len){
		if(strlen($arg)==$len){
			return true;
		}else{
			return false;	
		}
	}
	//validate argument 1 is more than the length specified by argument 2
	public function length_more($arg, $len){
		if(strlen($arg)>$len){
			return true;
		}else{
			return false;	
		}
	}
	//validate argument 1 is less than the length specified by argument 2
	public function length_less($arg, $len){
		if(strlen($arg)<$len){
			return true;
		}else{
			return false;	
		}
	}
	//validate argument is an email addreess
	public function email($arg){
		if(filter_var($arg, FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			return false;	
		}
	}
}
?>
