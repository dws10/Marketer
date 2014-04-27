<?php
//set execution time as default is too small
ini_set("max_execution_time", 0);

//api token
$userToken = 'AgAAAA**AQAAAA**aAAAAA**Pn1dUg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhC5CEpgqdj6x9nY+seQ**t2ECAA**AAMAAA**ulUItqB7hQ3KXKSBuCWcUtq5WkHBkz5goPi9dynkW/E+bqb0qrAcB2/Fhv6nZWvpQDjVaKdxdh6IGX5OzClXFa+Xrg0e/bdjOJcc1o2HRPSrzboagGOK1NB08/OkbVpbWYJKsqAw5KKfh7E5junzTj/mfHvK7Jfn32xXiFMFRJUp/MVe5cBZ0flTlTbImwRU6mMJUZrFJpfsJFblaMq7lyBNMcQCANM/Y2gS095o4cRVHF6htwldBOmdJO7vTwudtNo+nzvegSkUYDt48fKr1n/rR26A2EDh8rTMkwcwTAygz4r7Mwtw/MzOWW5EXHRM32TsWLUldBCNW6G+jWxxCwKVsQPdoqKJy+LB6YkCXbj/71JvRk1nYnbOWHbXG9/uwI1nc4Hxen5YepsRhbfPv4O3ihTvh7MB6Z16EAKxpJQaObXZGOXoYpHqc2hMolUEqrQkcCB30bpTCu+Qp9aKvYMAefFx/mBHMwOcQ4Mlz2oaDX9AHTRdug2Le0YmG1lTUh+/PA1OJ6BQkYs0H01xJU6vWj+bEYxCYU1zQo0XEPi/yB8/+bqQwrlT7o6sZ1cXtbO5bAQYG6NZ2EhSU+SdRcytgaaOVs4GDZbqhPL8pVcHWpbQRy8/5kqsbWRsL+lMfoXPGze0FqhhiKI24jHic8/sVJFCAB/G/BYgT9xH5IRFvaVD60IHxE2pjZEuXN6HET5W8VBCLuj594izIYj6epy3pzmO5vHQnQwDu3UIU+x3YAuTp7/CzRw0aFjfGtky';

//include ebay gatewya and session classes
include("../class/eBaySession.php");
include("../class/EbayGateway.php");

//instantiate ebay gateway
$gateway = new EbayGateway('sandbox');

//provide usertoken, site id 3 = uk and api action
$gateway->SetUserToken($userToken);
$gateway->SetSiteID('3');
$gateway->SetAction('GetCategories');

//open new ebay session
$session = $gateway->OpenSession();
		
//include categoryRequest class
include('../class/GetCategoryRequest.php');
//instantiate a new get category request
$xmlRequest = new GetCategoryRequest($userToken);
//send the request and recieve the response			
$response = $session->sendHttpRequest($xmlRequest->getAllCategories());
//load the xml response
$xmlResponse = simplexml_load_string($response);

//connect to db
include('../class/Global.php');

//get category version
$category_version = $xmlResponse->Version;

$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);

//sql to insert version into database
$sql = "INSERT INTO ebay_category_version_tbl (ebay_category_version_id)VALUES(?)";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('i',$category_version);


//run sql	
if (!$stmt->execute()) {
	//if failure echo error
    echo "Failed to add category version: (" . $mysqli->errno . ") " . $mysqli->error.'<br/>'.$sql.'<br/><br/>';
};

//loop through xml category data
foreach ($xmlResponse->CategoryArray->Category as $Category){
	$CategoryName = $Category->CategoryName; //get name
	$CategoryID = $Category->CategoryID; //id
	$CategoryParentID = $Category->CategoryParentID; //parent id
	$CategoryLevel = $Category->CategoryLevel; //level
	
	//if the category is not expired
	if(!isset($Category->Expired)){
		
		//get leaf category status true or false
		$LeafCategory = 0; 
		if(isset($Category->LeafCategory)){
			$LeafCategory = 1;	
		}
		
		//prepare the statement to insert category data
		$stmt = $mysqli->prepare("INSERT INTO ebay_category_tbl (ebay_category_real_id, ebay_category_name, ebay_category_parent_id, ebay_category_level, ebay_category_leaf)VALUES(?,?,?,?,?)");
		
		//bind parameters
		$stmt->bind_param('isiii', $CategoryID, $CategoryName, $CategoryParentID, $CategoryLevel, $LeafCategory);
	
		//execute prepared statement
		if (!$stmt->execute()) {
			//if failure echo error
			echo "Failed to add ebay category: (" . $mysqli->errno . ") " . $mysqli->error.'<br/>'.$sql.'<br/><br/>';
		}
	}
}

?>