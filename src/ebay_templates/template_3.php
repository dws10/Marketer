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
	margin:0;
	padding:0;
	width:750px;
	text-align:center;
}
.template-container .image-gallery{
	width:100%;	
}
.template-container .image-gallery .left{
	width:355px;
	float:left;
	padding:10px;
}
.template-container  .image-gallery .left img{
	width:100%;
	height:auto;	
	margin-bottom:10px;
}
.template-container  .image-gallery .right{
	width:355px;
	float:left;
	padding:10px;
}
.template-container  .image-gallery .right img{
	width:100%;
	height:auto;	
	margin-bottom:10px;
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
        <div>
            <div class="image-gallery">
                <div class="left">
					<?php
						$i = 0;
                        foreach($images as $id => $path){
                            if($i == 0){
                                echo "<img src='http://localhost/marketer/images/products/".$path."' alt='".$title."' title='".$title."'/>";
                            }
							$i++;
                        }
                    ?>
                </div>
                <div class="right">
					<?php
						$i = 0;
                        foreach($images as $id => $path){
                            if($i == 1){
                                echo "<img src='http://localhost/marketer/images/products/".$path."' alt='".$title."' title='".$title."'/>";
                            }
							$i++;
                        }
                    ?>
                </div>
            </div>
        </div>
        <div>
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
        <div>
            <div class="image-gallery">
                  <?php
                      $position = 'left';
                      $i = 0;
                      foreach($images as $id => $path){
                          if($i != 0 && $i != 1){
							  echo $i;
                              if($position == 'left'){
                                  echo "<div class='left'>";
                                      echo "<img src='http://localhost/marketer/images/products/".$path."' alt='".$title."' title='".$title."'/>";
                                  echo "</div>";
                                  $position = 'right';
                              }else{
                                  echo "<div class='right'>";
                                      echo "<img src='http://localhost/marketer/images/products/".$path."' alt='".$title."' title='".$title."'/>";
                                  echo "</div>";
                                  $position = 'left';
                              }
                          }
                          $i++;
                      }
                  ?>
            </div>
        </div>
    </div>
</div>