<?php
$apiKey = 'AQ.Ab8RN6JpYtcmu5HZHfZAsgTnri5wSl6bZ5oGCiZlMLQ7hzTsTg';
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models';

$ch = curl_init($endpoint . '?key=' . $apiKey);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP CODE: $httpCode\n";
echo "RESPONSE: $res\n";
