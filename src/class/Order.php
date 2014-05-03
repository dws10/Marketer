<?PHP
class Order{
	//Order attributes
	public $order_id; //our order ID 
	public $external_id; //external order identifier (eBay)
	public $purchase_date; //order purchase date
	public $shipping; //order shipping address associative array
	public $transactions; //transactions array of Transaction objects
	public $status; //order status
	public $store; //store object that the order belongs to
	
	//constructor sets up the order attributes
	public function __construct($order_id, $external_id, $shipping_id, $purchase_date, $status, $store){
		$this->store = $store;
		$this->order_id = $order_id;
		$this->external_id = $external_id;	
		$this->purchase_date = $purchase_date;	
		$this->status = $status;
		
		//format the datetime
		$dateTime = explode(' ',$purchase_date);
		$date = explode('-', $dateTime[0]);
		$purchase_date = $date[2].'/'.$date[1].'/'.$date[0].' '.$dateTime[1];
		//get the shipping address
		$this->shipping = $this->getShipping($shipping_id);	
		//get individual order transactions
		$this->transactions = $this->getTransactions();
	}
	
	//get the order shipping address array
	public function getShipping($shipping_id){
		//open a new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select the orders shipping address details
		$sql = "SELECT order_shipping_name, order_shipping_line_one, order_shipping_city, order_shipping_county, order_shipping_postcode, order_shipping_phone FROM orders_shipping_tbl WHERE order_shipping_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('i', $shipping_id);
		if(!$stmt->execute()){
			//is statement execution fails return the error
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//bind the result
			$stmt->bind_result($name, $line_one, $city, $county, $postcode, $phone );
			//and store it
			$stmt->store_result();
			if($stmt->num_rows == 1){
				//if num rows has one result row returned fetch the result
				$stmt->fetch();
				//build the shipping array
				$shipping = array(
					'name' => $name,
					'line_one' => $line_one,
					'city' => $city,
					'county' => $county,
					'postcode' => $postcode,
					'phone' => $phone,
				);
				//and return it
				return $shipping;
			}
		}		
	}
	
	//get an orders individual transactions as an array
	public function getTransactions(){
		//open new mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL Query to select the transaction data
		$sql = "SELECT order_transaction_id, order_transaction_email, order_transaction_ebay_id, order_transaction_listing_id, order_transaction_quantity, order_transaction_status FROM order_transaction_tbl WHERE order_transaction_order_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind parameters to the query
		$stmt->bind_param('i', $this->order_id);
		if(!$stmt->execute()){
			//if execution fails return error
			return "Error: ".$stmt->errno." - ".$stmt->error;	
		}else{
			//bind the results
			$stmt->bind_result( $order_transaction_id, $transaction_email, $transaction_ebay_id, $transaction_listing_id, $transaction_quantity, $order_transaction_status );
			//and store it
			$stmt->store_result();
			if($stmt->num_rows > 0){
				//if result rows greater than 0 instantiate the transaction array
				$transactions = array();
				$x = 0;
				while($stmt->fetch()){
					//get the transaction listing
					$listing = $this->store->getListing($transaction_listing_id);
					//fetch the result and instatiate the transaction with the listing
					$transactions[$x] = new Transaction($order_transaction_id, $transaction_email, $transaction_ebay_id, $listing, $transaction_quantity, $order_transaction_status );
					$x++;
				}
			}
			//return the transaction array
			return $transactions;
		}
	}
	
	//get the transation by its id
	public function getTransaction( $transaction_id){
		//get transaction array
		$transactions = $this->transactions;
		foreach($transactions as $transaction){
			//foreach transaction
			if($transaction->transactionID == $transaction_id){
				//if its id equals the argument id return it
				return $transaction;
			}
		}
	}
	
	//mark an order as dispatched
	public function markAsDispatched(){
		//validity flag
		$valid = true;	
		//get the stores gateway
		$gateway = $this->store->ebay_gateway;
		//set the eBay API action
		$gateway->setAction('CompleteSale');
		//open new ebay session
		$session = $gateway->OpenSession();
		//include categoryRequest class
		include('../class/CompleteSaleRequest.php');
		//instantiate a new get category request
		$xmlRequest = new CompleteSaleRequest($this->store->auth_token);
		//send the request and recieve the response			
		$response = $session->sendHttpRequest($xmlRequest->setDispatched($this->external_id));
		//load the xml response
		$xmlResponse = simplexml_load_string($response);
		if((string)$xmlResponse->Ack == 'Failure'){
			//if the response fails set validity flag as false
			$valid = false;	
		}
		//if the response was a success
		if($valid){
			//set status to dispactched
			$this->status = 'Dispatched';
			//open a new mysqli connection
			$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
			//SQL Query to update the order status
			$sql = "UPDATE orders_tbl SET order_status = ? WHERE order_id = ?";
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			//bind parameters to the query
			$stmt->bind_param('si', $this->status, $this->order_id);
			if(!$stmt->execute()){
				//if execution fails return error
				return "Error: ".$stmt->errno." - ".$stmt->error;
			}
		}
	}
}
?>