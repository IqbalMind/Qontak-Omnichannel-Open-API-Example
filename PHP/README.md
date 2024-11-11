# QontakAPI PHP Class

A PHP client for integrating with Qontak's API for WhatsApp message broadcasting, file uploads, webhook registration, and broadcast status tracking.

## Features
- Broadcast customizable WhatsApp messages with dynamic templates
- Track broadcast status and logs
- Register a webhook for message interactions
- Upload files for use in broadcast messages

## Installation
Clone this repository to your local machine:
```bash
git clone https://github.com/yourusername/QontakAPI.git
```

## Usage
Example usage of the QontakAPI class:
```php
require 'QontakAPI.php';

$accessToken = 'your_access_token';
$qontak = new QontakAPI($accessToken);

// Example: Send a custom broadcast message
$response = $qontak->broadcastCustomMessage('Muhamad Iqbal', 
    '62XXXX', 
    'X', 
    'X', 
    'id', 
    [
        'body' => [
            ['value' => 'name', 'value_text' => 'Iqbal'],
            ['value' => 'promo', 'value_text' => 'potongan Rp. 20.000']
        ],
        'buttons' => [
            ['index' => '0', 'type' => 'url', 'value' => 'about-us']
        ]
    ],
    'IMAGE', 
    'https://qblstore.com/upload/QBLStore-Blog-1.png'
);
echo $response;
```
