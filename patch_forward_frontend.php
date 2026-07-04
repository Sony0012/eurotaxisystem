<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

// 1. Add global users variable
$target1 = <<<EOF
            const users = await xhrPromise('/chat/staff-users?_t=' + Date.now(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
EOF;

$replacement1 = <<<EOF
            const users = await xhrPromise('/chat/staff-users?_t=' + Date.now(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            window.chatGlobalUsers = users;
EOF;
$content = str_replace($target1, $replacement1, $content);

// 2. Add Forward button to actionMenuHtml
$target2 = <<<EOF
                        <button data-id="\${m.id}" data-name="\${escapeHtml(m.sender)}" data-text="\${escapeHtml(m.message || m.attachment_type || 'Attachment')}" onclick="chatTriggerReplyFromAction(this)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full bg-black/5 transition-colors" title="Reply">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                        </button>
                    </div>
EOF;

$replacement2 = <<<EOF
                        <button data-id="\${m.id}" data-name="\${escapeHtml(m.sender)}" data-text="\${escapeHtml(m.message || m.attachment_type || 'Attachment')}" onclick="chatTriggerReplyFromAction(this)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full bg-black/5 transition-colors" title="Reply">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                        </button>
                        <button data-id="\${m.id}" onclick="chatShowForwardModal(this.dataset.id)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full bg-black/5 transition-colors" title="Forward">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg>
                        </button>
                    </div>
EOF;
$content = str_replace($target2, $replacement2, $content);

// 3. Add Modal and JS
$target3 = <<<EOF
<style>
/* ─── Global Chat Styles ─── */
EOF;

$replacement3 = <<<EOF
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
    list.innerHTML = (window.chatGlobalUsers || []).map(u => {
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
    }).join('');
    
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
        } else {
            // Toast notification can go here, but a quiet close is fine
        }
    } catch (e) {
        alert('Failed to forward message');
    }
};
</script>

<style>
/* ─── Global Chat Styles ─── */
EOF;
$content = str_replace($target3, $replacement3, $content);

file_put_contents($file, $content);
echo "Frontend forward patched successfully!";
