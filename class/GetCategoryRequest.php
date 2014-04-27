<?PHP
class GetCategoryRequest {
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
	
	public function getTopLevelCategories($siteID){
		//get eBay's top level categories
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategories.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategoriesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<CategorySiteID>".$siteID."</CategorySiteID>";
          $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
          $requestXML .= "<LevelLimit>1</LevelLimit>";
        $requestXML .= "</GetCategoriesRequest>";
		return $requestXML;
	}
	
	public function getChildCategories($parent){
		//get the child categories of a parent
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategories.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategoriesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<CategoryParent>".$parent."</CategoryParent>";
          $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
          $requestXML .= "<LevelLimit>2</LevelLimit>";
        $requestXML .= "</GetCategoriesRequest>";
		return $requestXML;
	}
	
	public function getAllCategories(){
		//get all categories from eBay
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategories.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategoriesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<CategorySiteID>3</CategorySiteID>";
          $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
        $requestXML .= "</GetCategoriesRequest>";
		return $requestXML;
	}
	
	public function getCategory($id){
		//get an eBay category by its id
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategories.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategoryInfoRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<CategoryID>".$id."</CategoryID>";
        $requestXML .= "</GetCategoryInfoRequest>";
		return $requestXML;
	}
	
	public function getVersion(){
		//Get the ebay category version id
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategories.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>"; 
        $requestXML .= "<GetCategoriesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>"; 
          $requestXML .= "<RequesterCredentials>"; 
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>"; 
          $requestXML .= "</RequesterCredentials>"; 
        $requestXML .= "</GetCategoriesRequest>"; 
		return $requestXML;
	}
	
	public function getCategoryMapping($current_version){
		//Get the mapping for category updates
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategories.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
        $requestXML .= "<GetCategoryMappingsRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
          $requestXML .= "<RequesterCredentials>";
            $requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
          $requestXML .= "</RequesterCredentials>";
          $requestXML .= "<WarningLevel>High</WarningLevel>";
          $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
          $requestXML .= "<CategoryVersion>".$current_version."</CategoryVersion>";
        $requestXML .= "</GetCategoryMappingsRequest>";
		return $requestXML;
	}
}	
?>