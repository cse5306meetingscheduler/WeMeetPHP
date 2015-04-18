<?php
        include_once("testmsg.php");
        define("GOOGLE_API_KEY", "AIzaSyA0-lltBKfC00Q-W0n04Zy46ha6QTDqtAc");
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' => array('APA91bGHVJblq62hLJwoiHdkKBlgkpOqTHfk8U4aVBJwQKd1Sl4wWvNwrG1--o-TA9SpBlwYpacO_C2wvJdN9SOT-G2qVmqg0MI_NtQ1vAkgWPs7KXpS1h_POp5Ncuw7Se_iFqww5KqOMLQoukDJha9VjLgaN0kuLg'),
            'data' => array("Test message"),
        );
 
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        echo $result;
    
?>