<?PHP
class Store{
	//class attributes
	public $store_id; //the store id
	public $store_name;
	public $admin_id; //id for admin (only 1 employee per store at the moment / always admin)
	public $auth_token; //eBay authentication token
	public $seller_address; //address associative array
	public $employees = array(); //employees
	
	public $categories = array(); //array of Category objects
	public $products = array(); //array of Product objects
	
	public $paypal_email; 
	public $listings = array(); //array of Listing objects

	public $shippingTemps = array(); //array of ShippingTemplate objects
	public $returnTemps = array(); //array of ReturnTemplate objects
	
	public $site_id;
	
	public $ebay_gateway; //the ebay api gateway object
		
	//constructor sets the store attributes from database by store id
	public function __construct($store_id){
		//set the store id
		$this->store_id = $store_id;
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);	
		//SQL Query to select store data by id
		$sql = "SELECT store_name, store_admin_id, store_auth_token, store_paypal_email FROM store_tbl WHERE store_id = ?";		
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//escape the method argument
		$store_id = $mysqli->real_escape_string($store_id);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);	
		if(!$stmt->execute()){
			//if execution error return error
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($store_name, $store_admin_id, $store_auth_token, $store_paypal_email);
			//and store it so num rows works
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows is greater than 0 fetch the result
				$stmt->fetch();
				//set the store attributes
				$this->store_name = $store_name;
				$this->admin_id = $store_admin_id;
				$this->auth_token = $store_auth_token;
				$this->site_id = 3;//site_id 3 = 'UK'
				$this->ebay_gateway = new EbayGateway('sandbox'); //setup the gateway
				$this->ebay_gateway->SetUserToken($this->auth_token);//provide authentication token
				$this->ebay_gateway->SetSiteID($this->site_id);//provide siteID
				$this->paypal_email = $store_paypal_email;
				//get the address
				$this->getAddress();
			}
		}
	}
	
	//get the stores address
	private function getAddress(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select store address data
		$sql = "SELECT store_address_building, store_address_line_one, store_address_line_two, store_address_city, store_address_county, store_address_postcode
				FROM store_address_tbl
				WHERE store_address_store_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($building, $line_one, $line_two, $city, $county, $postcode);
			//store it
			$stmt->store_result();
			//and fetch it
			$stmt->fetch();
			//setup the address array
			$seller_address = array(
				'building' => $building,
				'line_one' => $line_one,
				'line_two' => $line_two,
				'city' => $city,
				'county' => $county,
				'postcode' => $postcode,
			);
			//assign it to the seller_address class attribute
			$this->seller_address = $seller_address;
		}
	}
	
	//set all of the stores employees
	public function getEmployees(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select store employee data
		$sql = "SELECT employee_id, employee_forename, employee_surname, employee_email, employee_is_admin, employee_status
				FROM employee_tbl
				WHERE store_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$mysqli->errno." - ".$mysqli->error;	
		}else{
			//bind the result
			$stmt->bind_result($employee_id, $employee_forename, $employee_surname, $employee_email, $employee_is_admin, $employee_status);
			//and store it so that num_rows works
			$stmt->store_result();
			if($stmt->num_rows > 0){
				while($stmt->fetch()){
					//instantiate a new employee
					$employee = new Employee();
					//set the employee details
					$employee->setEmployee($employee_id, $employee_forename, $employee_surname, $this->store_id, $employee_email, $employee_is_admin, $employee_status);
					//add the employee to the stores employee array
					$this->employees[] = $employee;
				}
			}
		}
	}
	
	//get the sellers eBay store categories
	public function getSellerEbayStoreCategories(){
		//get the eBay gateway
		$gateway = $this->ebay_gateway;
		//set the action for the api call
		$gateway->SetAction('GetStore');
		//instantiate a new eBaySession
		$session = $gateway->OpenSession();
		//instantiate a new request object
		$request = new GetStoreRequest($this->auth_token);
		//send the request to ebay
		$result = $session->sendHttpRequest($request->getStoreCategories());
		//load the result into simpleXML for navigation 
		$store_cat_xml = simplexml_load_string($result);
		//instantiate the store category array
		$store_categories = array();
		if( $store_cat_xml->Ack != 'Failure'){
			//if the request doesnt fail
			foreach($store_cat_xml->Store->CustomCategories->CustomCategory as $CustomCategory){
				//get category ID and name
				$category_id = $CustomCategory->CategoryID;
				$name = $CustomCategory->Name;
				//add to store categories array
				$store_categories[(int)$category_id] = $name;
			}
		}else{
			//else return error
			return $store_cat_xml->Errors->ShortMessage;
		}
		//return the store categories 
		return $store_categories;
	}
	
	//get the top bay categories
	public function getEbayTopCategories(){
		//categories to be returned
		$categories = array();
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select the top level ebay category data
		$sql = "SELECT ebay_category_id, ebay_category_name FROM ebay_category_tbl WHERE ebay_category_level = 1";
		//run the query
		$result = $mysqli->query($sql);
		if (!$result) {
			//if failure echo error
			return "Failed to select top categories: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
		}else{
			//loop through results
			while($row = $result->fetch_assoc()){
				//adding to category array
				$categories[$row['ebay_category_id']] = $row['ebay_category_name'];	
			}
		}
		//return category array
		return $categories;
	}
	
	//get the products associated with the store 
	public function getProducts(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select products by store id
		$sql = "SELECT a.product_id, a.category_id, b.category_name, a.product_title, a.product_description 
				FROM product_tbl a
				INNER JOIN category_tbl b 
				ON a.category_id = b.category_id
				WHERE b.category_store_id = ?";
		//escape the vlass variables		
		$store_id = $mysqli->real_escape_string($this->store_id);
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{
			//else bind the results
			$stmt->bind_result($product_id, $category_id, $category_name, $product_title, $product_description);
			//and store them
			$stmt->store_result();
			//instantiate the products array
			$products = array();
			if($stmt->num_rows > 0){
				//if num rows is greater than 0 fetch the result
				while($stmt->fetch()){
					//instantiate a new product;
					$product = new Product($this->store_id);
					//set the id
					$product->setProductID($product_id);
					//set the general attributes
					$product->setProductGeneral($product_title, $product_description, $category_id);
					//set the product attributes
					$product->getProductAttributes();
					//set the product images
					$product->getProductImages();
					//assign product to the products array
					$products[$product_id] = $product;
				}
			}
			//return the products array
			return $products;
		}
	}
	
	//get a single product from thw store by id
	public function getProduct($id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select product data
		$sql = "SELECT product_id, category_id, product_title, product_description FROM product_tbl WHERE product_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//escape the method argument
		$id = $mysqli->real_escape_string($id);
		//bind the parameters to the query
		$stmt->bind_param('i', $id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{	
			//bind the result
			$stmt->bind_result($product_id, $category_id, $product_title, $product_description);
			//and store it to make num rows work
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the result
				while($stmt->fetch()){
					//instantiate a new product
					$product = new Product($this->store_id);
					//set the product id
					$product->setProductID($product_id);
					//set the general details
					$product->setProductGeneral($product_title, $product_description, $category_id);
					//get the product attributes
					$product->getProductAttributes();
					//get the product images
					$product->getProductImages();
					//return the product
					return $product;
				}
			}
		}
	}
	
	//get all the stores categories 
	public function getCategories(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select categories by store id
		$sql = "SELECT category_id, category_name, category_ebay_id, category_ebay_store_id FROM category_tbl WHERE category_store_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//escape the class variables
		$store_id = $mysqli->real_escape_string($this->store_id);
		//bind parameter to the query
		$stmt->bind_param('i', $store_id);
		//categories array
		$categories = array();
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			//bind the result
			$stmt->bind_result($id, $name, $ebay_id, $ebay_store_id);
			//and store it
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//instantiate a new Category and add to categories array
					$categories[] = new Category($id, $name, $ebay_id, $ebay_store_id);	
				}
			}else{
				return "Error: No Results";	
			}
		}
		//reverse the array
		$categories = array_reverse($categories);
		//return the categories array
		return $categories;
	}
	
	//get store categories by id
	public function getCategory($id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select category data by category id
		$sql = "SELECT category_name, category_ebay_id, category_ebay_store_id FROM category_tbl WHERE category_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}else{
			//bind the result
			$stmt->bind_result($name, $ebay_id, $ebay_store_id);
			//and store it
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				$stmt->fetch();
				//return the category
				return new Category($id, $name, $ebay_id, $ebay_store_id);	
			}else{
				return "Error: No Results";	
			}
		}
	}	
	
	//get all store return templates
	public function getReturnTemplates(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select ReturnTemplate data by store
		$sql = "SELECT return_template_id, return_template_title, return_template_duration, return_template_policy FROM return_template_tbl WHERE store_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{
			$stmt->bind_result($template_id, $title, $duration, $policy);
			//bind the result
			$stmt->store_result();
			//and store it
			$templates = array();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//instantiate a new ReturnTemplate and add to templates array
					$templates[$template_id] = new ReturnTemplate($title,$duration,$policy);
				}
			}
			//return the templates array
			return $templates;
		}
	}
	
	//get store return templates by id
	public function getReturnTemplate($id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select ReturnTemplate data by templateID
		$sql = "SELECT return_template_id, return_template_title, return_template_duration, return_template_policy FROM return_template_tbl WHERE return_template_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($template_id, $title, $duration, $policy);
			//and store it
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//instantiate new ReturnTemplate
					$returnTemplate = new ReturnTemplate($title,$duration,$policy);
					//set the template id
					$returnTemplate->setID($id);
					//return the template
					return $returnTemplate;
				}
			}
		}
	}
	
	//get all store shippingTemplates
	public function getShippingTemplates(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select ShippingTemplate data by store
		$sql = "SELECT a.shipping_template_id, a.shipping_template_title, b.ebay_shipping_description, b.ebay_shipping_code, a.shipping_template_dispatch_time, a.shipping_template_cost, a.shipping_template_additional_cost 
				FROM shipping_template_tbl a
				INNER JOIN ebay_shipping_tbl b 
				ON a.ebay_shipping_id = b.ebay_shipping_id
				WHERE a.store_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($template_id, $title, $description, $code, $dispatch_time, $cost, $additional_cost);
			//and store it
			$stmt->store_result();
			$templates = array();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//instantiate a new ShippingTemplate and add it to the array
					$templates[$template_id] = new ShippingTemplate($title,$description,$code,$dispatch_time,$cost,$additional_cost);
				}
			}
			//return the templates array
			return $templates;
		}
	}
	
	//get store shipping template by id
	public function getShippingTemplate($id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select ShippingTemplate data by template id
		$sql = "SELECT a.shipping_template_id, a.shipping_template_title, a.ebay_shipping_id, b.ebay_shipping_description,  b.ebay_shipping_code, a.shipping_template_dispatch_time, a.shipping_template_cost, a.shipping_template_additional_cost 
				FROM shipping_template_tbl a
				INNER JOIN ebay_shipping_tbl b 
				ON a.ebay_shipping_id = b.ebay_shipping_id
				WHERE a.store_id = ? AND a.shipping_template_id = ?";
		//prepare the statement		
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('ii', $this->store_id, $id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($template_id, $title, $shipping_id, $description, $code, $dispatch_time, $cost, $additional_cost);
			//and store it
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//instantiate the ShippingTemplate
					$shippingTemplate = new ShippingTemplate($title,$description,$code,$dispatch_time,$cost,$additional_cost);
					//set the id
					$shippingTemplate->setID($id);
					//return the template
					return $shippingTemplate;
				}
			}
		}
	}
	
	//get all store listings
	public function getListings(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select listing data by store
		$sql = "SELECT listing_id, listing_product_id, listing_condition_code, listing_condition_details, listing_price, listing_quantity, listing_duration, listing_shipping_id, listing_return_id, listing_display_id, listing_ebay_id, listing_activated FROM listing_tbl WHERE listing_store_id = ?";
		//prepare the statement	
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{	
			//bind the result
			$stmt->bind_result($listing_id, $product_id, $condition_code, $condition_details, $price, $quantity, $duration, $shipping_id, $return_id, $display_id, $listing_ebay_id, $listing_activated);
			//and store it
			$stmt->store_result();
			$listings = array();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//get the listings product
					$product = $this->getProduct($product_id);
					//get the listings shipping template
					$shippingTemplate = $this->getShippingTemplate($shipping_id);
					//get the listings return template
					$returnTemplate = $this->getReturnTemplate($return_id);
					//instantiate a new listing
					$listing = new Listing ( $this, $product, $condition_code, $condition_details, $price, $quantity, $duration, $shippingTemplate, $returnTemplate, $display_id);
					//set the listing id
					$listing->setListingID($listing_id);
					//set the listings ebay item id
					$listing->setItemID($listing_ebay_id);
					//set the listings activated status
					$listing->setActivated($listing_activated);
					//add to the listings array
					$listings[$listing_id] = $listing;
				}
			}
			//return listings
			return $listings;
		}
	}
	
	//get all store listings by id
	public function getListing($id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select listing data by listing id
		$sql = "SELECT listing_store_id, listing_product_id, listing_condition_code, listing_condition_details, listing_price, listing_quantity, listing_duration, listing_shipping_id, listing_return_id, listing_display_id, listing_ebay_id, listing_activated FROM listing_tbl WHERE listing_id = ?";
		//prepare the statement	
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ". $stmt->errorno . " - " . $stmt->error;	
		}else{	
			//bind the result
			$stmt->bind_result( $store_id, $product_id, $condition_code, $condition_details, $price, $quantity, $duration, $shipping_id, $return_id, $display_id, $listing_ebay_id, $listing_activated);
			//and store it
			$stmt->store_result();
			$listings = array();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				while($stmt->fetch()){
					//get the listings product
					$product = $this->getProduct($product_id);
					//get the listings shipping template
					$shippingTemplate = $this->getShippingTemplate($shipping_id);
					//get the listings return template
					$returnTemplate = $this->getReturnTemplate($return_id);
					//instantiate a new listing
					$listing = new Listing ( $this, $product, $condition_code, $condition_details, $price, $quantity, $duration, $shippingTemplate, $returnTemplate, $display_id);
					//set the listing id
					$listing->setListingID($id);
					//set the listing activated status
					$listing->setActivated($listing_activated);
					//set the listing item id
					$listing->setItemID($listing_ebay_id);
					//return the listing
					return $listing;
				}
			}
		}
	}
	
	//get all available display templates
	public function getDisplayTemplates(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to get the available display templates
		$sql = "SELECT display_template_id, display_template_path FROM display_template_tbl";
		//run the query
		$res = $mysqli->query($sql);
		if($res->num_rows > 0){
			//if num rows greater than 0 fetch the results
			$displayTemplates = array();
			while($row = $res->fetch_assoc()){
				//build the displayTemplate array
				$id = $row['display_template_id'];
				$displayTemplates[$id] = $row['display_template_path'];
			}
			//return the displayTemplate array
			return $displayTemplates;
		}
	}
	
	//get new orders from eBay for the store, add them to the database
	public function getNewOrders(){
		//get the store gateway
		$gateway = $this->ebay_gateway;
		//set the gateway action
		$gateway->setAction('GetOrders');
		//open new ebay session
		$session = $gateway->OpenSession();
		//include GetOrdersRequest class
		include('../class/GetOrdersRequest.php');
		//instantiate a new GetOrders Request
		$xmlRequest = new getOrdersRequest($this->auth_token);	
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select the highest order check date by store id
		$sql = "SELECT order_check_date FROM orders_tbl WHERE order_store_id = ".$this->store_id." ORDER BY order_check_date DESC LIMIT 1";
		//run query
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			//if num rows is greater than 0
			while($row = $result->fetch_assoc()){
				//set the time of the last check
				$last_check = $row['order_check_date'];
			}
		}else{
			//if num rows is not greater than 0 last check is a date in the past
			$last_check = '2014-01-01T12:34:56.000Z';	
		}
		//send the request and recieve the response	
		$response = $session->sendHttpRequest($xmlRequest->getOrders($last_check));
		//load the xml response
		$xmlResponse = simplexml_load_string($response);
		//foreach order
		foreach($xmlResponse->OrderArray->Order as $Order){
			//check order payment has been completed
			$payment_status = (string) $Order->CheckoutStatus->eBayPaymentStatus;
			$status = (string) $Order->CheckoutStatus->Status;
			//if the order has completed payment
			if($payment_status == 'NoPaymentFailure' && $status == 'Complete'){
				//get the order id
				$order_external_id = (string) $Order->OrderID;
				//get our store id
				$order_store_id = $this->store_id;
				//get the time of order creation
				$order_purchase_date = (string) $Order->CreatedTime;
				//get the current time stamp
				$order_check_date = date('c');
				//get shipping details
				$shipping = array(
					'name' => (string) $Order->ShippingAddress->Name,
					'line_one' => (string) $Order->ShippingAddress->Street1,
					'town' => (string) $Order->ShippingAddress->CityName,
					'county' => (string) $Order->ShippingAddress->StateOrProvince,
					'postcode' => (string) $Order->ShippingAddress->PostalCode,
					'phone' => (string) $Order->ShippingAddress->Phone,
				);
				//SQL Query to insert shipping details to database
				$sql = "INSERT INTO orders_shipping_tbl (order_shipping_name, order_shipping_line_one, order_shipping_city, order_shipping_county, order_shipping_postcode, order_shipping_phone) VALUES (?,?,?,?,?,?)";
				//prepare the statement	
				$stmt = $mysqli->prepare($sql);
				//bind the parameters to the query
				$stmt->bind_param('ssssss', $shipping['name'], $shipping['line_one'], $shipping['town'], $shipping['county'], $shipping['postcode'], $shipping['phone'] );
				if(!$stmt->execute()){
					//if execution fails return error
					return "Error: ". $stmt->errorno . " - " . $stmt->error;	
				}
				//get shipping id for last shipping data  added
				$order_shipping_id = $stmt->insert_id;
				//SQL Query to insert order details and shipping id into order table
				$sql = "INSERT INTO orders_tbl (order_external_id, order_store_id, order_shipping_id, order_purchase_date, order_check_date, order_status) VALUES (?,?,?,?,?,?)";
				//prepare the statement	
				$stmt = $mysqli->prepare($sql);
				//set order status toa waiting dispatch
				$order_status = 'Awaiting Dispatch';
				//bind the parameters to the query
				$stmt->bind_param('siisss', $order_external_id, $order_store_id, $order_shipping_id, $order_purchase_date, $order_check_date, $order_status);
				if(!$stmt->execute()){
					//if execution fails return error
					return "Error: ". $stmt->errorno . " - " . $stmt->error;	
				}
				//get the order id to add to transactions
				$transaction_order_id = $stmt->insert_id;
				//foreach transaction in the order
				foreach($Order->TransactionArray->Transaction as $Transaction){
					//get the buyer, the quantity and the ebay item id
					$transaction_email = (string) $Transaction->Buyer->Email;
					$transaction_quantity = (string) $Transaction->QuantityPurchased;
					$transaction_ebay_listing_id = (string) $Transaction->Item->ItemID;
					//SQL Query to select product and listing id from the ebay listing id
					$sql = "SELECT a.product_id, b.listing_id
							FROM product_tbl a
							INNER JOIN listing_tbl b
							ON a.product_id = b.listing_product_id 
							WHERE b.listing_ebay_id = ?";
					//prepare the statement	
					$stmt = $mysqli->prepare($sql);
					//bind the parameters to the query
					$stmt->bind_param('s',$transaction_ebay_listing_id);
					if(!$stmt->execute()){
						//if execution fails return error
						return "Error: ". $stmt->errorno . " - " . $stmt->error;	
					}else{
						//bind the result
						$stmt->bind_result($product_id, $listing_id);
						//and store it
						$stmt->store_result();
						if($stmt->num_rows > 0){
							//if num rows greater than 0 fetch the results
							$stmt->fetch();
							$transaction_product_id = $product_id;
							$transaction_listing_id = $listing_id;							
							//SQL Query to select product and listing id from the ebay listing id
							$sql = "INSERT INTO order_transaction_tbl (order_transaction_order_id, order_transaction_email, order_transaction_ebay_id, order_transaction_product_id, order_transaction_listing_id, order_transaction_quantity, order_transaction_status)VALUES(?,?,?,?,?,?,?)";
							//prepare the statement
							$stmt = $mysqli->prepare($sql);
							$transaction_status = 'Awaiting Packaging';
							//bind the parameters to the query
							$stmt->bind_param('issiiis',$transaction_order_id, $transaction_email, $transaction_ebay_listing_id, $transaction_product_id, $transaction_listing_id, $transaction_quantity, $transaction_status );
							if(!$stmt->execute()){
								//if execution fails return error
								return "Transaction Insert Error: ". $stmt->errorno . " - " . $stmt->error;	
							}
						}else{
							return "Error: could not associate with product";	
						}
					}
				}
			}
		}
	}
	
	//get all orders for the store
	public function getOrders(){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select orders by store
		$sql = "SELECT order_id, order_external_id, order_shipping_id, order_purchase_date, order_status FROM orders_tbl WHERE order_store_id = ? ORDER BY order_purchase_date DESC";
		//prepare the statement	
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('i', $this->store_id);
		$orders = array();
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($order_id, $external_id, $shipping_id, $purchase_date, $order_status );
			//and store it			
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				$i = 0;
				while($stmt->fetch()){
					//instantiate a new Order
					$order = new Order($order_id, $external_id, $shipping_id, $purchase_date, $order_status, $this);  	
					//and assign to array
					$orders[$i] = $order;
					$i++;
				}
			}
		}
		//return Orders array
		return $orders;
	}
	
	//get a stores order by order id
	public function getOrder($orderID){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select orders by order id
		$sql = "SELECT order_id, order_external_id, order_shipping_id, order_purchase_date, order_status FROM orders_tbl WHERE order_store_id = ? AND order_id = ?";
		//prepare the statement	
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('ii', $this->store_id, $orderID);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($order_id, $external_id, $shipping_id, $purchase_date, $order_status );
			//and store it
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if num rows greater than 0 fetch the results
				$i = 0;
				while($stmt->fetch()){
					//instantiate new Order and return it
					return new Order($order_id, $external_id, $shipping_id,$purchase_date, $order_status, $this); 	
				}
			}
		}
	}
	
}
?>