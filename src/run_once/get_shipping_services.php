<?php
//set execution time as default is too small
ini_set("max_execution_time", 0);

//api token
$userToken = 'AgAAAA**AQAAAA**aAAAAA**Pn1dUg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhC5CEpgqdj6x9nY+seQ**t2ECAA**AAMAAA**ulUItqB7hQ3KXKSBuCWcUtq5WkHBkz5goPi9dynkW/E+bqb0qrAcB2/Fhv6nZWvpQDjVaKdxdh6IGX5OzClXFa+Xrg0e/bdjOJcc1o2HRPSrzboagGOK1NB08/OkbVpbWYJKsqAw5KKfh7E5junzTj/mfHvK7Jfn32xXiFMFRJUp/MVe5cBZ0flTlTbImwRU6mMJUZrFJpfsJFblaMq7lyBNMcQCANM/Y2gS095o4cRVHF6htwldBOmdJO7vTwudtNo+nzvegSkUYDt48fKr1n/rR26A2EDh8rTMkwcwTAygz4r7Mwtw/MzOWW5EXHRM32TsWLUldBCNW6G+jWxxCwKVsQPdoqKJy+LB6YkCXbj/71JvRk1nYnbOWHbXG9/uwI1nc4Hxen5YepsRhbfPv4O3ihTvh7MB6Z16EAKxpJQaObXZGOXoYpHqc2hMolUEqrQkcCB30bpTCu+Qp9aKvYMAefFx/mBHMwOcQ4Mlz2oaDX9AHTRdug2Le0YmG1lTUh+/PA1OJ6BQkYs0H01xJU6vWj+bEYxCYU1zQo0XEPi/yB8/+bqQwrlT7o6sZ1cXtbO5bAQYG6NZ2EhSU+SdRcytgaaOVs4GDZbqhPL8pVcHWpbQRy8/5kqsbWRsL+lMfoXPGze0FqhhiKI24jHic8/sVJFCAB/G/BYgT9xH5IRFvaVD60IHxE2pjZEuXN6HET5W8VBCLuj594izIYj6epy3pzmO5vHQnQwDu3UIU+x3YAuTp7/CzRw0aFjfGtky';

//include ebay gateway and session classes
include("../class/eBaySession.php");
include("../class/EbayGateway.php");

//instantiate ebay gateway
$gateway = new EbayGateway('sandbox');

//provide usertoken, site id 3 = uk and api action
$gateway->SetUserToken($userToken);
$gateway->SetSiteID('3');
$gateway->SetAction('GetEbayDetails');

//open new ebay session
$session = $gateway->OpenSession();
		
//include categoryRequest class
include('../class/GetEbayDetailsRequest.php');
//instantiate a new get category request
$xmlRequest = new GetEbayDetailsRequest($userToken);
//send the request and recieve the response			
$response = $session->sendHttpRequest($xmlRequest->getShippingServices());
//load the xml response
$xmlResponse = simplexml_load_string($response);

//var_dump($response);

include('../class/Global.php');

//loop through xml category data
foreach ($xmlResponse->ShippingServiceDetails as $ServiceDetails){
	$service_description = $ServiceDetails->Description;
	$service_code = $ServiceDetails->ShippingService;
	$max_time = $ServiceDetails->ShippingTimeMax;
	$min_time = $ServiceDetails->ShippingTimeMin;
	
	//connect to db
	$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
	
	//prepare the statement to insert category data
	$stmt = $mysqli->prepare("INSERT INTO ebay_shipping_tbl (ebay_shipping_description, ebay_shipping_code, ebay_shipping_max_days, ebay_shipping_min_days)VALUES(?,?,?,?)");
	
	//bind parameters
	$stmt->bind_param('ssii', $service_description, $service_code, $max_time, $min_time);

	//execute prepared statement
	if (!$stmt->execute()) {
		//if failure echo error
		echo "Error: (" . $mysqli->errno . ") " . $mysqli->error.'<br/>'.$sql.'<br/><br/>';
	}else{
		echo "Added Shipping Service: ". $service_description . ' - ' . $service_code . ' - ' . $max_time . ' - ' . $min_time . '<br/>';	
	}

}

?>