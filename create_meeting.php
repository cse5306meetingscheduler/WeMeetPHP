<?php
	require "config.php";
    if (isset($_POST['max_ppl']) && isset($_POST['username']) && isset($_POST['meeting_time']) && isset($_POST['meeting_date']) && isset($_POST['location']) && isset($_POST['categories'])) {
        $max_ppl = $_POST['max_ppl'];
        $meeting_time = $_POST['meeting_time'];
        $meeting_date = $_POST['meeting_date'];
        $location = $_POST['location'];
        $categories = $_POST['categories'];
        $username = $_POST['username'];
        
		$stmt = $conn->prepare("select user_id from users where username = ?");
		$stmt->execute(array($username));
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
        $query = "INSERT INTO group_details (host_id, max_ppl, meeting_date, meeting_time, categories) VALUES (:user_id,:max_ppl,:meeting_date,:meeting_time,:categories)";
        $stmt = $conn->prepare($query);
        
        $result_group = $stmt->execute(array(':user_id'=>$user_id,':max_ppl'=>$max_ppl,':meeting_date'=>$meeting_date,':meeting_time'=>$meeting_time,':categories'=>$categories));
        $stmt = $conn->prepare("select group_id from group_details where host_id = ? and max_ppl = ? and meeting_date = ? and meeting_time = ?");
		$stmt->execute(array($user_id,$max_ppl,$meeting_date,$meeting_time));
		
		while ($row = $stmt->fetch()) {
            $group_id = $row['group_id'];
        }
        $query = "INSERT INTO user_group_details (user_id, group_id, location) VALUES (:user_id,:group_id,:location)";
        $stmt = $conn->prepare($query);
        $result_user_group = $stmt->execute(array(':user_id'=>$user_id,':group_id'=>$group_id,':location'=>$location));
        if (isset($result_user_group)) {
            // successfully inserted into database
        
            $response["success"] = 1;
            $response["message"] = "Your meeting group ID is " . $group_id;
 
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Oops! An error occurred. Meeting was not created.";
 
            echo json_encode($response);
        }
    
    }
        

?>
