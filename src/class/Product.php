<?PHP
class Product {
	//class atrributes
	public $product_id;
	public $store_id;
	public $title;
	public $description;
	public $category_id; //our category id
	public $ebay_attributes = array(); //ebay recommended attribute array 'name' => 'value'
	public $images = array(); //image array
	
	//constructor sets class variables
	public function __construct($store_id){
		$this->store_id = $store_id;
	}
	
	//set the product id for already existing products
	public function setProductID($product_id){
		$this->product_id = $product_id;
	}
	//set the product general attributes
	public function setProductGeneral($title, $description, $category_id){
		$this->title = $title;
		$this->description = $description;
		$this->category_id = $category_id;
	}
	//set product ebay attributes
	public function setEbayAttributes($ebay_attributes){
		$this->ebay_attributes = $ebay_attributes;
	}
	
	//add a new product
	public function addProduct(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to insert a new product
		$sql = 'INSERT INTO product_tbl (category_id, product_title, product_description)VALUES(?,?,?)';
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('iss', $this->category_id, $this->title, $this->description);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Failed to Insert product: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
		}else{
			//product id is the last sql instert id
			$product_id = $stmt->insert_id;
			
			$x = 1;
			//get the ebay attributes
			$ebay_attributes = $this->ebay_attributes;
			foreach($ebay_attributes as $name => $value){
				//SQL Query to insert a new ebay specific
				$sql = "INSERT INTO product_specific_tbl (product_id, product_specific_name, product_specific_value, product_specific_order, product_specific_type) VALUES (?,?,?,?,?)";
				//prepare the statement
				$stmt = $mysqli->prepare($sql);
				//attribute type is ebay
				$type = 'ebay';
				//bind the parameters to the query
				$stmt->bind_param('issis', $product_id, $name, $value, $x, $type);
				if (!$stmt->execute()) {
					//if execution fails return error
					return "Failed to update category parentID: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
				}
				$x++;
			}
			//return the product id
			return $product_id;
		}
				
	}
	
	//update the products general settings
	public function updateProductGeneral(){
		//open anew mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to update the products general attributes
		$sql = 'UPDATE product_tbl SET category_id = ?, product_title = ?, product_description = ? WHERE product_id = ?';
		//prepare the statment
		$stmt = $mysqli->prepare($sql);
		//escape the class variables
		$category_id = $mysqli->real_escape_string($this->category_id);
		$title = $mysqli->real_escape_string($this->title);
		$description = $mysqli->real_escape_string($this->description);
		$product_id = $mysqli->real_escape_string($this->product_id);
		//bind the parameters to the query
		$stmt->bind_param('issi', $category_id, $title, $description, $product_id);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: ". $mysqli->errno . " - " . $mysqli->error;
		}
		//return success
		return true;	
	}
	
	//update product attributes
	public function updateProductAttributes(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST,SQL_USER,SQL_PASS,SQL_DB);
		//SQL Query to remove existing attributes	
		$sql = "DELETE FROM product_specific_tbl WHERE product_id = ?";
		//escape the class variables
		$product_id = $mysqli->real_escape_string($this->product_id);
		$attributes = $mysqli->real_escape_string($this->ebay_attributes);
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $product_id);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: ". $mysqli->errno . " - " . $mysqli->error;
		}
		$i = 0;
		foreach($attributes as $name => $value){//loop attributes
			//the order the attributes will be displayed in
			$order = $i + 1;	
			//SQL Query to insert the new product specifics
			$sql = "INSERT INTO product_specific_tbl (product_specific_value, product_specific_type, product_specific_order, product_specific_name, product_id)VALUES(?,?,?,?,?)";
			//type is ebay
			$type = 'ebay';
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			//bind the parameters to the query
			$stmt->bind_param('ssisi', $value, $type, $order, $name, $product_id);
			if (!$stmt->execute()) {
				//if execution fails return error
				return "Error: ". $mysqli->errno . " - " . $mysqli->error;
			}
			$i++;
		}
		//return success
		return true;
	}
	
	//delete a product image by its id
	public function deleteImage($image_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape method arguments
		$image_id = $mysqli->real_escape_string($image_id);
		//SQL Query to select the image path by id
		$sql = "SELECT product_image_path FROM product_images_tbl WHERE product_image_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('i', $image_id);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: " . $mysqli->errno . " - " . $mysqli->error;
		}else{
			//else bind the result
			$stmt->bind_result($file_name);
			//and store it
			$stmt->store_result();
			//fetch the result data
			$stmt->fetch();
			//set the image directory
			$img_dir = '../images/products/';
			//get the full path
			$path = $img_dir.$file_name;
			//unlink the file
			unlink($path);
			//SQL Query to delete the image from the database
			$sql = "DELETE FROM product_images_tbl WHERE product_image_id = ?";
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			//bind parameters to the query
			$stmt->bind_param('i', $image_id);
			if (!$stmt->execute()) {
				//if execution fails return error
				return "Error: " . $mysqli->errno . " - " . $mysqli->error;
			}
		}
		//return success
		return true;
	}
	
	//update the product image display order
	public function updateImageOrder($order, $image_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//escape the method arguments
		$order = $mysqli->real_escape_string($order);
		$image_id = $mysqli->real_escape_string($image_id);
		//SQL Query to update the product image order
		$sql = "UPDATE product_images_tbl SET product_image_order = ? WHERE product_image_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('ii',$order, $image_id);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: " . $mysqli->errno . " - " . $mysqli->error;
		}
		//return success
		return true;
	}
	
	//get the products category name
	public function getCategoryName(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);	
		//escape class variable
		$category_id = $mysqli->real_escape_string($this->category_id);
		//SQL Query to select the category name by id
		$sql = "SELECT category_name FROM category_tbl WHERE category_id = ?";		
		//prepare the statement		
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query	
		$stmt->bind_param('i',$category_id);
		//instantiate input
		$input = '';
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			//bind the result
			$stmt->bind_result($category_name);
			//and store it	
			$stmt->store_result();
			//fetch the result
			while($stmt->fetch()){
				//return the category name
				return $category_name;
			}
		}
	}
	
	//set the products attributes
	public function getProductAttributes(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);	
		//instantiate product attribute array
		$product_attributes = array();
		//escape the class variable
		$product_id = $mysqli->real_escape_string($this->product_id);
		//SQL Query to select product specifics
		$sql = "SELECT product_specific_name, product_specific_value, product_specific_type FROM product_specific_tbl WHERE product_id = ? ORDER BY product_specific_order ASC";				
		//prepare the statement
		$stmt = $mysqli->prepare($sql);	
		//bind the parameters to query
		$stmt->bind_param('i',$product_id);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			//bind the result
			$stmt->bind_result($specific_name, $specific_value, $specific_type);	
			//and store it
			$stmt->store_result();
			while($stmt->fetch()){//fetch the results
				//instantiate the product attribute array
				$product_attribute = array();
				//set value and type
				$product_attribute['value'] = $specific_value;
				$product_attribute['type'] = $specific_type;
				//add the attrbiutes array
				$product_attributes[$specific_name] = $product_attribute;
			}
			//set attributes array as a class variable
			$this->ebay_attributes = $product_attributes;
		}
	}
	
	//set the product images
	public function getProductImages(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);	
		//escape the class variable
		$product_id = $mysqli->real_escape_string($this->product_id);
		//SQL Query to select the products images
		$sql = "SELECT product_image_id, product_image_path FROM product_images_tbl WHERE product_id = ? ORDER BY product_image_order ASC";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $product_id);
		if (!$stmt->execute()) {
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			//bind the result
			$stmt->bind_result($product_image_id, $product_image_path);
			//and store it
			$stmt->store_result();
			//instantiate product array
			$product_images = array();
			while($stmt->fetch()){
				//fetch the result and add it to the images array
				$product_images[$product_image_id] = $product_image_path;
			}
			//assign images array to the images class variable
			$this->images = $product_images;
		}
	}
}
?>