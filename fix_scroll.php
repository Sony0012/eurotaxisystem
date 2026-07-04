<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

// 1. Add chatScrollToBottom function and chatHandleScroll inside the script block
$scriptInjection = <<<JAVASCRIPT
    window.chatScrollToBottom = function() {
        const box = document.getElementById("staffChatMessages");
        if (box) box.scrollTop = box.scrollHeight;
    };
    
    window.chatHandleScroll = function() {
        const box = document.getElementById("staffChatMessages");
        const btn = document.getElementById("chatScrollBottomBtn");
        if (!box || !btn) return;
        
        if (box.scrollHeight - box.scrollTop - box.clientHeight > 100) {
            btn.classList.remove("opacity-0", "pointer-events-none", "translate-y-4");
            btn.classList.add("opacity-100", "translate-y-0");
        } else {
            btn.classList.add("opacity-0", "pointer-events-none", "translate-y-4");
            btn.classList.remove("opacity-100", "translate-y-0");
        }
    };
JAVASCRIPT;

$content = preg_replace("/window\.chatFetchMessages = async function/", $scriptInjection . "\n    window.chatFetchMessages = async function", $content);

// 2. Fix the scroll logic inside chatFetchMessages
$targetScroll = "const isAtBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 50;\n            if (isAtBottom || messages.length <= 50) {\n                box.scrollTop = box.scrollHeight;\n            }";
$replacementScroll = <<<JAVASCRIPT
            const isAtBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 50;
            // First load flag
            const isFirstLoad = !box.dataset.loaded;
            if (isFirstLoad) box.dataset.loaded = "true";
            
            if (isAtBottom || isFirstLoad) {
                setTimeout(() => {
                    box.scrollTop = box.scrollHeight;
                }, 100); // Wait for render and animations
                // Wait for images to load
                const imgs = box.getElementsByTagName("img");
                for (let img of imgs) {
                    img.addEventListener("load", () => {
                        const stillAtBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 200;
                        if (stillAtBottom || isFirstLoad) box.scrollTop = box.scrollHeight;
                    });
                }
            }
JAVASCRIPT;

$content = str_replace($targetScroll, $replacementScroll, $content);

// 3. Add the HTML for the scroll down button and attach onscroll to staffChatMessages
$targetContainerPattern = "/<div id=\"staffChatMessages\"[^\>]*><\/div>/s";
$replacementContainer = <<<HTML
<div id="staffChatMessages" onscroll="chatHandleScroll()" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50" style="min-height: 280px;"></div>
    <button id="chatScrollBottomBtn" onclick="chatScrollToBottom()" class="absolute bottom-[80px] right-4 bg-yellow-500 text-white rounded-full p-2 shadow-lg hover:bg-yellow-600 transition-all duration-200 opacity-0 pointer-events-none translate-y-4 z-40">
        <i data-lucide="arrow-down" class="w-5 h-5 pointer-events-none"></i>
    </button>
HTML;

$content = preg_replace($targetContainerPattern, $replacementContainer, $content);

// Also reset dataset.loaded when selecting a user
$targetSelect = "document.getElementById('chatThread').classList.remove('hidden');";
$replacementSelect = "document.getElementById('chatThread').classList.remove('hidden');\n        document.getElementById('staffChatMessages').dataset.loaded = '';";
$content = str_replace($targetSelect, $replacementSelect, $content);

file_put_contents($file, $content);
echo "Scroll logic updated successfully!";
