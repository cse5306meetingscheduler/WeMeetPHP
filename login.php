<?php
	require 'config.php';
    if (isset($_POST['password']) && isset($_POST['username'])){
        $password = md5($_POST['password']);
        $username = $_POST['username'];
        $conn->beginTransaction();
		$stmt = $conn->prepare("select home_location from users where username = ? and password = ?");
		$stmt->execute(array($username,$password));
		while ($row = $stmt->fetch()) {
            $home_location = $row['home_location'];
        }
		if(null == $home_location){
			$response["success"] = 0;
            $response["message"] = "User is not registered";
 
            // echoing JSON response
            echo json_encode($response);
  		}
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
