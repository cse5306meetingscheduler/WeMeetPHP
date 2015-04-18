<?php
echo phpinfo();
include_once('GCMPushMessage.php');

$apiKey = "AIzaSyA0-lltBKfC00Q-W0n04Zy46ha6QTDqtAc";
$devices = array('APA91bGHVJblq62hLJwoiHdkKBlgkpOqTHfk8U4aVBJwQKd1Sl4wWvNwrG1--o-TA9SpBlwYpacO_C2wvJdN9SOT-G2qVmqg0MI_NtQ1vAkgWPs7KXpS1h_POp5Ncuw7Se_iFqww5KqOMLQoukDJha9VjLgaN0kuLg');
$message = "The message to send";

$gcpm = new GCMPushMessage($apiKey);
$gcpm->setDevices($devices);
$response = $gcpm->send($message, array('title' => 'Test title'));


?>
