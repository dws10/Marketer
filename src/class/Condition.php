<?PHP
class Condition{
	//eBay condition attributes
	public $ebayConditionID;
	public $ebayConditionName;
	public $conditionDescription;
	
	/*
	* @constructor
	* @param int $id the condition ebay id
	* @param int $name the condition ebay name
	* @param int $desc the condition description
	*/	
	public function __construct($id, $name, $desc){
		setEbayConditionID($id);
		setEbayConditionName($name);
		setConditionDescription($desc);
	}
	/*
	* @method setEbayConditionID sets the ebay condition id
	* @param int $id the condition id from ebay
	*/
	private function setEbayConditionID($id){
		$this->ebayConditionID = $id;
	}
	/*
	* @method setEbayConditionName sets the ebay condition name
	* @param String $name the condition name from ebay
	*/
	private function setEbayConditionName($name){
		$this->ebayConditionName = $name;
	}
	/*
	* @method setConditionDescription sets the additional description information
	* @param String $desc the extra condition details
	*/
	private function setConditionDescription($desc){
		$this->conditionDescription = $desc;
	}
}
?>
