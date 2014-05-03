<?PHP
class ShippingTemplate {
	//shipping template attributes
	public $id;
	public $title; //shipping template name
	public $service_id; //shipping service eBay code
	public $description; //shipping service description
	public $dispatch_time; //how long till dispatch in days
	public $service_cost; //shipping service cost
	public $service_additional_cost; //extra cost per item
	
	//constructor assigns class variables
	public function __construct($title, $description, $code, $dispatch_time, $service_cost, $service_additional_cost){
		$this->title = $title;
		$this->service_id = $code;
		$this->description = $description;
		$this->dispatch_time = $dispatch_time;
		$this->service_cost = number_format($service_cost,2);
		$this->service_additional_cost = number_format($service_additional_cost,2);
	}
	
	//set the templates ID
	public function setID($id){
		$this->id = $id;	
	}
	
	//save a new template
	public function saveTemplate($store_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);	
		//escape the variables
		$title = $mysqli->real_escape_string($this->title);
		$store_id = $mysqli->real_escape_string($store_id);
		$shipping_id = $mysqli->real_escape_string($this->service_id);
		$dispatch_time = $mysqli->real_escape_string($this->dispatch_time);
		$cost = $mysqli->real_escape_string($this->service_cost);
		$additonal_cost = $mysqli->real_escape_string($this->service_additional_cost);
		//SQL Query to create a new shipping template
		$sql = "INSERT INTO shipping_template_tbl (shipping_template_title, store_id, ebay_shipping_id, shipping_template_dispatch_time, shipping_template_cost, shipping_template_additional_cost) VALUES (?,?,?,?,?,?)";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters
		$stmt->bind_param('siiidd', $title, $store_id, $shipping_id, $dispatch_time, $cost, $additonal_cost);	
		if(!$stmt->execute()){
			//if execution fails return the error
			return "Error: ".$stmt->errno . " - " . $stmt->error;	
		}
		//close the connection
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
	
	//Update an exisiting shipping template
	public function updateTemplate($template_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);	
		//escape the variables
		$template_id = $mysqli->real_escape_string($template_id);
		$shipping_id = $mysqli->real_escape_string($this->service_id);
		$dispatch_time = $mysqli->real_escape_string($this->dispatch_time);
		$cost = $mysqli->real_escape_string($this->service_cost);
		$additonal_cost = $mysqli->real_escape_string($this->service_additional_cost);
		//SQL query to update a shpping template
		$sql = "UPDATE shipping_template_tbl SET ebay_shipping_id = ?, shipping_template_dispatch_time = ?, shipping_template_cost = ?, shipping_template_additional_cost = ? WHERE shipping_template_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('iiddi', $shipping_id, $dispatch_time, $cost, $additonal_cost, $template_id);	
		if(!$stmt->execute()){
			//if execution fails return the error
			return "Error: ".$stmt->errno . " - " . $stmt->error;	
		}
		//close the connections
		$stmt->close();
		$mysqli->close();
		//return success
		return true;
	}
}
?>