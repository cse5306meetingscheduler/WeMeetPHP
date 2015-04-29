<?php
/*
	* This page is used to view meetings of a particular user
*/
require "config.php";
	//get the username from the client
    if (isset($_POST['username'])){
        $username = $_POST['username'];
        //get the userid from the database for the provided user
        $stmt = $conn->prepare("select user_id from users where username = ?");
		$stmt->execute(array($username));
		while ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
        //get the group details of the groups which userid is part of
		$stmt = $conn->prepare("select group_id, meeting_time, meeting_date, final_dest, host_id, feasible_midpoint, max_ppl from group_details where group_id in (select group_id from user_group_details where user_id =?) order by group_id desc" );
		$stmt->execute(array($user_id));
		$response_array = array();
		while ($row = $stmt->fetch()) {
		    $response = array();
            $response['meeting_time'] = $row['meeting_time'];
            $response['group_id'] = $row['group_id'];
            $response['meeting_date'] = $row['meeting_date'];
            $response['final_dest'] = $row['final_dest'];
            if($row['host_id'] == $user_id){
                $response['host'] = 1;
            }
            else{
                $response['host']=0;
            }
            $response['feasible_midpoint'] = $row['feasible_midpoint'];
            $response['max_ppl'] = $row['max_ppl'];
            array_push($response_array,$response);
        }
        //send the meetings list to the client
		if(isset($response_array)){
			echo json_encode($response_array);
  		}
		else{
		    $result['success'] = 0;
		    echo json_encode($result);
		}
	}
?>
