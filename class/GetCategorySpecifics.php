<?PHP

class GetCategorySpecifics {
	
	private $userToken;
	
	public function __construct($token){
		//set the product object from args to class variable 

		//set request token
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
	
	public function getCategorySpecifics($categoryID){
		
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategorySpecificsRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<CategorySpecific>";
            $requestXML .= "<CategoryID>".$categoryID."</CategoryID>";
          $requestXML .= "</CategorySpecific>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
        $requestXML .= "</GetCategorySpecificsRequest>";
		
		return $requestXML;
	}
	
	public function getCategorySpecific($categoryID, $name){
		
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategorySpecificsRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<CategorySpecific>";
            $requestXML .= "<CategoryID>".$categoryID."</CategoryID>";
          $requestXML .= "</CategorySpecific>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
		  $requestXML .= "<Name>".$name."</Name>";
        $requestXML .= "</GetCategorySpecificsRequest>";
		
		return $requestXML;
	}
	
	
	
	
}

		
?>