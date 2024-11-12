<?php
require 'QontakAPI.php';

// Your access token for Qontak API
$accessToken = 'your_access_token';

// Initialize QontakAPI instance
$qontak = new QontakAPI($accessToken);

// Define webhook URL
$webhookUrl = "https://yourdomain.com/qontak/webhook_handler";

// Register the webhook to receive agent, customer, and status messages
$response = $qontak->registerWebhook(
    $webhookUrl,          // URL for the webhook
    true,                 // receive_message_from_agent
    true,                 // receive_message_from_customer
    true,                 // status_message
    false                 // broadcast_log_status (optional)
);

// Print the response
echo "Webhook Registration Response: " . $response;
?>
