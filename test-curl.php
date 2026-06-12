<?php

// Display PHP info about cURL
echo "PHP cURL Information:\n";
echo "PHP Version: " . phpversion() . "\n";
echo "cURL Version: " . curl_version()['version'] . "\n";
echo "SSL Version: " . curl_version()['ssl_version'] . "\n";

// Get the current cURL configuration
echo "\nCurrent cURL Configuration:\n";
echo "curl.cainfo: " . ini_get('curl.cainfo') . "\n";
echo "openssl.cafile: " . ini_get('openssl.cafile') . "\n";

// Test a simple cURL request to Cloudinary
echo "\nTesting cURL request to Cloudinary:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/dbyoethmb/ping");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CAINFO, "D:/reviewer/cacert.pem");
$response = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($error) {
    echo "cURL Error: " . $error . "\n";
} else {
    echo "cURL Success! Response: " . $response . "\n";
    echo "HTTP Status Code: " . $info['http_code'] . "\n";
}

// Test with Guzzle
echo "\nTesting Guzzle request to Cloudinary:\n";
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

try {
    $client = new Client([
        'verify' => 'D:/reviewer/cacert.pem',
    ]);
    $response = $client->request('GET', 'https://api.cloudinary.com/v1_1/dbyoethmb/ping');
    echo "Guzzle Success! Status Code: " . $response->getStatusCode() . "\n";
    echo "Response Body: " . $response->getBody() . "\n";
} catch (RequestException $e) {
    echo "Guzzle Error: " . $e->getMessage() . "\n";
}
