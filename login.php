<?php
	/*
	* This page is used for the user login
	*/
	require 'config.php';
	//get password and username from the client
    if (isset($_POST['password']) && isset($_POST['username'])){
        $password = md5($_POST['password']);
        $username = $_POST['username'];
        $conn->beginTransaction();
        //check if the username and password combination is registered in the database
		$stmt = $conn->prepare("select home_location from users where username = ? and password = ?");
		$stmt->execute(array($username,$password));
		while ($row = $stmt->fetch()) {
            $home_location = $row['home_location'];
        }
        //if not registered, send an error message
		if(null == $home_location){
			$response["success"] = 0;
            $response["message"] = "User is not registered";
 
            // echoing JSON response
            echo json_encode($response);
  		}
  		//if registered send the users home location to the client
		else{
		    $row = $stmt->fetch();
			$response["success"] = 1;
            $response["message"] = "User can login.";
            $response['user_location'] = $home_location;
            $_SESSION['username'] = $username;
            // echoing JSON response
            echo json_encode($response);
		}
	}
?>
