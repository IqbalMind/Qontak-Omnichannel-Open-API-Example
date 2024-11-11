require 'QontakAPI.php';

$accessToken = 'your_access_token';
$qontak = new QontakAPI($accessToken);

// Set up OTP details
$toName = "Muhamad Iqbal";
$toNumber = "62XXXX"; // Recipient's phone number
$messageTemplateId = "X"; // Replace with OTP template ID
$channelIntegrationId = "X"; // Replace with your channel integration ID
$languageCode = "id"; // Language code
$otpCode = "3145"; // The OTP code

// Send OTP with button displaying the OTP code
$response = $qontak->broadcastOTP($toName, $toNumber, $messageTemplateId, $channelIntegrationId, $languageCode, $otpCode);

// Output the response
echo $response;
