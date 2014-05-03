<?php
class EbayGateway{
	public $gateway_url = '';//the url to communicate on
	
	public $devID, $appID, $certID; //our devID, appID and certID
	public $usertoken;//the seller's authentication token
	
	public $siteID;//the id for the ebay site to communicate with 'uk' = 3
	public $action;//what api call is made
	public $session;//the eBaySession 
	
	public $version;//api version
	
	public function __construct($mode = 'live'){
		//constructor configures gateway class variables
		//if mode  is 'live'
		if($mode == 'live'){
			//set live ebay url
			$this->gateway_url = '';
		}else if($mode == 'sandbox'){//if mode is sandbox
			//set sandbox url
			$this->gateway_url = 'https://api.sandbox.ebay.com/ws/api.dll';
		}else{
			//error incorrect arg
		}
		$this->SetVersion('843');//set api version
		$this->setDevID('318c9463-10b8-4fd7-80a3-8710396d38c4');//set dev id version
		$this->SetAppID('DanWhite-f631-4c59-b433-bf121d7b665b');//set app id version
		$this->SetCertID('feccebce-b7c5-46fe-ab84-89b71b1feaae');//set certificate id
	}
	
	private function SetVersion($version){
		//set the ebay api version
		$this->version = $version;
	}
	
	private function SetDevID($devID){
		//set our developer id
		$this->devID = $devID;
	}
	
	private function SetAppID($appID){
		//set our application id
		$this->appID = $appID;
	}
	
	private function SetCertID($certID){
		//set our certificate id
		$this->certID = $certID;
	}	
	
	public function SetUserToken($userToken){
		//Set the seller's authentication token
		$this->userToken = $userToken;
	}
	
	public function SetSiteID($siteID){
		//set the ID for the ebay site to communicate with (3 for uk)
		$this->siteID = $siteID;
	}
	
	public function SetAction($action){
		//assign the actions the api must perform
		$this->action = $action;
	}
	
	public function OpenSession(){
		//create a new SOAP session with eBay
		return new eBaySession($this->usertoken, $this->devID, $this->appID, $this->certID, $this->gateway_url, $this->version, $this->siteID, $this->action);
	}
}
?>