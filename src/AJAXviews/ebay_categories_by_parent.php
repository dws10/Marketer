<?php
include('../class/Global.php');

$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);

$parentID = $_GET['parentID'];
$parentID = $mysqli->real_escape_string($parentID);

$sql = "SELECT ebay_category_real_id FROM ebay_category_tbl WHERE ebay_category_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $parentID);

if (!$stmt->execute()) {
	//if failure echo error
	echo "Failed to update category parentID: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
}else{
	$stmt->bind_result($ebay_id);
	$stmt->store_result();
	
	if($stmt->num_rows > 0){
	
		while($stmt->fetch()){
			
			$sql = "SELECT ebay_category_id, ebay_category_real_id, ebay_category_name FROM ebay_category_tbl WHERE ebay_category_parent_id = ?";
			
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('i', $ebay_id);
			
			if (!$stmt->execute()) {
				//if failure echo error
				echo "Failed to update category parentID: (" . $mysqli->errno . ") " . $mysqli->error.'<br/><br/>';
			}else{
			
				$i = 0;
				$stmt->bind_result($ebay_category_id, $ebay_category_real_id, $ebay_category_name);
				$resp = '<select class="form-control ebay_category_select" name="ebay_cat">';
				$resp .= " <option value='' disabled selected>Please Select A Sub-Category</option>";
				while($stmt->fetch()){
					if($ebay_category_real_id != $ebay_id){
						$resp .= "<option value='".$ebay_category_id."'>".$ebay_category_name."</option>";
						$i++;
					}
				}
				$resp .= '</select>';
				
				if($i > 0){
					echo $resp;
				}else{
					echo 'null';
				}
			}	
		}
	}
}



?>