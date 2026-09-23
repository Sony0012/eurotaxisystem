<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$scriptStart = strpos($content, "window.chatReactToMessage = async function(emoji) {");
$scriptEnd = strpos($content, "};", $scriptStart) + 2;

$cleanReactToMessage = <<<JAVASCRIPT
window.chatReactToMessage = async function(emoji) {
    if (!activeReactionMessageId) return;
    const msgId = activeReactionMessageId;
    chatHideReactionPicker();
    
    try {
        const payload = JSON.stringify({ reaction: emoji });
        await window.xhrPromise(`/chat/react/\${msgId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
            },
            body: payload
        });
        window.chatFetchMessages();
    } catch (e) {
        console.error("Failed to save reaction:", e);
    }
};
JAVASCRIPT;

$content = substr_replace($content, $cleanReactToMessage, $scriptStart, $scriptEnd - $scriptStart);

file_put_contents($file, $content);
echo "Removed debugger!";

