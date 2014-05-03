<?PHP
class EbayCategory{
	//class attributes
	public $id; //our ebay category ID
	public $real_id; //the real eBay category ID
	public $name; //the ebay category name
	public $parent_cat; //the parent category EbayCategory
	public $level; //the category level in the hiarchy
	public $leaf; //Boolean is it leaf?
	
	//constructor assigns class variables
	public function __construct($id, $real_id, $name, $parent_id, $level, $leaf){
		$this->id = $id;
		$this->real_id = $real_id;
		$this->name = $name;
		$this->level = $level;
		$this->leaf = $leaf;
		//get the parent category
		$this->getParent($parent_id);
	}
	
	//get the parent category by its id
	public function getParent($parent_id){
		//cannot have a category for level 1 categories
		if($this->level > 1){
			//open a mysqli connection
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to select ebay category
			$sql = "SELECT ebay_category_id, ebay_category_real_id, ebay_category_name, ebay_category_parent_id, ebay_category_level, ebay_category_leaf 
					FROM ebay_category_tbl 
					WHERE ebay_category_real_id = ?";
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			//bind parameters to they query
			$stmt->bind_param('i', $parent_id);
			if(!$stmt->execute()){
				//if execution error return error
				return "Error: ".$stmt->errorno." - ".$stmt->error;	
			}else{
				//else bidn the result
				$stmt->bind_result($ebay_category_id, $ebay_id, $ebay_name, $parent_id, $level, $leaf);
				//store the result so we can check num rows 
				$stmt->store_result();
				if($stmt->num_rows > 0){
					//if more than 0 rows are returned
					while($stmt->fetch()){
						//fetch the row and instatiate the parent category
						$this->parent_cat = new EbayCategory($ebay_category_id, $ebay_id, $ebay_name, $parent_id, $level, $leaf);
					}
				}else{
					//if num rows is 0 or less return error
					return "Error: No Results";	
				}
			}
			//return success
			return true;
		}
	}
	
	//return an array of parent ID to reach top level
	public function getParentIdList(){
		//instantiate array
		$parent_ids = array();
		//start with the current category
		$category = $this;
		//while the category level is not top
		$i = 0;
		while($category->level >= 1){
			//assign the real ebay id of the parent to the array
			$parent_ids[$i] = $category->real_id;
			$i++;
			if($category->level > 1){
				//if level is more than top category becomes the parent
				$category = $category->returnParent();	
			}else{
				break;	
			}
		}
		//return the parent ids
		return $parent_ids;
	}
	
	//get the children of the category
	public function getChildren(){
		//if this category is not a leaf category
		if($this->leaf == 0){
			//instantitate the children array
			$children = array();
			//open a mysqli connection
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to select the child ebay category
			$sql = "SELECT ebay_category_id, ebay_category_name FROM ebay_category_tbl WHERE ebay_category_parent_id = ?";
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			//bind the parameters to the argument
			$stmt->bind_param('i', $this->real_id);
			if(!$stmt->execute()){
				//if execution fails return error
				return "Error: ".$stmt->errorno." - ".$stmt->error;	
			}else{
				//else bind the result
				$stmt->bind_result($ebay_id, $ebay_name);
				//and store it
				$stmt->store_result();
				if($stmt->num_rows > 0){
					//if the more than 0 rows
					while($stmt->fetch()){
					//fetch the result and add it to the children array
						$children[$ebay_id] = $ebay_name;
					}
				}else{
					return "Error: No Results";//else return error
				}
			}
			//return the children array
			return $children;
		}
	}
	
	//return the parent category object 
	public function returnParent(){
		return $this->parent_cat;
	}
	
	//get the category condition options
	public function getConditionOptions(){
		//get the list of parent ids
		$ids = $this->getParentIdList();
		//instantiate conditions array
		$conditions = array();
		//while conditions array is empty
		while(count($conditions) == 0){
			//open a new mysqli connection
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to select ebay category conditions
			$sql = "SELECT ebay_condition_real_id, ebay_condition_name FROM ebay_condition_tbl WHERE ebay_condition_category_id IN (?)";
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			for ($i = 0; $i < count($ids); $i++){
				//loop through the parent ID array binding the ids to the query
				$stmt->bind_param("i", $ids[$i]);
			}
			if(!$stmt->execute()){
				//if execution fails return error
				return "Error: ".$stmt->errorno." - ".$stmt->error;	
			}else{
				//else bind the result
				$stmt->bind_result($ebay_condition_id, $ebay_condition_name);
				//and store it
				$stmt->store_result();
				//in num rows holds more than 0
				if($stmt->num_rows > 0){
					while($stmt->fetch()){
						//fetch the result and add it to the array
						$conditions[$ebay_condition_id] = $ebay_condition_name;
					}
				}
			}
		}
		//return the conditions array
		return $conditions;
	}
}
?>