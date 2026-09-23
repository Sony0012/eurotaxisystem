<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$scriptStart = strpos($content, "window.chatReactToMessage = async function(emoji) {");
$scriptEnd = strpos($content, "};", $scriptStart) + 2;

$newReactToMessage = <<<JAVASCRIPT
window.chatReactToMessage = async function(emoji) {
    const debug = (msg) => {
        const d = document.createElement("div");
        d.style.cssText = "position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999999;background:#000;color:#fff;padding:15px;border-radius:10px;font-size:16px;text-align:center;";
        d.innerText = msg;
        document.body.appendChild(d);
        setTimeout(() => d.remove(), 3000);
    };
    
    if (!activeReactionMessageId) {
        debug("ERROR: No activeReactionMessageId");
        return;
    }
    const msgId = activeReactionMessageId;
    debug("Reacting: " + emoji + " to ID: " + msgId);
    
    chatHideReactionPicker();
    
    try {
        const payload = JSON.stringify({ reaction: emoji });
        const res = await xhrPromise(`/chat/react/\${msgId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
            },
            body: payload
        });
        debug("Success: " + JSON.stringify(res));
        chatFetchMessages();
    } catch (e) {
        debug("Error: " + e.message);
        console.error("Failed to save reaction:", e);
    }
};
JAVASCRIPT;

$content = substr_replace($content, $newReactToMessage, $scriptStart, $scriptEnd - $scriptStart);

file_put_contents($file, $content);
echo "Injected debugger!";

