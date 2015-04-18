<?php
include 'GCMPushMessage.php';
$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "c9";
/*
$servername = 'https://omega.uta.edu/myadmin';
$username = 'sxa6933';
$password = "B88KMc5T";
$database = "sxa6933";
*/
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['group_id']) && isset($_POST['username']) && isset($_POST['location'])) {
        $location = $_POST['location'];
        //$conn->beginTransaction();
        //$_SESSION['$username'] + $username;
        $username = $_POST['username'];
        $group_id = $_POST['group_id'];
		$stmt = $conn->prepare("select user_id from users where username = '" . $username . "'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
        $stmt = $conn->prepare("select max_ppl from group_details where group_id = '" . $group_id . "'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
            $max_ppl = $row['max_ppl'];
        }
        $stmt = $conn->prepare("select count(group_id) as count_group_id from user_group_details where group_id = '" . $group_id . "'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
            $count_group = $row['count_group_id'];
        }
        if($count_group <= $max_ppl){
            $query = "INSERT INTO user_group_details (user_id, group_id, location) VALUES ('$user_id', '$group_id', '$location')";
            $conn->beginTransaction();
            $result_group = $conn->exec($query);
            echo($result_group);
            $conn->commit();
            if (isset($$result_group)) {
            // successfully inserted into database
        
                $response["success"] = 1;
                $response["message"] = "Your are added to meeting " . $group_id;
 
            // echoing JSON response
                echo json_encode($response);
            } else {
        // failed to insert row
                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred. Meeting was not created.";
 
        // echoing JSON response
                echo json_encode($response);
            }
        }
        
        else{
            $response["success"] = 0;
            $response["message"] = "Max ppl for the meeting has been reached!";
 
        // echoing JSON response
                echo json_encode($response);
        }
        $stmt = $conn->prepare("select count(group_id) as count_group_id from user_group_details where group_id = '" . $group_id . "'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
            $count_group = $row['count_group_id'];
        }
        if($count_group==$max_ppl){
            $lat_values = array();
            $long_values = array();
            $x_values = array();
            $y_values = array();
            $z_values = array();
            $stmt = $conn->prepare("select location from user_group_details where group_id = '" . $group_id . "'");
		    $stmt->execute();
		    
		    while ($row = $stmt->fetch()) {
		        echo($location);
                $location = $row['location'];
                $lat_long = explode(",",$location);
                $lat_rad = $lat_long[0] * pi()/180;
                $long_rad = $lat_long[1] * pi()/180;
                array_push($lat_values,$lat_rad);
                array_push($long_values,$long_rad);
                
            }
            echo($lat_values);
            echo($long_values);
            for($i = 0 ; $i < count($lat_values) ; $i++){
                $x = cos($lat_values[$i]) * cos($long_values[$i]);
                $y = cos($lat_values[$i]) * sin($long_values[$i]);
                $z = sin($lat_values[$i]);
                array_push($x_values,$x);
                array_push($y_values,$y);
                array_push($z_values,$z);
                echo("XYZ = ");
                echo($x . "," . $y . "," . $z );
            }
            $x_avg = array_sum($x_values)/count($x_values);
            $y_avg = array_sum($y_values)/count($y_values);
            $z_avg = array_sum($z_values)/count($z_values);
            $mid_longitude = atan2($y_avg, $x_avg);
            $hyp = sqrt(($x_avg * $x_avg) + ($y_avg * $y_avg));
            $mid_latitude = atan2($z_avg, $hyp);
            echo("Mid longitude = " . $mid_longitude);
            echo("Mid latitude = " . $mid_latitude);
            
            $midpoint = ($mid_latitude*180/pi()) . "," . ($mid_longitude*180/pi());
            echo("Midpoint = " . $midpoint);
            $query = "update group_details set feasible_midpoint = '" . $midpoint . "' where group_id = '" . $group_id . "'";
            $conn->beginTransaction();
            $result_group = $conn->exec($query);
            $conn->commit();
            $apiKey = "AIzaSyCUFbbJQDwyWIb36D-jrYdVmE51-iTh_xw";
            $stmt = $conn->prepare("select gcm_id from users where user_id in (select user_id from user_group_details where group_id='" . $group_id . "')");
		    $stmt->execute();
		    $gcm_id_array = array();
		    while ($row = $stmt->fetch()) {
		        array_push($gcm_id_array,$row['gcm_id']);
		    }
            $devices = $gcm_id_array;
            $message = "All users are registered for the meeting. Click here to select your choice of meeting place.";

            $gcpm = new GCMPushMessage($apiKey);
            $gcpm->setDevices($devices);
            $response = $gcpm->send($message, array('title' => 'WeMeet', 'body' => $message));
            $stmt = $conn->prepare("select feasible_midpoint, max_ppl from group_details where group_id = '" . $group_id . "'" );
		    $stmt->execute();
		    while ($row = $stmt->fetch()) {
		        $feasible_midpoint = $row['feasible_midpoint'];
		        $max_ppl = $row['max_ppl'];
            }
            $radius = 1500;
            while(true){
                $json = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyCrjUsBQoM6zXPdhKzpXANDZ7tisHHHO3o&location=" . $feasible_midpoint . "&radius=" . $radius . "&types=restaurant"); // this will require php.ini to be setup to allow fopen over URLs
                $data = json_decode($json);
                $results = $data->results;
                $i = 1;
                if(count($results) >= 5){
                    foreach($results as $result){
                        $loc_id = $i;
                        if(isset($result->price_level)){
                            $price_level = $result->price_level;
                        }
                        else{
                            $price_level = 0;
                        }
            
                        $name = $result->name;
                        if(isset($result->rating)){
                            $rating = $result->rating;
                        }
                        else{
                            $rating = 0;
                        }
                        $address = $result->vicinity;
                        $latitude = $result->geometry->location->lat;
                        $longitude = $result->geometry->location->lng;
                        if(isset($result->photos[0]->photo_reference)){
                            $image = $result->photos[0]->photo_reference;
                        }
                        else{
                            $image = null;
                        }
                        $query = "INSERT INTO locations (group_id, loc_id, name, price_level, rating, address, latitude, longitude, image) VALUES ('$group_id','$loc_id'," . '"' . $name. '"' . ", '$price_level', '$rating', '$address', '$latitude', '$longitude', '$image')";
                        echo $query;
                        $conn->beginTransaction();
                        $result_group = $conn->exec($query);
                        $conn->commit();
                        $i++;
                        if($i>=6){
                            break;
                        }
                    }
                    break;
                }
                else{
                    $radius = $radius + 500;
                }
            }
        }
    
    }
        
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>

//32.754751, -97.173471
//https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=" . $result->photos[0]->photo_reference . "&key=AIzaSyCrjUsBQoM6zXPdhKzpXANDZ7tisHHHO3o";