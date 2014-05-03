<?PHP
class GetEbayDetailsRequest {
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
	
	public function getShippingServices(){
		//setup request to get shipping services from ebay
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GeteBayDetails.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GeteBayDetailsRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<DetailName>ShippingServiceDetails</DetailName>";
        $requestXML .= "</GeteBayDetailsRequest>";
		return $requestXML;
	}
}	
?>