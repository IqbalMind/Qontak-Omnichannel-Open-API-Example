<?php
require 'QontakAPI.php';

// Your access token for Qontak API
$accessToken = 'your_access_token';

// Initialize QontakAPI instance
$qontak = new QontakAPI($accessToken);

// Define message details
$toName = "Muhamad Iqbal";
$toNumber = "62XXXX";  // recipient phone number in international format
$templateId = "X";  // template ID for the message
$channelId = "X";    // channel integration ID
$languageCode = "id";                                   // language code for the template

// Define body parameters for template
$bodyParams = [
    ["key" => "1", "value" => "nama", "value_text" => "Muhamad Iqbal"],
    ["key" => "2", "value" => "promo", "value_text" => "potongan Rp. 20.000"],
    ["key" => "3", "value" => "berlaku", "value_text" => "31 Agustus 2024"]
];

// Define media details if needed
$mediaUrl = "https://qblstore.com/upload/QBLStore-Blog-1.png"; // URL of the media (image in this case)
$mediaType = "IMAGE";                                          // type of media ("image", "document", etc.)
$filename = "promo_august.png";                                // filename for the media

// Send the custom broadcast message
$response = $qontak->broadcastCustomMessage(
    $toName,
    $toNumber,
    $templateId,
    $channelId,
    $languageCode,
    $bodyParams,
    $mediaUrl,
    $mediaType,
    $filename
);

// Output the response
// echo $response;
// Beautify the JSON response
$formatted_json = json_encode(json_decode($response), JSON_PRETTY_PRINT);

echo "<pre>$formatted_json</pre>";
?>
