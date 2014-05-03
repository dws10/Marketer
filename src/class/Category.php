<?PHP
class Category{

	public $category_id;
	public $category_name; 
	public $ebay_category_id;
	public $ebay_category;
	public $category_ebay_store_id;
	
	public function __construct($category_id, $name, $ebay_id, $ebay_store_id){
		//constructor sets class variables
		$this->category_id = $category_id;//Category identifier
		$this->category_name = $name;//Category name
		$this->ebay_category_id = $ebay_id;//the real ebay category id
		
		//open mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL query to select ebay category data
		$sql = "SELECT ebay_category_id, ebay_category_real_id, ebay_category_name, ebay_category_parent_id, ebay_category_level, ebay_category_leaf 
				FROM ebay_category_tbl 
				WHERE ebay_category_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('i', $ebay_id);
		//if execute fails
		if(!$stmt->execute()){
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//else bind the results
			$stmt->bind_result($ebay_category_id, $ebay_category_real_id, $ebay_category_name, $ebay_category_parent_id, $ebay_category_level, $ebay_category_leaf);
			//and store them
			$stmt->store_result();
			//if the number of rows returned is 1
			if($stmt->num_rows == 1){
				//fetch the results
				$stmt->fetch();
				//instantiate a new ebay category 
				$this->ebay_category = new EbayCategory($ebay_category_id, $ebay_category_real_id, $ebay_category_name, $ebay_category_parent_id, $ebay_category_level, $ebay_category_leaf);
			}else{
				return "Error: No Results";	
			}	
		}
		//set the ebay storeID
		$this->category_ebay_store_id = $ebay_store_id;
	}
	
	//returns the ebay category
	public function getEbayCategory(){
		return $this->ebay_category;
	}
	
	//add a new category to our system by system storeID
	public function addCategory($store_id){
		//open a mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the values
		$store_id = $mysqli->real_escape_string($store_id);
		$category_name = $mysqli->real_escape_string($this->category_name);
		$ebay_category_id = $mysqli->real_escape_string($this->ebay_category_id);
		$ebay_store_category_id = $mysqli->real_escape_string($this->category_ebay_store_id);
		//sql query to insert a new category
		$sql = "INSERT INTO category_tbl (category_store_id, category_name, category_ebay_id, category_ebay_store_id) VALUES (?,?,?,?)";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('isii', $store_id, $category_name, $ebay_category_id, $ebay_store_category_id);
		//if statement fails to execute
		if(!$stmt->execute()){
			//Error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			//else return success
			return true;
		}	
	}
	
	//update a sellers store category
	public function updateCategory(){
		//open a mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the variables
		$cat_id = $mysqli->real_escape_string($this->category_id);
		$cat_name = $mysqli->real_escape_string($this->category_name);
		$ebay_cat = $mysqli->real_escape_string($this->ebay_category->id);
		$ebay_store_cat = $mysqli->real_escape_string($this->category_ebay_store_id);
		//build they sQL query
		$sql = "UPDATE category_tbl 
				SET category_name = ?, category_ebay_id = ?, category_ebay_store_id = ? 
				WHERE category_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the paramters
		$stmt->bind_param('siii', $cat_name, $ebay_cat, $ebay_store_cat, $cat_id);
		if (!$stmt->execute()) {
			//if execution failure return error
			return "Error: " . $mysqli->errno . " - " . $mysqli->error;
		}else{
			//else return success 
			return true;	
		}
	}
}
?>