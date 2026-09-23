<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

// Add the "Forwarded" text indicator in the rendering loop
$target1 = <<<EOF
                        \${repliedTopHtml}
                        \${repliedMessageBubbleHtml}
                        
                        <div class="px-3 py-2 shadow-sm relative transition-transform duration-200 touch-pan-y z-10 w-full \${m.is_mine
EOF;

$replacement1 = <<<EOF
                        \${repliedTopHtml}
                        \${repliedMessageBubbleHtml}
                        
                        \${m.is_forwarded ? `<div class="flex items-center gap-1 text-[10px] text-gray-400 mb-0.5 italic \${m.is_mine ? 'mr-2' : 'ml-2'}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg> Forwarded message</div>` : ''}
                        
                        <div class="px-3 py-2 shadow-sm relative transition-transform duration-200 touch-pan-y z-10 w-full \${m.is_mine
EOF;
$content = str_replace($target1, $replacement1, $content);

// Append the Modal correctly before the media lightbox
$target2 = <<<EOF
{{-- ③ Media Preview Lightbox (Outside widget container to cover whole screen) --}}
EOF;

$replacement2 = <<<EOF
<!-- Forward Modal -->
<div id="chatForwardModal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden flex flex-col transform transition-all">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800 text-sm">Forward to...</h3>
            <button onclick="document.getElementById('chatForwardModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 rounded-full p-1 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div id="chatForwardList" class="p-2 overflow-y-auto max-h-[60vh] space-y-1">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<script>
window.chatShowForwardModal = function(msgId) {
    window._forwardMsgId = msgId;
    const list = document.getElementById('chatForwardList');
    
    // Create an array with General GC first
    let usersList = [];
    
    // Prepend General Chat
    usersList.push(`
        <button onclick="chatExecuteForward(0)" class="flex items-center gap-3 px-3 py-2.5 hover:bg-yellow-50 rounded-lg transition-colors text-left w-full border border-transparent hover:border-yellow-100">
            <div class="relative w-8 h-8 rounded-full bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center text-white font-black text-xs flex-shrink-0 shadow-sm border border-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800 truncate">General Staff Chat</p>
                <p class="text-[10px] text-gray-500 truncate">Group Chat</p>
            </div>
        </button>
    `);
    
    // Map existing users
    if (window.chatGlobalUsers) {
        usersList.push(window.chatGlobalUsers.map(u => {
            return `
                <button onclick="chatExecuteForward(\${u.id})" class="flex items-center gap-3 px-3 py-2.5 hover:bg-yellow-50 rounded-lg transition-colors text-left w-full border border-transparent hover:border-yellow-100">
                    <div class="relative w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white font-black text-xs flex-shrink-0 shadow-sm border border-white">
                        \${u.avatar}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">\${u.name}</p>
                        <p class="text-[10px] text-gray-500 truncate">\${u.role || ''}</p>
                    </div>
                </button>
            `;
        }).join(''));
    }
    
    list.innerHTML = usersList.join('');
    document.getElementById('chatForwardModal').classList.remove('hidden');
};

window.chatExecuteForward = async function(toUserId) {
    if (!window._forwardMsgId) return;
    
    const formData = new FormData();
    formData.append('to_user_id', toUserId);
    formData.append('forward_from_id', window._forwardMsgId);

    try {
        await xhrPromise('/chat/send', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        document.getElementById('chatForwardModal').classList.add('hidden');
        // If we are currently chatting with the user we forwarded to, refresh
        if (window.chatActiveUser && window.chatActiveUser.id === toUserId) {
            chatFetchMessages();
        }
    } catch (e) {
        alert('Failed to forward message');
    }
};
</script>

{{-- ③ Media Preview Lightbox (Outside widget container to cover whole screen) --}}
EOF;

$content = str_replace($target2, $replacement2, $content);

file_put_contents($file, $content);
echo "Frontend forward fully patched with GC and Forwarded indicator!";
