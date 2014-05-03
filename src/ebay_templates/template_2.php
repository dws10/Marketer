<?php

include('../class/Global.php');
include('../class/Employee.php');
include('../class/Store.php');
include('../class/Listing.php');
include('../class/Product.php');
include('../class/Category.php');
include('../class/EbayCategory.php');
include('../class/ReturnTemplate.php');
include('../class/ShippingTemplate.php');

$listing_id = $_GET['listing_id'];

$employee = new Employee();
$employee->authenticateSession();

$store = new Store($employee->storeID);

$listing = $store->getListing($listing_id);

$product = $listing->product;

$title = $product->title;
$description = $product->description;
$condition_description = $listing->conditionDetails;
$images = $product->images;

$ebay_attributes = $product->ebay_attributes;
//var_dump($listing);
$return_template = $listing->returnTemplate;



$return_policy = $return_template->description;
?>
<style>
.template-container{
	width:100%;
	background-color:#DDE6FF;
	overflow:auto;
}
.template-container .template-wrapper{
	width:750px;
	margin:10px auto;
	background-color:#DDE6FF;
	overflow:auto;
	border:1px solid #CCC;
	border-top:0;
	border-bottom:0;
}
.template-container .product-title{
	background-color:#DDE6FF;
	width:100%;
}
.template-container .left{
	width:380px;
	float:left;
	padding:10px;	
	background-color:#DDE6FF;
}
.template-container .right{
	width:330px;
	float:left;	
	padding:10px;
	background-color:#DDE6FF;
}
.template-container .right img{
	width:100%;
	height:auto;	
	margin-bottom:10px;
}
.template-container .right div{
	padding-bottom:15px;
}
.template-container table{
	margin:0 auto;
	min-width:75%;
	margin-bottom:20px;	
}
.template-container table tr{
	border-bottom:1px solid #000;
}
.template-container h1, .template-container h2{
	text-align:center;
	margin-top:	
}
.template-container h1, .template-container h2, .template-container p{
	margin:0;
	font-weight:500;
	line-height:1;
	padding-bottom:20px;
	font-family:Arial, Helvetica, sans-serif;
}


</style>
<div class="template-container">
    <div class="template-wrapper">
        <h1 class="product-title"><?php echo $title; ?></h1>
        <div class="left">
         <h2>Description</h2>
            <div class="product-description">
                <p>
                    <?php echo $description; ?>
                </p>
            </div>
            <h2>Details</h2>
            <div class="product-details">
                <?php
                    echo "<table>";
                    foreach($ebay_attributes as $name => $attribute){
                        echo "<tr>";
                        echo "<td>".$name."</td>";
                        echo "<td>".$attribute['value']."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                ?>
            </div>
            <h2>Condition</h2>
            <div class="product-condition">
            	<p>
					<?php
                        echo $condition_description;
                    ?>
                </p>
            </div>
            <h2>Return Policy</h2>
            <div class="product-details">
                <p>
                    <?php 
						echo $return_policy; 
					?>
                </p>
            </div>
               
        </div>
        <div class="right">
            <div class="image-gallery">
                <?php
                    foreach($images as $id => $path){
                        echo "<img src='http://localhost/marketer/images/products/".$path."' alt='".$title."' title='".$title."'/>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>