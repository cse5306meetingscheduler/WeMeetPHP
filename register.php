<?php
/*
	* This page is used for the user registration
*/
require "config.php";
	//get user details from the client
    if (isset($_POST['password']) && isset($_POST['username']) && isset($_POST['gcm_id']) && isset($_POST['home_location']) && isset($_POST['phone_number']) && isset($_POST['email_id'])) {
 
        $password = md5($_POST['password']);
        $username = $_POST['username'];
        $gcm_id = $_POST['gcm_id'];
        $home_location = ($_POST['home_location']);
        $phone_number = $_POST['phone_number'];
        $email_id = $_POST['email_id'];
        $conn->beginTransaction();
		
		//store the retrieved user information into the database
    	$query = "INSERT INTO users (username, gcm_id, home_location, phone_number, email_id, password) VALUES (:username,:gcm_id,:home_location,:phone_number,:email_id,:password)";
    	$stmt = $conn->prepare($query);
        $result = $stmt->execute(array(':username'=>$username,':gcm_id'=>$gcm_id,':home_location'=>$home_location,':phone_number'=>$phone_number,':email_id'=>$email_id,'password'=>$password));
        if ($result) {
            // successfully inserted into database
        
            $response["success"] = 1;
            $response["message"] = "User successfully regstered.";
 
            // echoing JSON response
            echo json_encode($response);
        } else {
        // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Oops! An error occurred.";
 
        // echoing JSON response
            echo json_encode($response);
        }
    
    }
        
?>
