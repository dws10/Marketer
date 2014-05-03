<?PHP
session_start();
//require smarty
require('./libs/password_api/lib/password.php');
require_once('./libs/Smarty-3.1.16/libs/Smarty.class.php');

include('./class/Global.php');


//instantiate smarty
$smarty = new Smarty();

//setup smarty directories
$smarty->setTemplateDir('./templates');
$smarty->setConfigDir('./config');
$smarty->setCompileDir('./compile');
$smarty->setCacheDir('./cache');

//assign jsScriptDir
$smarty->assign('jsScriptDir','./js/');
//assign libraryDir
$smarty->assign('libraryDir','./libs/');

//require the xajax library
require_once("./libs/xajax-beta-0.6/xajax_core/xajax.inc.php");

//instantiate new xajax class
$xajax = new xajax();
//debug flag (comment to disable)
$xajax->configure('debug', true);
//configure javascript uri
$xajax->configure('javascript URI','./libs/xajax-beta-0.6/');

//setup menu active states
$smarty->assign('homeActive','');
$smarty->assign('featuresActive','');
$smarty->assign('paymentActive','');
$smarty->assign('contactActive','');
$smarty->assign('registerActive','');



?>
