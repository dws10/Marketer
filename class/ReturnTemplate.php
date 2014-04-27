<?PHP
class ReturnTemplate {
	//returnTemplate attributes
	public $id; 
	public $title; //the title to identify the template
	public $duration; //returns within
	public $description; //return policy 
	
	//constructor assings class variables
	public function __construct($title, $duration, $desc){
		$this->title = $title;
		$this->duration = $duration;
		$this->description = $desc;	
		
	}
	
	//assign the templateID
	public function setID($id){
		$this->id = $id;
	}
	
	//save a new return template to the database
	public function saveTemplate($store_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the variables
		$title = $mysqli->real_escape_string($this->title);
		$duration = $mysqli->real_escape_string($this->duration);
		$policy = $mysqli->real_escape_string($this->description);
		//SQL Query to insert a new return template
		$sql = "INSERT INTO return_template_tbl (store_id, return_template_title, return_template_duration, return_template_policy)VALUES(?,?,?,?)";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//assign the parameters to the query
		$stmt->bind_param('isss', $store_id, $title, $duration, $policy);
		if(!$stmt->execute()){
			//if execution fails then return error
			return "Error: ". $stmt->errno . " - " . $stmt->error;	
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
	
	//update an existing return template
	public function updateTemplate($template_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the variables
		$id = $mysqli->real_escape_string($template_id);
		$title = $mysqli->real_escape_string($this->title);
		$duration = $mysqli->real_escape_string($this->duration);
		$policy = $mysqli->real_escape_string($this->description);
		//SQL Query to update an existing return template
		$sql = "UPDATE return_template_tbl SET return_template_title = ?, return_template_duration = ?, return_template_policy = ? WHERE return_template_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('sssi', $title, $duration, $policy, $id);
		if(!$stmt->execute()){
			//if execution fails return the error
			return "Error: ". $stmt->errno . " - " . $stmt->error;	
		}
		//close the connections
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
}
?>