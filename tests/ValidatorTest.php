<?php

include ('/var/lib/hudson/jobs/marketer_new/workspace/src/class/Validator.php');
//include ('../src/class/Validator.php');
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers test construct
    */
    public function testObjectCanBeConstructedForValidConstructorArgument()
    {
        $validator = new Validation();

        $this->assertInstanceOf('Validation', $validator);

        return $validator;
    }
	/**
     * @covers Validator::equals
    */
    public function testEqualsMethod()
    {
		$validator = new Validation();
		
		//tests with integers
		$min=1;$max=9999; //random number between
		for($i = 0; $i < 30; $i++){
			$randomNumber = rand($min, $max);
			$this->assertTrue($validator->equals($randomNumber, $randomNumber));
		}
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with strings
		for($i = 0; $i < 30; $i++){
			$length = rand(20, 250);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertTrue($validator->equals($randomString, $randomString));
		}
				
        return $validator;
    }
	/**
     * @covers Validator::equals
    */
	public function testDoesNotEqualsMethod()
    {
		$validator = new Validation();
		
		//tests with integers
		$min=1;$max=9999; //random number between
		for($i = 0; $i < 30; $i++){
			$randomNumber = rand($min, $max);
			$this->assertFalse($validator->equals($randomNumber, $randomNumber - 1));
		}
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$length = rand(20, 250);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertFalse($validator->equals($randomString, substr($randomString, 1)));
		}
				
        return $validator;
    }
	public function testAlphaMethodTrue(){
		$validator = new Validation();
		$characters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with alpha strings
		for($i = 0; $i < 30; $i++){
			$length = rand(1, 250);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertTrue($validator->alpha($randomString));
		}
				
        return $validator;
	}
	public function testAlphaMethodFalse(){
		$validator = new Validation();
		
		//tests with integers
		$min=1;$max=9999; //random number between
		for($i = 0; $i < 30; $i++){
			$randomNumber = rand($min, $max);
			$this->assertFalse($validator->alpha($randomNumber));
		}
				
        return $validator;
	}
	public function testNumericMethodFalse(){
		$validator = new Validation();
		$characters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with alpha strings
		for($i = 0; $i < 30; $i++){
			$length = rand(20, 250);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertFalse($validator->numeric($randomString));
		}
				
        return $validator;
	}
	public function testNumericMethodTrue(){
		$validator = new Validation();
		
		//tests with integers
		$min=1;$max=9999; //random number between
		for($i = 0; $i < 30; $i++){
			$randomNumber = rand($min, $max);
			$this->assertTrue($validator->numeric($randomNumber));
		}
				
        return $validator;
	}
	public function testAlphaNumericMethodTrue(){
		$validator = new Validation();
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$length = rand(20, 250);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertTrue($validator->alpha_numeric($randomString, substr($randomString, 1)));
		}
			
        return $validator;
	}
	public function testAlphaNumericMethodFalse(){
		$validator = new Validation();
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$length = rand(20, 250);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertFalse($validator->alpha_numeric($randomString.'#'));
		}
			
        return $validator;
	}
	public function testLengthEquals(){
		$validator = new Validation();
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$randomString = '';
			
			$length = rand(20, 250);
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertTrue($validator->length_equals($randomString, $length));
		}
			
        return $validator;
	}
	public function testLengthDoesNotEqual(){
		$validator = new Validation();
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$randomString = '';
			$length = rand(20, 250);
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertFalse($validator->length_equals($randomString, $length+rand(10, 250)));
		}
			
        return $validator;
	}
	public function testLengthMoreThan(){
		$validator = new Validation();
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$randomString = '';
			
			$length = rand(20, 250);
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertTrue($validator->length_more($randomString, $length - rand(10, 250)));
			$this->assertFalse($validator->length_less($randomString, $length - rand(10, 250)));
		}
			
        return $validator;
	}
	public function testLengthLessThan(){
		$validator = new Validation();
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//test with numeric strings
		for($i = 0; $i < 30; $i++){
			$randomString = '';
			
			$length = rand(20, 250);
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, count($characters)-1)];
			}
			$this->assertFalse($validator->length_more($randomString, $length + rand(10, 250)));
			$this->assertTrue($validator->length_less($randomString, $length + rand(10, 250)));
		}
			
        return $validator;
	}
	public function testEmailTrue(){
		$validator = new Validation();
		
		//test with numeric strings
		$tlds = array("com", "net", "gov", "org", "edu", "biz", "info");
		$characters = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','.'); 
		
		for($i = 0; $i < 30; $i++){
			$username = '';
			$length = rand(10, 64);
			for ($i = 0; $i < $length; $i++) {
				$username .= $characters[rand(0, count($characters)-1)];
			}
			$domain = '';
			$length = rand(10, 64);
			for ($i = 0; $i < $length; $i++) {
				$domain .= $characters[rand(0, count($characters)-1)];
			}
			
			$email = $username.'@'.$domain.'.'.$tlds[rand(0,count($tlds)-1)];
			
			$this->assertTrue($validator->email($email));
		}
			
        return $validator;
	}
}
