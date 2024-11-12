<?php
require 'QontakAPI.php';

// Your access token for Qontak API
$accessToken = 'your_access_token';

// Initialize QontakAPI instance
$qontak = new QontakAPI($accessToken);

// Example broadcast ID
$broadcastId = '990aeb5f-04d9-4b7e-8ad2-678f29f0d214'; //replace with broadcast id

// Retrieve and display the broadcast log
$response = $qontak->getBroadcastLog($broadcastId);
// echo $response;
?>
