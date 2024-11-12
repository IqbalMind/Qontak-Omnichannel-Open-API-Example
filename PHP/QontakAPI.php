<?php

class QontakAPI
{
    private $accessToken;
    private $baseUrl;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->baseUrl = 'https://service-chat.qontak.com/api/open/v1';
    }

    public function broadcastOTP($toName, $toNumber, $messageTemplateId, $channelIntegrationId, $languageCode, $otpCode) {
        $endpoint = '/broadcasts/whatsapp/direct';
        
        $data = [
            "to_name" => $toName,
            "to_number" => $toNumber,
            "message_template_id" => $messageTemplateId,
            "channel_integration_id" => $channelIntegrationId,
            "language" => [
                "code" => $languageCode
            ],
            "parameters" => [
                "body" => [
                    ["key" => "1", "value" => "otp", "value_text" => $otpCode]
                ],
                "buttons" => [
                    [
                        "index" => "0",
                        "type" => "url",
                        "value" => $otpCode // Sets the OTP code as the button value
                    ]
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    /**
     * Customizable broadcast message for WhatsApp, supporting dynamic body, header, and button parameters.
     *
     * @param string $toName Recipient's name
     * @param string $toNumber Recipient's phone number in international format
     * @param string $templateId Message template ID
     * @param string $channelId Channel integration ID
     * @param string $languageCode Language code (e.g., "en", "id")
     * @param array $parameters Associative array of dynamic message parameters
     * @param string|null $mediaType Optional: IMAGE, VIDEO, DOCUMENT for headers
     * @param string|null $mediaUrl Optional URL for the media attachment
     * @return string JSON response or error message
     */
    public function broadcastCustomMessage($toName, $toNumber, $messageTemplateId, $channelIntegrationId, $languageCode, $parameters = [], $mediaUrl = null, $mediaType = null, $filename = null) {
        $endpoint = '/broadcasts/whatsapp/direct';

        $data = [
            "to_name" => $toName,
            "to_number" => $toNumber,
            "message_template_id" => $messageTemplateId,
            "channel_integration_id" => $channelIntegrationId,
            "language" => [
                "code" => $languageCode
            ],
            "parameters" => [
                "body" => $parameters
            ]
        ];

        // Add media header if media information is provided
        if ($mediaUrl && $mediaType && $filename) {
            $data["parameters"]["header"] = [
                "format" => strtoupper($mediaType),
                "params" => [
                    ["key" => "url", "value" => $mediaUrl],
                    ["key" => "filename", "value" => $filename]
                ]
            ];
        }

        // Debugging: Output the constructed data
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_SLASHES));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            // Debugging the response
            echo 'Response: ' . $result;
        }
        curl_close($ch);

        return $result;
    }

    /**
     * Get the broadcast status and log by ID.
     *
     * @param string $broadcastId The ID of the broadcast
     * @return string JSON response or error message
     */
    // New function to retrieve broadcast log
    public function getBroadcastLog($broadcastId) {
        $url = "https://service-chat.qontak.com/api/open/v1/broadcasts/{$broadcastId}/whatsapp/log";
    
        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo 'CURL Error: ' . curl_error($ch);
        } elseif ($httpCode != 200) {
            echo "HTTP Error Code: $httpCode<br>";
            echo "Response: " . $result;
        } else {
            echo 'Broadcast Log Response: ' . $result;
        }
        
        curl_close($ch);

        return $result;
    }

    /**
     * Register a webhook for message interactions.
     *
     * @param string $url The webhook URL
     * @param bool $agentMessages Whether to receive messages from agents
     * @param bool $customerMessages Whether to receive messages from customers
     * @param bool $statusMessages Whether to receive status messages
     * @param bool $broadcastLog Whether to receive broadcast log status
     * @return string JSON response or error message
     */
    public function registerWebhook($url, $agentMessages = true, $customerMessages = true, $statusMessages = true, $broadcastLog = false)
    {
        $endpoint = '/v1/message_interactions';
        $data = [
            'receive_message_from_agent' => $agentMessages,
            'receive_message_from_customer' => $customerMessages,
            'status_message' => $statusMessages,
            'broadcast_log_status' => $broadcastLog,
            'url' => $url
        ];

        return $this->sendRequest('PUT', $endpoint, $data);
    }

    /**
     * Upload a file to Qontak's server and retrieve the URL.
     *
     * @param string $filePath Path to the file to be uploaded
     * @return string JSON response with file URL or error message
     */
    public function uploadFile($filePath)
    {
        $endpoint = '/file_uploader';
        
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $this->baseUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->accessToken}"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'file' => new CURLFile($filePath)
            ]
        ];

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = 'cURL error: ' . curl_error($curl);
            curl_close($curl);
            return $error;
        }

        curl_close($curl);
        return $response;
    }

    /**
     * Send an HTTP request to the Qontak API.
     *
     * @param string $method HTTP method (e.g., GET, POST, PUT)
     * @param string $endpoint API endpoint
     * @param array|null $data Optional data for POST/PUT requests
     * @return string JSON response or error message
     */
    private function sendRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/json"
            ]
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = 'cURL error: ' . curl_error($curl);
            curl_close($curl);
            return $error;
        }

        curl_close($curl);
        return $response;
    }
}
?>
