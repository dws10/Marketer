<?PHP
class Condition{
	//eBay condition attributes
	public $ebayConditionID;
	public $ebayConditionName;
	public $conditionDescription;
	
	//setup condition	
	public function __construct($id, $name, $desc){
		//constructor populates class variables
		$this->ebayConditionID = $id;
		$this->ebayConditionName = $name;
		$this->conditionDescription = $desc;
	}
}
?>