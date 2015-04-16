<?php

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
        echo("goup count = " . $count_group);
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
            
        }
    
    }
        
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>

//32.754751, -97.173471