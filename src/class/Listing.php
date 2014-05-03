<?PHP
class Listing {
	//listing attributes
	public $listing_id;
	public $ebay_item_id;
	public $store; //the Store
	public $product; //the Product
	
	public $conditionCode; //ebay condition code
	public $conditionDetails; //custom condition description
	
	public $price;
	public $quantity;
	public $duration; //eBay listing duration
	
	public $shippingTemplate; //the ShippingTemplate
	public $returnTemplate; //the ReturnTemplate
	public $displayTemplate; //the DisplayTemplate
	
	public $activated; //Listing status
	
	//constructor sets the class variables
	public function __construct($store, $product, $condition_code, $condition_details, $price, $quantity, $duration_code, $shippingTemplate, $returnTemplate, $display_template_id){
		$this->store = $store;
		$this->product = $product;
		$this->conditionCode = $condition_code;
		$this->conditionDetails = $condition_details;
		$this->price = $price;
		$this->quantity = $quantity;
		$this->duration = $duration_code;
		$this->shippingTemplate = $shippingTemplate;
		$this->returnTemplate = $returnTemplate;
		$this->displayTemplate = $display_template_id;
	}
	
	//set the listing id for existing listings
	public function setListingID($id){
		$this->listing_id = $id;
	}
	
	//set the ebay itemID
	public function setItemID($itemID){
		$this->ebay_item_id = $itemID;
	}
	
	//setup Listing activated status
	public function setActivated($activated){
		if($activated == 0){
			$this->activated = false;
		}else{
			$this->activated = true;
		}
	}
	
	//set listing quantity
	public function setQuantity($quantity){
		//set the quantity from the method argument
		$this->quantity = $quantity;
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to update the listing quantity
		$sql = "UPDATE listing_tbl SET listing_quantity = ? WHERE listing_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('ii',$this->quantity, $this->listing_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Failed to update listing quantity: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
		}
	}
	
	//save a new listing
	public function saveListing(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the class variables
		$store_id = $mysqli->real_escape_string($this->store->store_id);
		$product_id = $mysqli->real_escape_string($this->product->product_id);
		$conditionCode = $mysqli->real_escape_string($this->conditionCode);
		$conditionDetails = $mysqli->real_escape_string($this->conditionDetails);
		$price = $mysqli->real_escape_string($this->price);
		$quantity = $mysqli->real_escape_string($this->quantity);
		$duration = $mysqli->real_escape_string($this->duration);
		$shippingTemplate = $mysqli->real_escape_string($this->shippingTemplate->id);
		$returnTemplate = $mysqli->real_escape_string($this->returnTemplate->id);
		$displayTemplate = $mysqli->real_escape_string($this->displayTemplate);
		//SQL Query to insert a new listing
		$sql = 'INSERT INTO listing_tbl (listing_store_id, listing_product_id, listing_condition_code, listing_condition_details, listing_price, listing_quantity, listing_duration, listing_shipping_id, listing_return_id, listing_display_id)VALUES(?,?,?,?,?,?,?,?,?,?)';
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('iiisdisiii', $store_id, $product_id, $conditionCode, $conditionDetails, $price, $quantity, $duration, $shippingTemplate, $returnTemplate, $displayTemplate);
		if (!$stmt->execute()) {
			//if execution failure return error
			return "Failed to create listing: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
		}
		//return success
		return true;
	}
	
	//Update an excisting listing 
	public function updateListing(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape class variables
		$product_id = $mysqli->real_escape_string($this->product->product_id);
		$conditionCode = $mysqli->real_escape_string($this->conditionCode);
		$conditionDetails = $mysqli->real_escape_string($this->conditionDetails);
		$price = $mysqli->real_escape_string($this->price);
		$quantity = $mysqli->real_escape_string($this->quantity);
		$duration = $mysqli->real_escape_string($this->duration);
		$shippingTemplate = $mysqli->real_escape_string($this->shippingTemplate->id);
		$returnTemplate = $mysqli->real_escape_string($this->returnTemplate->id);
		$displayTemplate = $mysqli->real_escape_string($this->displayTemplate);
		$listing_id = $mysqli->real_escape_string($this->listing_id);
		$store_id = $mysqli->real_escape_string($this->store->store_id);
		//SQL Query to update a listing
		$sql = 'UPDATE listing_tbl SET listing_product_id = ?, listing_condition_code = ?, listing_condition_details = ?, listing_price = ?, listing_quantity = ?, listing_duration = ?, listing_shipping_id = ?, listing_return_id = ?, listing_display_id = ? WHERE listing_id = ? AND listing_store_id = ?';
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('iisdisiiiii',$product_id, $conditionCode, $conditionDetails, $price, $quantity, $duration, $shippingTemplate, $returnTemplate, $displayTemplate, $listing_id, $store_id);
		if (!$stmt->execute()) {
			//if statement failure return error
			return "Failed to update listing: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
		}
		//return success
		return true;
	}
	
	//load the content of a display tmeplate
	public function getDisplayTemplateContent(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the templateid
		$template_id = $mysqli->real_escape_string($this->displayTemplate);
		//SQL Query to get the display template path
		$sql = "SELECT display_template_path FROM display_template_tbl WHERE display_template_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('i', $template_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Failed to get display template: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
		}else{
			//else bind the result
			$stmt->bind_result($template_name);
			//and fetch it
			while($stmt->fetch()){
				//get the server root 
				$root = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . "/marketer/";
				//get the template content
				$template_content = file_get_contents($root . '/ebay_templates/' . $template_name . '?listing_id=' . $this->listing_id);
				//and return it
				return $template_content;
			}
		}
	}
	
	//activate this listing
	public function activateListing(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to activate a listing
		$sql = "UPDATE listing_tbl SET listing_ebay_id = ?, listing_activated = 1 WHERE listing_id = ?";
		//prepare statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters
		$stmt->bind_param('si',$this->ebay_item_id, $this->listing_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Failed to update listing: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
		}
		//return success
		return true;
	}
	
	//deactivate this listing
	public function deactivateListing(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to deactivate a listing
		$sql = "UPDATE listing_tbl SET listing_ebay_id = NULL, listing_activated = 0 WHERE listing_id = ?";
		//prepare statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters
		$stmt->bind_param('i',$this->listing_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Failed to update listing: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
		}
		//return success
		return true;
	}
}
?>