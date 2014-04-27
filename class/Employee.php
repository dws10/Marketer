<?PHP
class Employee{
	//define class attributes
	public $employeeID; //Employee identifier
	public $forename, $surname; //name
	public $storeID; //their store
	public $email; //their email
	public $is_admin; //is the employee an admin
	public $status; //employment status
	public $ip;//connection IP address
	
	public function __construct(){
		//get the connecting IP address
		$this->ip = $_SERVER['REMOTE_ADDR'];
	}
	
	//set up class variables for employee
	public function setEmployee($id, $forename, $surname, $store_id, $email, $is_admin, $status){
		$this->employeeID = $id;
		$this->forename = $forename;
		$this->surname = $surname;
		$this->storeID = $store_id;
		$this->email = $email;
		$this->is_admin = $is_admin;
		$this->status = $status;	
	}
	
	//check if the current session is authenticated
	public function authenticateSession(){
		if (!isset($_SESSION['employee_id'])){
			//if the session has not been set return false
			return false;
		}else{
			//if the session has been set get the data
			$this->getSession();	
			//and return success
			return true;
		}
	}
	
	//get the employee details from there session
	private function getSession(){
		$this->employeeID = $_SESSION['employee_id'];
		$this->forename = $_SESSION['employee_forename'];
		$this->surname = $_SESSION['employee_surname'];
		$this->storeID = $_SESSION['employee_store_id'];
		$this->email = $_SESSION['employee_email'];
		$this->is_admin = $_SESSION['employee_is_admin'];
		$this->status = $_SESSION['employee_status'];
	}
	
	//setup the new employee session
	private function setSession(){
		 $_SESSION['employee_id'] = $this->employeeID;
		 $_SESSION['employee_forename'] = $this->forename;
		 $_SESSION['employee_surname'] = $this->surname;
		 $_SESSION['employee_store_id'] = $this->storeID;
		 $_SESSION['employee_email'] = $this->email;
		 $_SESSION['employee_is_admin'] = $this->is_admin;
		 $_SESSION['employee_status'] = $this->status;
	}
	
	//get employee store identifier
	public function getStore(){
		//get the employees store
		$store_id = NULL;
		//open new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to find the employees storeID 
		$sql = "SELECT store_id FROM employee_tbl WHERE employee_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i',$employeeID);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: " . $stmt->errno . " - " . $stmt->error;
		}else{
			//else bind the result
			$stmt->bind_result($store_id);
			//and fetch it
			$stmt->fetch();
			//setup the employees storeID
			$this->store_id = $store_id;	
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
	
	//end the employee session
	public function logout(){
		session_destroy();//destroys the current session
	}
	
	//update the employee infomation
	public function update($formdata){
		//new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape form data
		$employee_id = $mysqli->real_escape_string($formdata['user_id']);
		$fname = $mysqli->real_escape_string($formdata['fname']);
		$sname = $mysqli->real_escape_string($formdata['sname']);
		$email = $mysqli->real_escape_string($formdata['email']);
		$password = $mysqli->real_escape_string($formdata['password']);
		//build SQL Query to update employee details
		$sql = "UPDATE employee_tbl SET employee_forename = ?, employee_surname = ?, employee_email = ?";
		//if the password has been changed add the field to the query	
		if($password != '' && $password != NULL){
			$sql .= ", employee_password = ?";
		}
		//setup SQL Query where statement
		$sql .= " WHERE employee_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//if password is not blank
		if($password != '' && $password != NULL){
			$password = password_hash($password, PASSWORD_BCRYPT);//hash password
			//and bind the parameters to the query with password
			$stmt->bind_param('ssssi', $fname, $sname, $email, $password, $employee_id);
		}else{
			//else bind the parameters to the query without the password
			$stmt->bind_param('sssi', $fname, $sname, $email, $employee_id);
		}	
		if (!$stmt->execute()) {
			//if execution failure return error
			return "Error: " . $stmt->errno . " - " . $stmt->error;
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
		//set the updated details
		$this->forename = $fname;
		$this->surname = $sname;
		$this->email = $email;
		//set the updated details to the employee session
		$this->setSession();
		//return success
		return true;
	}
	
	//create a new employee (Admin only)
	public function createEmployee($forname, $surname, $email, $password, $is_admin){
		
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a new employee
		$sql = "INSERT INTO employee_tbl (employee_forename, employee_surname, employee_email, employee_password, employee_is_admin, employee_status)VALUES(?,?,?,?,?,1)";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('ssssi',$forename, $surname, $email, password_hash($password), $is_admin);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: " . $stmt->errno . " - " . $stmt->error;
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
	
	//records an employee action in the system
	public function logEvent($event_name){
		//get the current time
		$timestamp = date('Y-m-d H:i:s');
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a system event
		$sql = "INSERT INTO system_event_tbl (event_log_name, event_log_ip, event_log_timestamp, event_log_employee_id) VALUES (?,?,?,?)";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('sssi', $event_name, $this->ip, $timestamp, $this->employeeID);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: " . $stmt->errno . " - " . $stmt->error;
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
}
?>