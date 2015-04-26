<?php
require "config.php";
    if (isset($_POST['group_id'])){
        $response_array = array();
        $group_id = $_POST['group_id'];
		$stmt = $conn->prepare("select loc_id, name, price_level, rating, address, latitude, longitude, image from locations where group_id = ?");
		$stmt->execute(array($group_id));
		while ($row = $stmt->fetch()) {
		    $response = array();
		    $response['loc_id'] = $row['loc_id'];
		    $response['name'] = $row['name'];
		    $response['price_level'] = $row['price_level'];
		    $response['rating'] = $row['rating'];
		    $response['address'] = $row['address'];
		    $response['latitude'] = $row['latitude'];
		    $response['longitude'] = $row['longitude'];
		    $response['image'] = $row['image'];
		    array_push($response_array,$response);
        }
        if(isset($response_array)){
            echo json_encode($response_array);
        }
        else{
            $response = array();
            $response['success'] = 0;
            echo json_encode($response);
        }
    }
?>
