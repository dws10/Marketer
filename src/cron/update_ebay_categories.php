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
$xmlRequest = new getCategoryRequest($userToken);
//send the request and recieve the response			
$response = $session->sendHttpRequest($xmlRequest->getVersion());
//load the xml response
$xmlResponse = simplexml_load_string($response);

//connect to db
include('../class/Global.php');
$mysqli = new mysqli(SQL_HOST,SQL_USER,SQL_PASS,SQL_DB);

//get category version
$category_version = $xmlResponse->Version;

//sql to select version from database
$stmt = $mysqli->prepare("SELECT ebay_category_version_id FROM ebay_category_version_tbl");
	
if (!$stmt->execute()) {
	//if failure echo error
    echo "Failed to add category: (" . $mysqli->errno . ") " . $mysqli->error.'<br/>'.$sql.'<br/><br/>';
}else{
	$stmt->bind_result($ebay_category_version_id);
	$stmt->fetch();
	if($ebay_category_version_id == $category_version ){
		echo 'Category version is up to date.';	
	}else{
		echo 'Category version is outdated, retreieving category mapping now';	
		
		
		$gateway->SetAction('GetCategoryMappings');
		//open new ebay session
		$session = $gateway->OpenSession();
		//instantiate a new get category request
		$xmlRequest = new getCategoryRequest($userToken);
		//send the request and recieve the response			
		$response = $session->sendHttpRequest($xmlRequest->getCategoryMapping($row['ebay_category_version_id']));
		//load the xml response
		$xmlResponse = simplexml_load_string($response);
		
		foreach ($xmlResponse->CategoryMapping as $CategoryMapping){
			$oldID = $CategoryMapping->attributes()->oldID;
			$newID = $CategoryMapping->attributes()->id;
			
			$stmt = $mysqli->prepare("UPDATE ebay_categories_tbl SET ebay_category_real_id = ? WHERE ebay_category_real_id = ?");
			$stmt->bind_param('ii',$newID, $oldID);
			if (!$stmt->execute()) {
				//if failure echo error
				echo "Failed to update categoryID: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
			};
					
			$stmt = $mysqli->prepare("UPDATE ebay_categories_tbl SET ebay_category_parent_id = ? WHERE ebay_category_parent_id = ?");
			$stmt->bind_param('ii',$newID, $oldID);
			if (!$stmt->execute()) {
				//if failure echo error
				echo "Failed to update category parentID: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
			};
		}
		
		$version = $xmlResponse->Version;
		
		$stmt = $mysqli->prepare("UPDATE ebay_category_version_tbl SET ebay_category_version_id = ?");
		$stmt->bind_param('i',$version);
		if (!$stmt->execute()) {
			//if failure echo error
			echo "Failed to update category version: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
		};
		
	}
}
?>