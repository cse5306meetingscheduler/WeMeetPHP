<?php
$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "c9";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['password']) && isset($_POST['username'])){
        $password = md5($_POST['password']);
        $username = $_POST['username'];
        $conn->beginTransaction();
		$stmt = $conn->prepare("select home_location from users where username = '" . $username . "'and password = '" . $password . "'");
		$stmt->execute();
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
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}