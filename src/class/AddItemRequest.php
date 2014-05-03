<?PHP
class AddItemRequest {
	private $product; //the Product
	private $description; //the DisplayTemplate
	private $listing; //the Listing
	private $store; //the Store
	
	private $site; //the site to list to 'UK'
	private $country; //the country of listing 'GB'

	private $listingType; //eBay listing type
	
	private $currency; //currency type 'GBP'
	private $paymentMethod; //payment method 'PayPal'
	private $paypalEmail; //seller PayPal email address
	
	private $returnAccepted; //returns are allowed for most categories
	private $returnType; //returns are always 'MoneyBack'
	private $returnTemplate; //the ReturnTemplate

	private $shippingPriority; //Priority always 0 as we currently only allow for one shipping method per listing
	private $shippingTemplate; //the ShippingTemplate
	
	public function __construct($listing){
		//constructor sets class variables
		$this->listing = $listing;
		$this->product = $listing->product;
		$this->store = $listing->store;

		$this->description = $listing->getDisplayTemplateContent();
	
		//location details
		$this->site = 'UK';
		$this->country = 'GB';
		$this->postcode = $store->seller_address['postcode'];
		
		$this->listingType = 'FixedPriceItem';

		//payment details
		$this->paymentMethod = 'PayPal';
		$this->currency = 'GBP';
		
		//return details
		$this->returnAccepted = 'ReturnsAccepted';
		$this->returnType = 'MoneyBack';
		$this->returnTemplate =  $listing->returnTemplate;
		
		//shipping details
		$this->shippingPriority = '0';
		$this->shippingTemplate = $listing->shippingTemplate;
		
	}
	
	public function VerifyRequest(){
		//request to verify adding a new item
		//http://developer.ebay.com/devzone/xml/docs/reference/ebay/VerifyAddItem.html
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
		$requestXML .= "<VerifyAddItemRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
			$requestXML .= "<RequesterCredentials>";
				$requestXML .= "<eBayAuthToken>".$this->store->auth_token."</eBayAuthToken>";
			$requestXML .= "</RequesterCredentials>";
			$requestXML .= "<Item>";
				$requestXML .= "<Title>".$this->product->title."</Title>";
				//wrap the description with CDATA tags to pass the HTML content 
				$requestXML .= "<Description><![CDATA[".$this->description."]]></Description>";
				$requestXML .= "<PictureDetails>";
					$requestXML .= "<GalleryType>Gallery</GalleryType>";
					//get images for the product
					$this->listing->product->getProductImages();
					foreach ($this->product->images as $image){
						$requestXML .= "<PictureURL>http://localhost/marketer/images/product/".$image."</PictureURL>";
					}
				$requestXML .= "</PictureDetails>";
				$requestXML .= "<StartPrice>".$this->listing->price."</StartPrice>";
				$requestXML .= "<ConditionID>".$this->listing->conditionCode."</ConditionID>";
				//if condition code allows for condition description
				//see Item.ConditionDescription
				if($this->listing->conditionCode > 1499 || $this->listing->conditionCode < 1000){
					$requestXML .= "<ConditionDescription>".$this->product->conditionTemplate->description."</ConditionDescription>";
				}
					$requestXML .= "<PrimaryCategory>";
						
						$ebay_category = $this->store->getCategory($this->product->product_id)->ebay_category->real_id;
						$requestXML .= "<CategoryID>".$ebay_category."</CategoryID>";
						
					$requestXML .= "</PrimaryCategory>";
				$requestXML .= "<Country>".$this->country."</Country>";
				$requestXML .= "<Currency>".$this->currency."</Currency>";
				$requestXML .= "<DispatchTimeMax>".$this->shippingTemplate->dispatch_time."</DispatchTimeMax>";
				
				//loop out the ebay attributes
				$attributes = $this->product->ebay_attributes;
				if(count($attributes)>0){
					$requestXML .= "<ItemSpecifics>";
					foreach($attributes as $name => $attribute){
							$requestXML .= " <NameValueList>";
								$requestXML .= "<Name>".$name."</Name>";
								$requestXML .= "<Value>".$attribute['value']."</Value>";
							$requestXML .= "</NameValueList>";
					}
					$requestXML .= "</ItemSpecifics>";
				}
			
				$requestXML .= "<ListingDuration>".$this->listing->duration."</ListingDuration>";
				$requestXML .= "<ListingType>".$this->listingType."</ListingType>";
				$requestXML .= "<PaymentMethods>".$this->paymentMethod."</PaymentMethods>";
				$requestXML .= "<PayPalEmailAddress>".$this->store->paypal_email."</PayPalEmailAddress>";
				$requestXML .= "<PostalCode>".$this->postcode."</PostalCode>";
				$requestXML .= "<Quantity>".$this->listing->quantity."</Quantity>";
				$requestXML .= "<ReturnPolicy>";
					$requestXML .= "<ReturnsAcceptedOption>".$this->returnAccepted."</ReturnsAcceptedOption>";
					$requestXML .= "<RefundOption>".$this->returnType."</RefundOption>";
					$requestXML .= "<ReturnsWithinOption>".$this->returnTemplate->duration."</ReturnsWithinOption>";
					$requestXML .= "<Description>".$this->returnTemplate->description."</Description>";
				$requestXML .= "</ReturnPolicy>";
				$requestXML .= "<ShippingDetails>";
					$requestXML .= "<ShippingServiceOptions>";
						$requestXML .= "<ShippingServicePriority>".$this->shippingPriority."</ShippingServicePriority>";
						$requestXML .= "<ShippingService>".$this->shippingTemplate->service_id."</ShippingService>";
						$requestXML .= "<ShippingServiceCost>".$this->shippingTemplate->service_cost."</ShippingServiceCost>";
						$requestXML .= "<ShippingServiceAdditionalCost>".$this->shippingTemplate->service_additional_cost."</ShippingServiceAdditionalCost>";
					$requestXML .= "</ShippingServiceOptions>";
				$requestXML .= "</ShippingDetails>";
				$requestXML .= "<Site>".$this->site."</Site>";
			$requestXML .= "</Item>";
		$requestXML .= "</VerifyAddItemRequest>";

		return $requestXML;
	}
	
