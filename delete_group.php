<?php
require "config.php";
    if (isset($_POST['group_id'])){
        $group_id = $_POST['group_id'];
        $query_locations = "delete from locations where group_id = :group_id";
        $query_user_group_details = "delete from user_group_details where group_id = :group_id";
        $query_group_details = "delete from group_details where group_id = :group_id";
        $stmt_locations = $conn->prepare($query_locations);
        $stmt_locations->bindParam(':group_id', $group_id, PDO::PARAM_INT); 
        $result_locations = $stmt_locations->execute();
        $stmt_user_group_details = $conn->prepare($query_user_group_details);
        $stmt_user_group_details->bindParam(':group_id', $group_id, PDO::PARAM_INT); 
        $result_user_group_details = $stmt_user_group_details->execute();
        $stmt_group_details = $conn->prepare($query_group_details);
        $stmt_group_details->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $result_group_details = $stmt_group_details->execute();
        if((isset($result_user_group_details) && isset($result_group_details)) || isset($result_locations)){
            $response = array();
            $response['success'] = 1;
            $response['message'] = "Meeting deleted successfully!";
            
            echo json_encode($response);
        }
        else{
            $response = array();
            $response['success'] = 0;
            $response['message'] = "Meeting could not be deleted!!";
            
            echo json_encode($response);
        }
    }
        
    
?>
