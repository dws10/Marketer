<?PHP
class Guest {
	//Guest class attributes
	public $ip; //the ip of the guest

	//constructor sets class variables
	public function __construct(){
		//get guests ip address
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	//register a new store and employee account (First employee is Admin)
	public function register($formdata){
		//get form data
		$employee_fname = $formdata['fname'];
		$employee_sname = $formdata['sname'];
		$employee_email = $formdata['email'];
		$employee_password = $formdata['password'];
		$store_name = $formdata['store_name'];
		$store_auth_token = $formdata['auth_token'];
		$paypal_email = $formdata['paypal_email'];
		$postcode = $formdata['address_postcode'];
		$building = $formdata['address_building'];
		$road1 = $formdata['address_road1'];
		$road2 = $formdata['address_road2'];
		$town = $formdata['address_town'];
		$county = $formdata['address_county'];
		
		//create an employee returning its id
		$adminID = $this->createEmployee($employee_fname, $employee_sname, $employee_email, $employee_password);
		
		//if admin id returned as an error
		if(substr($adminID , 0, 8) == "SQLERROR"){
			$error = $adminID;
			//return error
			return $error ;
		}else{
			//create the new store
			$storeID = $this->createStore($store_name, $adminID, $store_auth_token, $paypal_email);
			//if storeID returned as an error
			if(substr($storeID , 0, 8) == "SQLERROR"){
				$error = $storeID;
				//return error
				return $error ;
			}else{
				//update the employee with the new store ID
				$this->updateEmployeeStoreID($storeID, $adminID);
				//and register the store address
				$this->registerAddress($postcode, $building, $road1, $road2, $town, $county, $storeID);
			}
		}
		//return success
		return true;
	}
	
	//create a new employee (Admin only for now)
	private function createEmployee($forename, $surname, $email, $password){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a new employee
		$sql = "INSERT INTO employee_tbl (employee_forename, employee_surname, employee_email, employee_password, employee_is_admin, employee_status)VALUES(?,?,?,?,1,1)";
		//escape arguments
		$forename = $mysqli->real_escape_string($forename);
		$surname = $mysqli->real_escape_string($surname);
		$email = $mysqli->real_escape_string($email);
		$password = $mysqli->real_escape_string($password);
		//hash the password http://www.php.net/manual/en/faq.passwords.php#faq.passwords.bestpractice
		$password = password_hash($password,PASSWORD_BCRYPT);
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('ssss',$forename, $surname, $email, $password);
		if(!$stmt->execute()){
			//if execution fails return error
			return "SQLERROR - ".$stmt->errno. ' - ' .$mysqli->error;
		}
		//get the employee ID from the query
		$employeeID = $stmt->insert_id;
		//close the connections
		$stmt->close();
		$mysqli->close();
		//return the employee id
		return $employeeID;
	}
	
	//register the seller's address
	private function registerAddress($postcode, $building, $road1, $road2, $town, $county, $store_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a new store address
		$sql = "INSERT INTO store_address_tbl (store_address_building, store_address_road1, store_address_road2, store_address_town, store_address_county, store_address_postcode, store_address_store_id)VALUES(?,?,?,?,?,?,?)";
		//escape the method arguments
		$postcode = $mysqli->real_escape_string($postcode);
		$building = $mysqli->real_escape_string($building);
		$road1 = $mysqli->real_escape_string($road1);
		$road2 = $mysqli->real_escape_string($road2);
		$town = $mysqli->real_escape_string($town);
		$county = $mysqli->real_escape_string($county);
		$store_id = $mysqli->real_escape_string($store_id);
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('ssssssi', $building, $road1, $road2, $town, $county,$postcode, $store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "SQLERROR - ".$stmt->errno. ' - ' .$mysqli->error;
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
	}
	
	//update the employee's store id
	private function updateEmployeeStoreID($storeID, $employeeID){
		//open new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to update the employee store id
		$sql = "UPDATE employee_tbl SET employee_store_id = ? WHERE employee_id = ?";
		//escape the method arguments
		$storeID = $mysqli->real_escape_string($storeID);
		$employeeID = $mysqli->real_escape_string($employeeID);
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('ii', $storeID, $employeeID);
		if(!$stmt->execute()){
			//if execution fails return error
			return "SQLERROR - ".$stmt->errno. ' - ' .$mysqli->error;
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
	}
	
	//create a new store
	private function createStore($store_name, $adminID, $authToken, $paypal_email){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a new store
		$sql = "INSERT INTO store_tbl (store_name, store_admin_id, store_auth_token, store_paypal_email)VALUES(?,?,?,?)";
		//escape the method arguments
		$store_name = $mysqli->real_escape_string($store_name);
		$adminID = $mysqli->real_escape_string($adminID);
		$authToken = $mysqli->real_escape_string($authToken);
		$paypal_email = $mysqli->real_escape_string($paypal_email);
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to they query
		$stmt->bind_param('siss', $store_name, $adminID, $authToken, $paypal_email);
		if(!$stmt->execute()){
			//if execution fails return error
			return "SQLERROR - ".$stmt->errno. ' - ' .$mysqli->error;
		}
		//get the storeID as last insert id
		$storeID = $stmt->insert_id;
		//close the connection
		$stmt->close();
		$mysqli->close();
		//return the storeID
		return $storeID;
	}
	
	public function login($formdata){
		
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select employees matching the email
		$sql = "SELECT employee_id, employee_forename, employee_surname, employee_store_id, employee_email, employee_password, employee_is_admin, employee_status 
				FROM employee_tbl 
				WHERE employee_email = ?";
		//get and escape the formdata
		$email = $mysqli->real_escape_string($formdata['email']);
		$password = $formdata['password'];
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('s', $email);
		if(!$stmt->execute()){
			//if execution fails return error
			return 'Error: '.$stmt->errno. ' - ' .$mysqli->error;
		}else{
			//bind the result
			$stmt->bind_result($employee_id, $employee_forename, $employee_surname, $employee_store_id, $employee_email, $employee_password, $employee_is_admin, $employee_status);
			//store it so num rows works
			$stmt->store_result();
			//if only 1 employee result is returned
			if($stmt->num_rows == 1){
				//fetch it
				$stmt->fetch();
				//verify password with PHP password hashing API
				if(password_verify($password, $employee_password)){
					//if password verifies setup the employee session
					$_SESSION['employee_id'] = $employee_id;
					$_SESSION['employee_forename'] = $employee_forename;
					$_SESSION['employee_surname'] = $employee_surname;
					$_SESSION['employee_store_id'] = $employee_store_id;
					$_SESSION['employee_email'] = $employee_email;
					$_SESSION['employee_is_admin'] = $employee_is_admin;
					$_SESSION['employee_status'] = $employee_status;
					return true;
				}else{
					//return error
					return 'Error: Password not recognised.';	
				}
			}else{
				//return error
				return 'Error: Account not recognised.';
			}
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
	}

	//record guest action
	public function logEvent($event_name){
		//the current timestamp
		$timestamp = date('Y-m-d H:i:s');
		//open new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a system event 
		$sql = "INSERT INTO system_event_tbl (event_log_name, event_log_ip, event_log_timestamp, event_log_employee_id) VALUES (?,?,?, NULL)";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('sss', $event_name, $this->ip, $timestamp);
		if(!$stmt->execute()){
			//if execution fails return error
			return 'Error:  '.$stmt->errno.' - '.$stmt->error;
		}
	}
}
?>