<?php
//include the global attributes
include("../../class/Global.php");
//assign upload directory
$uploaddir = '../../images/products/';
//Empty file array, upload not found
$result = 0; 
$filename = 'No Files';
$fileID = NULL;

if(isset($_FILES)){
	//allowable extensions
	$allowedExts = array("jpeg", "jpg", "png");	
	//get file extension
	$filename = $_FILES['image']['name'];
	$temp = explode(".", $filename);
	$extension = end($temp);
	
	if(in_array($extension, $allowedExts)){
		//image must be less than 2000000 KB
		if($_FILES["image"]["size"] < 2000000){
			//get the product id from the form values
			$product_id = $_POST['product_id'];
			//open a new mysqli connection
			$mysqli = new mysqli(SQL_HOST,SQL_USER,SQL_PASS,SQL_DB);
			//SQL Query to count the number of images
			$sql = "SELECT COUNT(product_image_id) FROM product_images_tbl WHERE product_id = ?";
			//prepare the statement
			$stmt = $mysqli->prepare($sql);
			//bind the parameters to the query
			$stmt->bind_param('i', $product_id);
			if (!$stmt->execute()) {
				//if execution fails echo error
				echo "Failed to select image count: (" . $stmt->errno . ") " . $stmt->error.'<br/><br/>';
			}
			//bind the result
			$stmt->bind_result($image_count);
			//and stoee it
			$stmt->store_result();
			//fetch the result
			$stmt->fetch();
			//check no more than 4 images
			if($image_count < 4){
				//get the temp upload location
				$temp_location = $_FILES['image']['tmp_name'];
				//create a new name using product id and random integer
				$new_unqiue_name = $product_id.'_'.mt_rand(10000000, 99999999).'.'.$extension;
				//path of file to upload
				$uploadfile = $uploaddir . basename($new_unqiue_name);
				//SQL Query to insert a new product image
				$sql = "INSERT INTO product_images_tbl (product_id, product_image_path, product_image_order)VALUES(?,?,0)";
				//prepare the statement
				$stmt = $mysqli->prepare($sql);
				//bind the parameters to the query
				$stmt->bind_param('is', $product_id, $new_unqiue_name);
				if (!$stmt->execute()) {
					//if execution failure echo error
					echo "Failed to add new image: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
				}else{
					//get the file ID as the last SQL insert
					$fileID = $stmt->insert_id;
					//include simple image class 
					include('../../class/SimpleImage.php');
					//instatiate a new simple image
					$image = new SimpleImage();
					//load the image we are uploading
					$image->load($temp_location);
					//resize the image
					$image->resizeToWidth(450);
					//save the file
					$image->save($uploadfile);
					
					$result = 100;
				}
	
			}else{
				$result =  3; //Error: 4 image limit
			}
		}else{
			$result =  2; //Error: file exceeds size limit
		}
	}else{
		$result =  1; //Error: file type not accepted
	}
	sleep(1);
}


?>
<html>
<head>
<script language="javascript" type="text/javascript">
<!-- Pass the PHP result and file data from the PHP of the iframe to the parent window -->
	window.top.window.stopUpload(<?php echo $result; ?>, '<?php echo $new_unqiue_name; ?>', <?php echo $fileID ?>);
</script>
</head>
<body></body>
</html>