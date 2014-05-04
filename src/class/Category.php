<?PHP
class Category{

	public $category_id; //cat id
	public $category_name; 
	public $ebay_category_id;
	public $ebay_category;
	public $category_ebay_store_id;
	
	/*
	*@constructor
	*@param int $category_id the categories id
	*@param int $name the categories name
	*@param int $ebay_id the categories ebay category id
	*@param int $ebay_store_id the categories ebay store category id
	*/
	public function __construct($category_id, $name, $ebay_id, $ebay_store_id){
		//constructor sets class variables
		$this->category_id = $category_id;//Category identifier
		$this->category_name = $name;//Category name
		$this->ebay_category_id = $ebay_id;//the real ebay category id
		$this->setEbayCategoryFromDB($ebay_id);//set the ebaycategory object
		$this->category_ebay_store_id = $ebay_store_id;//set the ebay storeID
	}
	/*
	*@method setEbayCategoryFromDB loads the categories ebay category object from the database
	*@param int $ebay_id the categories ebay category id
	*/
	public function setEbayCategoryFromDB($ebay_id){
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL query to select ebay category data
		$sql = "SELECT ebay_category_id, ebay_category_real_id, ebay_category_name, ebay_category_parent_id, ebay_category_level, ebay_category_leaf 
				FROM ebay_category_tbl 
				WHERE ebay_category_id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('i', $ebay_id);
		if(!$stmt->execute()){
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			$stmt->bind_result($ebay_category_id, $ebay_category_real_id, $ebay_category_name, $ebay_category_parent_id, $ebay_category_level, $ebay_category_leaf);
			$stmt->store_result();
			if($stmt->num_rows == 1){
				$stmt->fetch();
				$this->ebay_category = new EbayCategory($ebay_category_id, $ebay_category_real_id, $ebay_category_name, $ebay_category_parent_id, $ebay_category_level, $ebay_category_leaf);
				return true;
			}else{
				return "Error: No Results";	
			}	
		}
	}
	/*
	*@method getEbayCategory returns the ebay category
	*/
	public function getEbayCategory(){
		return $this->ebay_category;
	}
	/*
	*@method addCategory add a new category to our system by system storeID
	*@param int $store_id the store id to add the category to
	*/
	public function addCategory($store_id){
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		$store_id = $mysqli->real_escape_string($store_id);
		$category_name = $mysqli->real_escape_string($this->category_name);
		$ebay_category_id = $mysqli->real_escape_string($this->ebay_category_id);
		$ebay_store_category_id = $mysqli->real_escape_string($this->category_ebay_store_id);
		//sql query to insert a new category
		$sql = "INSERT INTO category_tbl (category_store_id, category_name, category_ebay_id, category_ebay_store_id) VALUES (?,?,?,?)";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('isii', $store_id, $category_name, $ebay_category_id, $ebay_store_category_id);
		if(!$stmt->execute()){
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			return true;
		}	
	}
	/*
	*@method updateCategory update a sellers store category
	*/
	public function updateCategory(){
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		$cat_id = $mysqli->real_escape_string($this->category_id);
		$cat_name = $mysqli->real_escape_string($this->category_name);
		$ebay_cat = $mysqli->real_escape_string($this->ebay_category->id);
		$ebay_store_cat = $mysqli->real_escape_string($this->category_ebay_store_id);
		//sql query to update an existing category
		$sql = "UPDATE category_tbl 
				SET category_name = ?, category_ebay_id = ?, category_ebay_store_id = ? 
				WHERE category_id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('siii', $cat_name, $ebay_cat, $ebay_store_cat, $cat_id);
		if (!$stmt->execute()) {
			return "Error: " . $mysqli->errno . " - " . $mysqli->error;
		}else{
			return true;	
		}
	}
}
?>
