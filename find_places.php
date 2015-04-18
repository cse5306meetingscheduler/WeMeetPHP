<?php
//https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyCrjUsBQoM6zXPdhKzpXANDZ7tisHHHO3o&location=32.7331436,-97.1102656&radius=2500&types=restaurant
//https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=CnRuAAAAU9j5tfFmNnLu2zWZJ5Anv20uiRSrL8j4fBiQEUh_Aglk-Ay7HVBXtTWYhqApfXQqRsXW-RCVfOIX4KpOGHjkeZmKBjoGIAc-nRvok7jQjCc92LAuETrufSoDez0Txh4l38MJzjaql1Egz_77NDIBnxIQs8gUb68laouYK03nTpBuJBoU_FTFURa4U_mcS8TWIBFXxZ73Yl0&key=AIzaSyCrjUsBQoM6zXPdhKzpXANDZ7tisHHHO3o

session_start();
$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "c9";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['group_id'])){
        $response_array = array();
        $group_id = $_POST['group_id'];
        $stmt = $conn->prepare("select loc_id, name, price_level, rating, address, latitude, longitude, image from locations where group_id = '" . $group_id . "'");
		$stmt->execute();
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
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}