<?PHP
class EbayCategory{
	//class attributes
	public $id; //our ebay category ID
	public $real_id; //the real eBay category ID
	public $name; //the ebay category name
	public $parent_cat; //the parent category EbayCategory
	public $level; //the category level in the hiarchy
	public $leaf; //Boolean is it leaf?
	/*
	*@constructor
	*@param $id int the ebay category id for our internal database
	*@param $real_id int the ebat category id from ebay
	*@param $name String the category name
	*@param $parent_id int the real id of the ebay category parent
	*@param $level int the level of the ebay category in the ebay hiarachy
	*@param $leaf Boolean is the category a leaf
	*/
	public function __construct($id, $real_id, $name, $parent_id, $level, $leaf){
		$this->id = $id;
		$this->real_id = $real_id;
		$this->name = $name;
		$this->level = $level;
		$this->leaf = $leaf;
		//get the parent category
		$this->getParent($parent_id);
	}
	/*
	*@method getParent get the parent category by its id
	*@param $parent_id the id of the parent
	*@return true or error
	*/
	public function getParent($parent_id){
		//cannot have a category for level 1 categories
		if($this->level > 1){
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to select ebay category
			$sql = "SELECT ebay_category_id, ebay_category_real_id, ebay_category_name, ebay_category_parent_id, ebay_category_level, ebay_category_leaf 
					FROM ebay_category_tbl 
					WHERE ebay_category_real_id = ?";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('i', $parent_id);
			if(!$stmt->execute()){
				return "Error: ".$stmt->errorno." - ".$stmt->error;	
			}else{
				$stmt->bind_result($ebay_category_id, $ebay_id, $ebay_name, $parent_id, $level, $leaf);
				$stmt->store_result();
				if($stmt->num_rows > 0){
					while($stmt->fetch()){
						//fetch the row and instatiate the parent category
						$this->parent_cat = new EbayCategory($ebay_category_id, $ebay_id, $ebay_name, $parent_id, $level, $leaf);
					}
				}else{
					return "Error: No Results";	
				}
			}
			return true;
		}
	}
	/*
	*@method getParentIdList gets an array of parent ID to reach top level
	*@return the parent ids
	*/
	public function getParentIdList(){
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
		return $parent_ids;
	}
	/*
	*@method getChildren get the children of the category
	*@return the array of children
	*/
	public function getChildren(){
		//if this category is not a leaf category
		if($this->leaf == 0){
			$children = array();
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to select the child ebay category
			$sql = "SELECT ebay_category_id, ebay_category_name FROM ebay_category_tbl WHERE ebay_category_parent_id = ?";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('i', $this->real_id);
			if(!$stmt->execute()){
				return "Error: ".$stmt->errorno." - ".$stmt->error;	
			}else{
				$stmt->bind_result($ebay_id, $ebay_name);
				$stmt->store_result();
				if($stmt->num_rows > 0){
					while($stmt->fetch()){
						//fetch the result and add it to the children array
						$children[$ebay_id] = $ebay_name;
					}
				}else{
					return "Error: No Results";//else return error
				}
			}
			return $children;
		}
	}
	/*
	*@method returnParent get the parent category
	*@return the parent category object
	*/
	public function returnParent(){
		return $this->parent_cat;
	}
	/*
	*@method getConditionOptions returns an array of conditions applicable to this ebay category
	*@return the array of conditions and there corrosponding id as the index
	*/
	public function getConditionOptions(){
		$ids = $this->getParentIdList();
		$conditions = array();
		while(count($conditions) == 0){
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to select ebay category conditions
			$sql = "SELECT ebay_condition_real_id, ebay_condition_name FROM ebay_condition_tbl WHERE ebay_condition_category_id IN (?)";
			$stmt = $mysqli->prepare($sql);
			//bind parameters to the statement by looping the array
			for ($i = 0; $i < count($ids); $i++){
				$stmt->bind_param("i", $ids[$i]);
			}
			if(!$stmt->execute()){
				return "Error: ".$stmt->errorno." - ".$stmt->error;	
			}else{
				$stmt->bind_result($ebay_condition_id, $ebay_condition_name);
				$stmt->store_result();
				if($stmt->num_rows > 0){
					while($stmt->fetch()){
						//loop fetching the result and add it to the array
						$conditions[$ebay_condition_id] = $ebay_condition_name;
					}
				}
			}
		}
		return $conditions;
	}
}
?>
