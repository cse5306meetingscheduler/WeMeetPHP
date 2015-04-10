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
    echo "In start";
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['max_ppl']) && isset($_POST['username']) && isset($_POST['meeting_time']) && isset($_POST['meeting_date']) && isset($_POST['location'])) {
        echo "in create meeting";
        $max_ppl = $_POST['max_ppl'];
        $meeting_time = $_POST['meeting_time'];
        $meeting_date = $_POST['meeting_date'];
        $location = $_POST['location'];
        //$conn->beginTransaction();
		$stmt = $conn->prepare("select user_id from users where username = '" . $username . "'");
		$stmt->execute();
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
        //$conn->close();
		//$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		//echo $result;
        //foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $key=>$value) { 
        //    $user_id = $value;
        //}
        echo "User id = " . $user_id;
        $query = "INSERT INTO group_details (host_id, max_ppl, meeting_date, meeting_time) VALUES ('$user_id', '$max_ppl', '$meeting_date', '$meeting_time')";
        $conn->beginTransaction();
        $result_group = $conn->exec($query);
        $conn->commit();
        //$conn->close();
        echo "Insterted into group_details";
        //$conn->beginTransaction();
		$stmt = $conn->prepare("select group_id from group_details where host_id = '" . $user_id . "' and max_ppl = '" . $max_ppl . "' and meeting_date = '" . $meeting_date . "' and meeting_time = '" . $meeting_time . "'");
		$stmt->execute();
		
		while ($row = $stmt->fetch()) {
            $group_id = $row['group_id'];
        }
        //$conn->close();
        echo "Group id = " . $group_id;
        $query = "INSERT INTO user_group_details (user_id, group_id, location) VALUES ('$user_id', '$group_id', '$location')";
        $conn->beginTransaction();
        $result_user_group = $conn->exec($query);
        $conn->commit();
        //$conn->close();
        if (isset($user_id) and isset($group_id)) {
            // successfully inserted into database
        
            $response["success"] = 1;
            $response["message"] = "Your meeting group ID is " . $group_id;
 
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
        
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>