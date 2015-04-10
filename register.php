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
    if (isset($_POST['password']) && isset($_POST['username']) && isset($_POST['gcm_id']) && isset($_POST['home_location']) && isset($_POST['phone_number']) && isset($_POST['email_id'])) {
 
        $password = md5($_POST['password']);
        $username = $_POST['username'];
        $gcm_id = $_POST['gcm_id'];
        $home_location = ($_POST['home_location']);
        $phone_number = $_POST['phone_number'];
        $email_id = $_POST['email_id'];
        $conn->beginTransaction();

        $query = "INSERT INTO users (username, gcm_id, home_location, phone_number, email_id, password) VALUES ('$username', '$gcm_id', '$home_location', '$phone_number', '$email_id', '$password')";
    
        $result = $conn->exec($query);
        $conn->commit();
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
        
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>