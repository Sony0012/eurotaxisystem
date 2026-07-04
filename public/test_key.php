<?php
$apiKey = 'AQ.Ab8RN6JpYtcmu5HZHfZAsgTnri5wSl6bZ5oGCiZlMLQ7hzTsTg';
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
$payload = json_encode([
    'contents' => [['parts' => [['text' => 'Hello, this is a test. Reply with OK.']]]],
]);

$ch = curl_init($endpoint . '?key=' . $apiKey);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP CODE: $httpCode\n";
echo "ERROR: $error\n";
echo "RESPONSE: $res\n";
