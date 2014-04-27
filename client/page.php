<?PHP
session_start();

require('../libs/password_api/lib/password.php');
include('../class/Global.php');
include('../class/Employee.php');
include('../class/Store.php');
include('../class/Category.php');
include('../class/EbayCategory.php');
include('../class/EbayGateway.php');
include('../class/eBaySession.php');
include('../class/GetStoreRequest.php');
include('../class/Product.php');
include('../class/GetCategorySpecifics.php');
include('../class/ReturnTemplate.php');
include('../class/ShippingTemplate.php');
include('../class/Listing.php');
include('../class/Order.php');
include('../class/Transaction.php');
//check if logged in
$employee = new Employee();
if(!$employee->authenticateSession()){
	header('Location: ../');	
}

$store = new Store($employee->storeID);

//require smarty
require_once('../libs/Smarty-3.1.16/libs/Smarty.class.php');

//instantiate smarty
$smarty = new Smarty();

//setup smarty directories
$smarty->setTemplateDir('../templates');
$smarty->setConfigDir('../config');
$smarty->setCompileDir('../compile');
$smarty->setCacheDir('../cache');

//assign jsScriptDir
$smarty->assign('jsScriptDir','../js/');
//assign libraryDir
$smarty->assign('libraryDir','../libs/');

//setup menu active priority
$smarty->assign('dashboardActive','');
$smarty->assign('inventoryActive','');
$smarty->assign('templatesActive','');
$smarty->assign('listingsActive','');
$smarty->assign('shippingActive','');
$smarty->assign('supportActive','');
$smarty->assign('accountActive','');

//assign username to welcome message
$smarty->assign('username',$employee->forename.' '.$employee->surname);

//require the xajax library
require_once("../libs/xajax-beta-0.6/xajax_core/xajax.inc.php");

//instantiate new xajax class
$xajax = new xajax();
//debug flag (comment to disable)
$xajax->configure('debug', true);
//configure javascript uri
$xajax->configure('javascript URI','../libs/xajax-beta-0.6/');


?>
