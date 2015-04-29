<?php
/*
	* This page is used to join a participant to a particular group
*/
include 'GCMPushMessage.php';
require "config.php";
	//get groupid, username and location from the client
    if (isset($_POST['group_id']) && isset($_POST['username']) && isset($_POST['location'])) {
        $location = $_POST['location'];
        $username = $_POST['username'];
        $group_id = $_POST['group_id'];
        //retrieve userid for the provided username
		$stmt = $conn->prepare("select user_id from users where username = ?");
		$stmt->execute(array($username));
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
        //get the maximum people participating in the group
		$stmt = $conn->prepare("select max_ppl from group_details where group_id = ?");
		$stmt->execute(array($group_id));
		while ($row = $stmt->fetch()) {
            $max_ppl = $row['max_ppl'];
        }
        //check the number of participants already regstered for the particular group
		$stmt = $conn->prepare("select count(group_id) as count_group_id from user_group_details where group_id = ?");
		$stmt->execute(array($group_id));
		while ($row = $stmt->fetch()) {
            $count_group = $row['count_group_id'];
        }
        //if the count of already registered participants is less than the maximum people allowed in the group, store the information
        if($count_group <= $max_ppl){
            $query = "INSERT INTO user_group_details (user_id, group_id, location) VALUES (:user_id, :group_id, :location)";
            $stmt = $conn->prepare($query);
            $result_group = $stmt->execute(array(':user_id'=>$user_id,':group_id'=>$group_id,':location'=>$location));
            if (isset($result_group)) {
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
        //otherwise send a meeting filled message
        else{
            $response["success"] = 0;
            $response["message"] = "Max ppl for the meeting has been reached!";
 
        // echoing JSON response
                echo json_encode($response);
        }
		$stmt = $conn->prepare("select count(group_id) as count_group_id from user_group_details where group_id = ?");
		$stmt->execute(array($group_id));
		while ($row = $stmt->fetch()) {
            $count_group = $row['count_group_id'];
        }
        //when all the participants have joined the meeting
        if($count_group==$max_ppl){
            $lat_values = array();
            $long_values = array();
            $x_values = array();
            $y_values = array();
            $z_values = array();
            //location of all users for the group is retrieved
		    $stmt = $conn->prepare("select location from user_group_details where group_id = ?");
		    $stmt->execute(array($group_id));
		    //geographical midpoint between all locations is calculated
		    while ($row = $stmt->fetch()) {
		        $location = $row['location'];
                $lat_long = explode(",",$location);
                //lat long is converted from degrees to radians
                $lat_rad = $lat_long[0] * pi()/180;
                $long_rad = $lat_long[1] * pi()/180;
                array_push($lat_values,$lat_rad);
                array_push($long_values,$long_rad);
                
            }
            //cartesian product is found
            for($i = 0 ; $i < count($lat_values) ; $i++){
                $x = cos($lat_values[$i]) * cos($long_values[$i]);
                $y = cos($lat_values[$i]) * sin($long_values[$i]);
                $z = sin($lat_values[$i]);
                array_push($x_values,$x);
                array_push($y_values,$y);
                array_push($z_values,$z);
                
            }
            //average of all x, y , z values are found
            $x_avg = array_sum($x_values)/count($x_values);
            $y_avg = array_sum($y_values)/count($y_values);
            $z_avg = array_sum($z_values)/count($z_values);
            //latitude and longitude of midpoint is calculated 
            $mid_longitude = atan2($y_avg, $x_avg);
            $hyp = sqrt(($x_avg * $x_avg) + ($y_avg * $y_avg));
            $mid_latitude = atan2($z_avg, $hyp);
            //the midpoint lat-long is converted from radians back to degrees
            $midpoint = ($mid_latitude*180/pi()) . "," . ($mid_longitude*180/pi());
            //the midpoint is stored in the database
            $query = "update group_details set feasible_midpoint = :midpoint where group_id = :group_id";
            $stmt = $conn->prepare($query);
            $result_group = $stmt->execute(array(':midpoint'=>$midpoint,':group_id'=>$group_id));
            $apiKey = "AIzaSyCUFbbJQDwyWIb36D-jrYdVmE51-iTh_xw";
		    $stmt = $conn->prepare("select gcm_id from users where user_id in (select user_id from user_group_details where group_id=?)");
		    $stmt->execute(array($group_id));
		    $gcm_id_array = array();
		    while ($row = $stmt->fetch()) {
		        array_push($gcm_id_array,$row['gcm_id']);
		    }
            $devices = $gcm_id_array;
            $message = "All users are registered for the meeting. Click here to select your choice of meeting place.";
			//all users are notified of the midpoint selection process completion
            $gcpm = new GCMPushMessage($apiKey);
            $gcpm->setDevices($devices);
            $response = $gcpm->send($message, array('title' => 'WeMeet', 'body' => $message, 'type' => '1'));
		    $stmt = $conn->prepare("select feasible_midpoint, max_ppl, categories from group_details where group_id = ?" );
		    $stmt->execute(array($group_id));
		    while ($row = $stmt->fetch()) {
		        $feasible_midpoint = $row['feasible_midpoint'];
		        $max_ppl = $row['max_ppl'];
		        $categories = $row['categories'];
            }
            $radius = 1500;
            //places around the midpoint are obtained using google places api
            //initially a radius of 1500 meters is kept
            //if the number of places returned is less than 5, the radius is increased by 500 meters
            //the obtained results will be stored into the database
            while(true){
                $json = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyCrjUsBQoM6zXPdhKzpXANDZ7tisHHHO3o&location=" . $feasible_midpoint . "&radius=" . $radius . "&types=" . $categories); // this will require php.ini to be setup to allow fopen over URLs
                $data = json_decode($json);
                $results = $data->results;
                $i = 1;
                if(count($results) >= 5 || $radius >= 50000){
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
                        $json_address = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude);
                        $data_address = json_decode($json_address);
                        $address = $data_address->results[0]->formatted_address;
                        $query = "INSERT INTO locations (group_id, loc_id, name, price_level, rating, address, latitude, longitude, image) VALUES (:group_id,:loc_id,:name,:price_level,:rating,:address,:latitude,:longitude,:image)";
                        $stmt = $conn->prepare($query);
                        $result_group = $stmt->execute(array(':group_id'=>$group_id,':loc_id'=>$loc_id,':name'=>$name,':price_level'=>$price_level,':rating'=>$rating,':address'=>$address,':latitude'=>$latitude,':longitude'=>$longitude,':image'=>$image));
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
        
?>


