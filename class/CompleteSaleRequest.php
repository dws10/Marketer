<?PHP
class CompleteSaleRequest {
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
	
	public function setDispatched($orderID){
		//Set the order to dispatched in the ebay store
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/CompleteSale.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<CompleteSaleRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->userToken."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<WarningLevel>High</WarningLevel>"; 
          $requestXML .= "<OrderID>".$orderID."</OrderID>";
          $requestXML .= "<Paid>true</Paid>";
          $requestXML .= "<Shipped>true</Shipped>";
        $requestXML .= "</CompleteSaleRequest>";
                
		return $requestXML;
	}
}	
?>