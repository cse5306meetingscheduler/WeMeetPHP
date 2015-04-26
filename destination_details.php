<?php
require "config.php";
    if (isset($_POST['group_id']) && isset($_POST['username'])){
        $username = $_POST['username'];
        $group_id = $_POST['group_id'];
        $loc_id = $_POST['loc_id'];
		$stmt = $conn->prepare("select user_id from users where username = ?");
		$stmt->execute(array($username));
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
		$stmt = $conn->prepare("select final_dest from group_details where group_id = ?");
		$stmt->execute(array($group_id));
		while ($row = $stmt->fetch()) {
            $loc_id = $row['final_dest'];
        }
		$stmt = $conn->prepare("select loc_id, name, price_level, rating, address, latitude, longitude, image from locations where group_id = ? and loc_id=?");
		$stmt->execute(array($group_id,$loc_id));
		$response = array();
		while ($row = $stmt->fetch()) {
		    
		    $response['loc_id'] = $row['loc_id'];
		    $response['name'] = $row['name'];
		    $response['price_level'] = $row['price_level'];
		    $response['rating'] = $row['rating'];
		    $response['address'] = $row['address'];
		    $response['latitude'] = $row['latitude'];
		    $response['longitude'] = $row['longitude'];
		    $response['image'] = $row['image'];
		    
        }
        if(isset($response)){
            echo json_encode($response);
        }
        else{
            $response = array();
            $response['success'] = 0;
            echo json_encode($response);
        }
    }
?>
