<?php
$apiKey = 'AQ.Ab8RN6JpYtcmu5HZHfZAsgTnri5wSl6bZ5oGCiZlMLQ7hzTsTg';
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models';
$ch = curl_init($endpoint . '?key=' . $apiKey);
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false]);
$res = curl_exec($ch);
curl_close($ch);
$data = json_decode($res, true);
$models = [];
foreach($data['models'] as $m) {
    if (strpos($m['name'], 'flash') !== false) {
        $models[] = $m['name'];
    }
}
print_r($models);
