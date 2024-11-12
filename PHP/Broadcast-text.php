<?php
require 'QontakAPI.php';

// Your access token for Qontak API
$accessToken = 'your_access_token';

// Initialize QontakAPI instance
$qontak = new QontakAPI($accessToken);

// Define message details
$toName = "Muhamad Iqbal";
$toNumber = "62XX";  // recipient phone number in international format
$templateId = "X";  // template ID for the message
$channelId = "X";    // channel integration ID
$languageCode = "id";                                   // language code for the template

// Define body parameters for template
$bodyParams = [
    ["key" => "1", "value" => "nama", "value_text" => "Muhamad Iqbal"],
    ["key" => "2", "value" => "capex_year", "value_text" => "2024"],
    ["key" => "3", "value" => "total_budget", "value_text" => "100.000.000"],
    ["key" => "4", "value" => "link", "value_text" => "https://mekari.com"]
];

// Send the custom broadcast message
$response = $qontak->broadcastCustomMessage(
    $toName,
    $toNumber,
    $templateId,
    $channelId,
    $languageCode,
    $bodyParams
);

// Print the response
echo "Broadcast Response: " . $response;
?>
