<?PHP
class EndItemRequest {
	private $userToken;//the stores eBay Authentication Token
	private $reason;//reason for ending the listing
	private $item_id;//item id for the listing
	
	public function __construct($token, $reason, $itemID){
		//constructor sets class variables
		$this->setToken( $token );
		$this->reason = $reason;
		$this->item_id = $itemID;
	}
	
	private function setToken($token){
		//set request token
		$this->userToken = $token;
	}
	
	private function getToken(){
		//get request token
		$token = $this->userToken;
		//return it
		return $token;
	}
	
	public function endItem(){
		//End an listing on eBay
		//http://developer.ebay.com/devzone/xml/docs/reference/ebay/EndItem.html#Samples
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
		$requestXML .= "<EndItemRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
		  $requestXML .= "<RequesterCredentials>";
			$requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
		  $requestXML .= "</RequesterCredentials>";
		 $requestXML .= "<ItemID>".$this->item_id."</ItemID>";
		  $requestXML .= "<EndingReason>".$this->reason."</EndingReason>";
		$requestXML .= "</EndItemRequest>";
		return $requestXML;
	}
}
?>