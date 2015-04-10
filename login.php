<?php
session_start();
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
		$stmt = $conn->prepare("select * from users where username = '" . $username . "'and password = '" . $password . "'");
		$stmt->execute();
		if(null == $stmt->fetch()){
			$response["success"] = 0;
            $response["message"] = "User is not registered";
 
            // echoing JSON response
            echo json_encode($response);
  		}
		else{
			$response["success"] = 1;
            $response["message"] = "User can login.";
            $_SESSION['username'] = $username;
            // echoing JSON response
            echo json_encode($response);
		}
	}
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}