<?PHP
class GetOrdersRequest {
	private $userToken;//the stores eBay Authentication Token
	
	public function __construct($token){
		//constructor sets class variables
		$this->setToken( $token );
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
	
	public function getOrders($from){
		//get all eBay orders from the seller between the date specified and the current time 
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetOrders.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetOrdersRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<CreateTimeFrom>".$from."</CreateTimeFrom>";
          $requestXML .= "<CreateTimeTo>".date('c')."</CreateTimeTo>";
          $requestXML .= "<OrderRole>Seller</OrderRole>";
          $requestXML .= "<OrderStatus>Completed</OrderStatus>";
        $requestXML .= "</GetOrdersRequest>";
		return $requestXML;
	}
}
?>