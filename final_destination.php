<?php
/*
	* This page is used for calculating the final destination details
*/
include 'GCMPushMessage.php';
require "config.php";
	//groupid, selected location and the username is retrieved from the client
    if (isset($_POST['group_id']) && isset($_POST['loc_id']) && isset($_POST['username'])){
        $username = $_POST['username'];
        $group_id = $_POST['group_id'];
        $loc_id = $_POST['loc_id'];
        //userid of the username is retrieved
		$stmt = $conn->prepare("select user_id from users where username = ?");
		$stmt->execute(array($username));
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
		$stmt = $conn->prepare("select max_ppl, host_id from group_details where group_id = ?");
		$stmt->execute(array($group_id));
		while ($row = $stmt->fetch()) {
            $max_ppl = $row['max_ppl'];
            $host_id = $row['host_id'];
        }
        //update the users choice into the database
        $query = "update user_group_details set selected_loc = :loc_id where user_id = :user_id and group_id = :group_id ";
        $stmt = $conn->prepare($query);
        $result_group = $stmt->execute(array(':loc_id'=>$loc_id,':user_id'=>$user_id,':group_id'=>$group_id));
		$stmt = $conn->prepare("select user_id,selected_loc from user_group_details where group_id = ?");
		$stmt->execute(array($group_id));
		$locations = array();
		while ($row = $stmt->fetch()) {
            $selected_loc = $row['selected_loc'];
            $user_id = $row['user_id'];
            if($selected_loc != ''){
                $locations[$user_id] = $selected_loc;
            }
        }
        //when all users have selected their choice of location
        //a simple majority voting is done and also more preference is given to the host of the meeting 
        if($max_ppl == count($locations)){
            $selected_location = array(1=>0,2=>0,3=>0,4=>0,5=>0);
            
            foreach($locations as $user=>$location){
                if($location != ''){
                    if($host_id == $user){
                        $selected_location[$location] += 2;
                    }
                    else{
                        $selected_location[$location] += 1;
                    }
                }
            }
            $max = 0;
            foreach( $selected_location as $key => $value )
            {
                if($max < $value){
                    $max_key = $key;
                    $max = $value;
                }
        
            }
        //the computed final destination is updated in the databse
        $query = "update group_details set final_dest = :max_key where group_id = :group_id";
        $stmt = $conn->prepare($query);
        $result_group = $stmt->execute(array(':max_key'=>$max_key,':group_id'=>$group_id));
        if(isset($result_group)){
            $response = array();
            $response['success'] = 1;
            $response['message'] = "All users have selected their preferences.You will be notified shortly";
            
            echo json_encode($response);
            $apiKey = "AIzaSyCUFbbJQDwyWIb36D-jrYdVmE51-iTh_xw";
            //also all users are notified of the change
		    $stmt = $conn->prepare("select gcm_id from users where user_id in (select user_id from user_group_details where group_id=?)");
		    $stmt->execute(array($group_id));
		    $gcm_id_array = array();
		    while ($row = $stmt->fetch()) {
		        array_push($gcm_id_array,$row['gcm_id']);
		    }
            $devices = $gcm_id_array;
            $message = "All users have selected their choice of restaurant for meeting id " . $group_id;

            $gcpm = new GCMPushMessage($apiKey);
            $gcpm->setDevices($devices);
            $response = $gcpm->send($message, array('title' => 'WeMeet', 'body' => $message, 'group_id' => $group_id, 'type' => '2'));
        }
        else{
            $response = array();
            $response['success'] = 0;
            $response['message'] = "Choice of restaurant was not updated.";
            echo json_encode($response);
        }
        }
        else{
            $response = array();
            $response['success'] = 1;
            $response['message'] = "All users have not selected their preferences yet.";
            echo json_encode($response);
        }
    }
?>
