<?PHP
class GetCategoryFeaturesRequest{
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
	
	public function getCategoryConditions($category_id){
		//get the ebay conditions available to a category by category id
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategoryFeatures.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
		$requestXML .= "<GetCategoryFeaturesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
		  $requestXML .= "<RequesterCredentials>";
			$requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
		  $requestXML .= "</RequesterCredentials>";
		  $requestXML .= "<WarningLevel>High</WarningLevel>";
		  $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
		  $requestXML .= "<CategoryID>".$category_id."</CategoryID>";
		  $requestXML .= "<FeatureID>ConditionValues</FeatureID>";
		$requestXML .= "</GetCategoryFeaturesRequest>";
		return $requestXML;
	}
	
	public function getAllConditions(){
		//get all ebay conditions
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategoryFeatures.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
		$requestXML .= "<GetCategoryFeaturesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
		  $requestXML .= "<RequesterCredentials>";
			$requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
		  $requestXML .= "</RequesterCredentials>";
		  $requestXML .= "<WarningLevel>High</WarningLevel>";
		  $requestXML .= "<ViewAllNodes>True</ViewAllNodes>";
		  $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
		  $requestXML .= "<FeatureID>ConditionValues</FeatureID>";
		$requestXML .= "</GetCategoryFeaturesRequest>";
		return $requestXML;
	}
		
	public function getListingDurations($id){
		//get listing durations for a specific category by id
		//http://developer.ebay.com/DevZone/XML/docs/reference/ebay/GetCategoryFeatures.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
		  $requestXML .= "<GetCategoryFeaturesRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
			  $requestXML .= "<RequesterCredentials>";
			  	$requestXML .= "<eBayAuthToken>".$this->getToken()."</eBayAuthToken>";
			  $requestXML .= "</RequesterCredentials>";
			  $requestXML .= "<DetailLevel>ReturnAll</DetailLevel>";
			  $requestXML .= "<ViewAllNodes>True</ViewAllNodes> ";
			  $requestXML .= "<CategoryID>".$id."</CategoryID>";
			  $requestXML .= "<FeatureID>ListingDurations</FeatureID>";
		$requestXML .= "</GetCategoryFeaturesRequest>";
		return $requestXML;
	}
}
?>