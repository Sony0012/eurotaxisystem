<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

// Expose xhrPromise
$content = str_replace("function xhrPromise(url, options = {}) {", "window.xhrPromise = function xhrPromise(url, options = {}) {", $content);

// Expose chatFetchMessages
$content = str_replace("async function chatFetchMessages() {", "window.chatFetchMessages = async function chatFetchMessages() {", $content);

file_put_contents($file, $content);
echo "Fixed scopes!";