	public function ConfirmRequest(){
		//add the item after the listing has been verified
		//http://developer.ebay.com/devzone/xml/docs/reference/ebay/additem.html
		//structure similar to verify add item
		$requestXML = "<?xml version='1.0' encoding='utf-8'?>";
		$requestXML .= "<AddItemRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
			$requestXML .= "<RequesterCredentials>";
				$requestXML .= "<eBayAuthToken>".$this->store->auth_token."</eBayAuthToken>";
			$requestXML .= "</RequesterCredentials>";
			$requestXML .= "<Item>";
				$requestXML .= "<Title>".$this->product->title."</Title>";
				$requestXML .= "<Description><![CDATA[".$this->description."]]></Description>";
				$requestXML .= "<PictureDetails>";
					$requestXML .= "<GalleryType>Gallery</GalleryType>";
					$this->listing->product->getProductImages();
					foreach ($this->product->images as $image){
						$requestXML .= "<PictureURL>http://localhost/marketer/images/product/".$image."</PictureURL>";
					}
				$requestXML .= "</PictureDetails>";
				$requestXML .= "<StartPrice>".$this->listing->price."</StartPrice>";
				$requestXML .= "<ConditionID>".$this->listing->conditionCode."</ConditionID>";
			if($this->listing->conditionCode > 1499 || $this->listing->conditionCode < 1000){
				$requestXML .= "<ConditionDescription>".$this->product->conditionTemplate->description."</ConditionDescription>";
			}
					$requestXML .= "<PrimaryCategory>";
						
						$ebay_category = $this->store->getCategory($this->product->product_id)->ebay_category->real_id;
						$requestXML .= "<CategoryID>".$ebay_category."</CategoryID>";
						
					$requestXML .= "</PrimaryCategory>";
				$requestXML .= "<Country>".$this->country."</Country>";
				$requestXML .= "<Currency>".$this->currency."</Currency>";
				$requestXML .= "<DispatchTimeMax>".$this->shippingTemplate->dispatch_time."</DispatchTimeMax>";
				
			$attributes = $this->product->ebay_attributes;
			if(count($attributes)>0){
				$requestXML .= "<ItemSpecifics>";
				foreach($attributes as $name => $attribute){
						$requestXML .= " <NameValueList>";
							$requestXML .= "<Name>".$name."</Name>";
							$requestXML .= "<Value>".$attribute['value']."</Value>";
						$requestXML .= "</NameValueList>";
				}
				$requestXML .= "</ItemSpecifics>";
			}
		
				$requestXML .= "<ListingDuration>".$this->listing->duration."</ListingDuration>";
				$requestXML .= "<ListingType>".$this->listingType."</ListingType>";
				$requestXML .= "<PaymentMethods>".$this->paymentMethod."</PaymentMethods>";
				$requestXML .= "<PayPalEmailAddress>".$this->store->paypal_email."</PayPalEmailAddress>";
				$requestXML .= "<PostalCode>".$this->postcode."</PostalCode>";
				$requestXML .= "<Quantity>".$this->listing->quantity."</Quantity>";
				$requestXML .= "<ReturnPolicy>";
					$requestXML .= "<ReturnsAcceptedOption>".$this->returnAccepted."</ReturnsAcceptedOption>";
					$requestXML .= "<RefundOption>".$this->returnType."</RefundOption>";
					$requestXML .= "<ReturnsWithinOption>".$this->returnTemplate->duration."</ReturnsWithinOption>";
					$requestXML .= "<Description>".$this->returnTemplate->description."</Description>";
				$requestXML .= "</ReturnPolicy>";
				$requestXML .= "<ShippingDetails>";
					$requestXML .= "<ShippingServiceOptions>";
						$requestXML .= "<ShippingServicePriority>".$this->shippingPriority."</ShippingServicePriority>";
						$requestXML .= "<ShippingService>".$this->shippingTemplate->service_id."</ShippingService>";
						$requestXML .= "<ShippingServiceCost>".$this->shippingTemplate->service_cost."</ShippingServiceCost>";
						$requestXML .= "<ShippingServiceAdditionalCost>".$this->shippingTemplate->service_additional_cost."</ShippingServiceAdditionalCost>";
					$requestXML .= "</ShippingServiceOptions>";
				$requestXML .= "</ShippingDetails>";
				$requestXML .= "<Site>".$this->site."</Site>";
			$requestXML .= "</Item>";
		$requestXML .= "</AddItemRequest>";

		return $requestXML;

		return $requestXML;
	}
}	
?>