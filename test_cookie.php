<?php
$json = '{"36":1720000000000,"37":1720000000000}';
$encoded = rawurlencode($json); // This mimics JS encodeURIComponent

// In PHP, $_COOKIE is automatically urldecode'd by the server.
// So $_COOKIE['read_notifs'] receives the raw JSON string:
$_COOKIE['read_notifs'] = urldecode($encoded);

$rawCookie = $_COOKIE['read_notifs'];
$decodedVal = stripslashes($rawCookie);
$readData = json_decode($decodedVal, true);
if (!$readData) {
    $readData = json_decode($rawCookie, true);
}

var_dump($readData);

$readNotifIds = [];
if (is_array($readData) && array_is_list($readData)) {
    $readNotifIds = array_map('strval', $readData);
} elseif (is_array($readData)) {
    $nowMs = time() * 1000;
    foreach ($readData as $id => $timestamp) {
        if ($nowMs - $timestamp < 2592000000) {
            $readNotifIds[] = (string)$id;
        }
    }
}

var_dump($readNotifIds);
