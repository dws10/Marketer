<?PHP
class Transaction{
	//transaction attriboutes
	public $transactionID;
	public $listing;//the listing that was purchased
	public $quantity;//and the quantity purchased
	public $email;//the buyers email address
	public $ebay_id;//the ebay id for the item purchased
	public $status;//the status of the order
	
	public function __construct($transactionID, $email, $ebay_id, $listing, $quantity, $status){
		//constructor sets up the class variables
		$this->transactionID = $transactionID;
		$this->listing = $listing;	
		$this->quantity = $quantity;	
		$this->email = $email;	
		$this->ebay_id = $ebay_id;
		$this->status = $status;	
	}
	
	//mark a transaction as packaged
	public function markAsPackaged(){
		//set the status
		$this->status = 'Packaged';
		//open mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL query to update the transaction status
		$sql = "UPDATE order_transaction_tbl SET order_transaction_status = ? WHERE order_transaction_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('si', $this->status, $this->transactionID);
		if(!$stmt->execute()){
			//if execution fails then return the Error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}
	}
	
	//mark a transaction as unpackaged
	public function unpackage(){
		//set the status
		$this->status = 'Awaiting Packaging';
		//open mysqli connection
		$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
		//SQL query to update the transaction status
		$sql = "UPDATE order_transaction_tbl SET order_transaction_status = ? WHERE order_transaction_id = ?";
		//prepare the statement
		$stmt = $mysqli->prepare($sql);
		//bind the parameters to the query
		$stmt->bind_param('si', $this->status, $this->transactionID);
		if(!$stmt->execute()){
			//if execution fails then return the Error
			return "Error: ".$stmt->errno." - ".$stmt->error;
		}
	}
}
?>