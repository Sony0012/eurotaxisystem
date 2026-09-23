
(function () {
    let chatOpen       = false;
    let chatActiveUser = null;
    let chatPollTimer  = null;
    let chatSending    = false;
    let chatSelectedFile = null;
    let chatReplyToData  = null;

    // â”€â”€â”€ Toggle Open / Close â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    window.chatToggleDrawer = function () {
        chatOpen = !chatOpen;
        const drawer = document.getElementById('chatDrawer');
        const icon   = document.getElementById('chatBtnIcon');

        if (chatOpen) {
            // Smooth bouncy open
            drawer.classList.remove('opacity-0', 'pointer-events-none', 'scale-95', 'translate-y-4');
            drawer.classList.add('opacity-100', 'pointer-events-auto', 'scale-100', 'translate-y-0');
            if (icon) {
                icon.setAttribute('data-lucide', 'chevron-down');
                if (window.lucide) window.lucide.createIcons();
            }
            chatLoadUsers();
            chatStartPolling();
        } else {
            // Smooth close
            drawer.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100', 'translate-y-0');
            drawer.classList.add('opacity-0', 'pointer-events-none', 'scale-95', 'translate-y-4');
            if (icon) {
                icon.setAttribute('data-lucide', 'message-circle');
                if (window.lucide) window.lucide.createIcons();
            }
            chatStopPolling();
        }
    };

    // â”€â”€â”€ XHR Helper for Android WebView Compatibility â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    window.xhrPromise = function xhrPromise(url, options = {}) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open(options.method || 'GET', url);
            xhr.withCredentials = true;
            if (options.headers) {
                for (const [k, v] of Object.entries(options.headers)) {
                    xhr.setRequestHeader(k, v);
                }
            }
            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try { resolve(JSON.parse(xhr.responseText)); } 
                    catch(e) { resolve(xhr.responseText); }
                } else {
                    reject(new Error('HTTP ' + xhr.status + ': ' + xhr.responseText));
                }
            };
            xhr.onerror = () => reject(new Error('Network Error'));
            xhr.send(options.body || null);
        });
    }

    // â”€â”€â”€ Load Staff Users â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    async function chatLoadUsers() {
        const list    = document.getElementById('chatUserList');
        const loading = document.getElementById('chatUserListLoading');
        if (loading) loading.classList.remove('hidden');

        try {
            const users = await xhrPromise('/chat/staff-users?_t=' + Date.now(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            window.chatGlobalUsers = users;
            if (loading) loading.classList.add('hidden');

            if (!users.length) {
                list.innerHTML = '<div class="px-4 py-8 text-center text-gray-400 text-sm">No other staff online.</div>';
                return;
            }

            list.innerHTML = users.map(u => {
                const onlineDot = u.is_online 
                    ? '<span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm"></span>'
                    : '';
                return `
                <button onclick="chatOpenThread(${u.id}, '${u.name.replace(/'/g, "\\'")}', ${u.is_online}, '${u.last_active || ''}')"
                        class="flex items-center gap-3 px-4 py-3.5 hover:bg-yellow-50/50 transition-colors text-left border-b border-gray-100 w-full last:border-b-0">
                    <div class="relative w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white font-black text-sm flex-shrink-0 shadow-sm border border-white">
                        ${u.avatar}
                        ${onlineDot}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-gray-900 truncate">${u.name}</p>
                            ${u.last_time ? `<p class="text-[9px] text-gray-400 shrink-0 ml-2">${u.last_time}</p>` : ''}
                        </div>
                        <p class="text-[11px] ${u.unread > 0 ? 'text-gray-900 font-bold' : 'text-gray-500'} truncate mt-0.5">${u.last_msg || u.role}</p>
                    </div>
                    ${u.unread > 0
                        ? `<span class="bg-red-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">${u.unread}</span>`
                        : ''}
                </button>
            `}).join('');

            const totalUnread = users.reduce((s, u) => s + u.unread, 0);
            const badge = document.getElementById('chatUnreadBadge');
            if (badge) {
                badge.textContent = totalUnread;
                totalUnread > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
            }
            if (!window.originalDocTitle) {
                window.originalDocTitle = document.title.replace(/^\(\d+\)\s*/, '');
            }
            if (totalUnread > 0) {
                document.title = '(' + totalUnread + ') ' + window.originalDocTitle;
            } else {
                document.title = window.originalDocTitle;
            }
            if (window.lucide) window.lucide.createIcons();
        } catch (e) {
            list.innerHTML = `<span class="text-red-400 text-xs px-4">Error: ${e.message}</span>`;
        }
    }

    // â”€â”€â”€ Open conversation thread â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    window.chatOpenThread = function (userId, userName, isOnline, lastActive) {
        chatActiveUser = { id: userId, name: userName };
        document.getElementById('chatUserList').classList.add('hidden');
        const thread = document.getElementById('chatThread');
        thread.classList.remove('hidden');
        thread.classList.add('flex');
        
        let subText = isOnline ? 'Active now' : (lastActive ? 'Active ' + lastActive : 'Offline');
        let iconHtml = isOnline 
            ? '<div class="w-2 h-2 rounded-full bg-green-400 mr-1 animate-pulse"></div>' 
            : '<i data-lucide="grip-horizontal" class="w-3.5 h-3.5 text-yellow-100 opacity-75 pointer-events-none"></i>';

        document.getElementById('chatHeaderTitle').innerHTML =
            `${iconHtml} <span class="pointer-events-none">${userName}</span>`;
        document.getElementById('chatHeaderSub').innerHTML = subText;
        document.getElementById('chatHeaderSub').textContent = subText;
        document.getElementById('chatBackBtn').classList.remove('hidden');
        
        if (window.lucide) window.lucide.createIcons();
        chatFetchMessages();
    };

    // â”€â”€â”€ Back to list view â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    window.chatBackToList = function () {
        chatActiveUser = null;
        chatClearAttachment();
        document.getElementById('chatThread').classList.add('hidden');
        document.getElementById('chatThread').classList.remove('flex');
        document.getElementById('chatUserList').classList.remove('hidden');
        document.getElementById('chatHeaderTitle').innerHTML =
            '<i data-lucide="grip-horizontal" class="w-3.5 h-3.5 text-yellow-100 opacity-75 pointer-events-none"></i> Staff Chat';
        document.getElementById('chatHeaderSub').textContent = 'Hold header to drag â€¢ Internal';
        document.getElementById('chatBackBtn').classList.add('hidden');
        if (window.lucide) window.lucide.createIcons();
        chatLoadUsers();
    };

    // â”€â”€â”€ Fetch Thread Messages â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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
    window.chatFetchMessages = async function chatFetchMessages() {
        if (!chatActiveUser) return;
        try {
            const messages = await xhrPromise(`/chat/messages/${chatActiveUser.id}?_t=` + Date.now(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const box = document.getElementById('staffChatMessages');
            let lastIsMine = null;

            if (messages.length === 0) {
                box.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 mt-20 opacity-80">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <p class="text-sm font-medium text-gray-600">No messages yet</p>
                        <p class="text-xs mt-1 text-center max-w-[200px] text-gray-400">Send a message to start the conversation.</p>
                    </div>
                `;
            } else {
                box.innerHTML = messages.map((m, index, arr) => {
                const isFirstInCluster = index === 0 || arr[index - 1].sender !== m.sender;
                const isLastInCluster = index === arr.length - 1 || arr[index + 1].sender !== m.sender;
                
                let marginClass = 'mb-1';
                if (isLastInCluster) marginClass = 'mb-4';

                let attachmentHtml = '';
                if (m.attachment_path) {
                    if (m.attachment_type === 'image') {
                        attachmentHtml = `<img src="${m.attachment_path}" onclick="chatPreviewMedia('image', '${m.attachment_path}')" class="max-w-[200px] max-h-[200px] rounded-lg mt-1 object-cover hover:opacity-90 transition-opacity cursor-pointer border border-black/10 shadow-sm">`;
                    } else if (m.attachment_type === 'video') {
                        attachmentHtml = `
                            <div class="relative mt-1 cursor-pointer group" onclick="chatPreviewMedia('video', '${m.attachment_path}')">
                                <video src="${m.attachment_path}#t=0.1" class="max-w-[200px] max-h-[200px] rounded-lg object-cover border border-black/10 shadow-sm"></video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition-colors rounded-lg">
                                    <div class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4 text-gray-900 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        attachmentHtml = `
                            <a href="${m.attachment_path}" target="_blank" class="flex items-center gap-2 bg-black/10 hover:bg-black/20 transition-colors p-2 rounded-lg mt-1 no-underline text-current">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-xs truncate max-w-[150px] font-medium">${escapeHtml(m.attachment_name)}</span>
                            </a>
                        `;
                    }
                }

                let statusHtml = '';
                if (m.is_mine) {
                    // Show read receipt icon
                    if (m.read) {
                        statusHtml = `<span class="ml-1 text-[9px] text-yellow-300 flex items-center"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Seen</span>`;
                    } else {
                        statusHtml = `<span class="ml-1 text-[9px] text-yellow-100 flex items-center opacity-70"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sent</span>`;
                    }
                }

                // Spacing logic for consecutive messages
                const isConsecutive = lastIsMine === m.is_mine;
                lastIsMine = m.is_mine;
                const marginClass = isConsecutive ? 'mt-1' : 'mt-4';

                // Render Reactions
                let reactionsHtml = '';
                if (m.reactions && Object.keys(m.reactions).length > 0) {
                    const uniqueEmojis = [...new Set(Object.values(m.reactions))];
                let tooltipParts = [];
                for (const [uId, emj] of Object.entries(m.reactions)) {
                    if (String(uId) === String(chatActiveUser.id)) {
                        tooltipParts.push(chatActiveUser.name + " " + emj);
                    } else {
                        tooltipParts.push("You " + emj);
                    }
                }
                const tooltipText = tooltipParts.join("\n");
                    const totalReactions = Object.keys(m.reactions).length;
                    reactionsHtml = `
                        <div class="absolute -bottom-2 ${m.is_mine ? '-left-2' : '-right-2'} bg-white text-gray-800 rounded-full px-1 py-0.5 text-[11px] leading-none flex items-center justify-center shadow-md cursor-pointer z-20 border border-gray-100" title="${tooltipText}" onclick="chatShowReactionPicker(event, ${m.id})">
                            ${uniqueEmojis.join('')} ${totalReactions > 1 ? `<span class="text-gray-500 font-bold ml-0.5">${totalReactions}</span>` : ''}
                        </div>
                    `;
                }

                let repliedTopHtml = '';
                let repliedMessageBubbleHtml = '';
                if (m.reply_data) {
                    const rType = m.reply_data.attachment_type;
                    const rIcon = rType === 'image' ? 'ðŸ–¼ï¸ ' : (rType === 'video' ? 'ðŸŽ¥ ' : (rType ? 'ðŸ“Ž ' : ''));
                    const rText = m.reply_data.message ? escapeHtml(m.reply_data.message) : (rType ? 'Attachment' : '');
                    const senderName = m.reply_data.sender === m.sender ? 'yourself' : escapeHtml(m.reply_data.sender);
                    
                    repliedTopHtml = `
                        <div class="flex items-center gap-1 text-[10px] text-gray-500 mb-0.5 ${m.is_mine ? 'mr-2' : 'ml-2'}">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <span>You replied to ${senderName}</span>
                        </div>
                    `;
                    
                    repliedMessageBubbleHtml = `
                        <div class="bg-black/10 text-gray-600 text-[11px] px-3 py-2 rounded-t-2xl opacity-80 truncate w-full" style="margin-bottom: -12px; padding-bottom: 16px;">
                            ${rIcon}${rText}
                        </div>
                    `;
                }

                const swipeDataStr = escapeHtml(JSON.stringify({ id: m.id, name: m.sender, text: m.message || (m.attachment_type || 'Attachment') }));
                
                let actionMenuHtml = `
                    <div class="flex opacity-60 md:opacity-0 group-hover:opacity-100 transition-opacity items-center gap-1 ${m.is_mine ? 'mr-2' : 'ml-2'} shrink-0 z-10 self-center">
                        <button onclick="chatShowReactionPicker(event, ${m.id})" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full bg-black/5 transition-colors" title="React">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                        <button data-id="${m.id}" data-name="${escapeHtml(m.sender)}" data-text="${escapeHtml(m.message || m.attachment_type || 'Attachment')}" onclick="chatTriggerReplyFromAction(this)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full bg-black/5 transition-colors" title="Reply">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                        </button>
                        <button data-id="${m.id}" onclick="chatShowForwardModal(this.dataset.id)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full bg-black/5 transition-colors" title="Forward">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg>
                        </button>
                    </div>
                `;

                return `
                <div class="flex ${m.is_mine ? 'justify-end' : 'justify-start'} ${marginClass} group pl-1 pr-1 pb-1">
                    ${m.is_mine ? actionMenuHtml : ''}
                    
                    ${(!m.is_mine && chatActiveUser.id === 0) ? `
                    <div class="w-7 h-7 shrink-0 flex items-center justify-center mr-2 self-end mb-4">
                        ${isLastInCluster ? `
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white font-bold text-[10px] shadow-sm border border-white">
                            ${m.sender_avatar || 'U'}
                        </div>
                        ` : ''}
                    </div>
                    ` : ''}
                    
                    <div class="flex flex-col ${m.is_mine ? 'items-end' : 'items-start'} max-w-[75%] relative">
                        ${(!m.is_mine && chatActiveUser.id === 0 && isFirstInCluster) ? `<span class="text-[10px] text-gray-500 font-bold ml-1 mb-0.5">${escapeHtml(m.sender)}</span>` : ''}
                        ${repliedTopHtml}
                        ${repliedMessageBubbleHtml}
                        
                        ${m.is_forwarded ? `<div class="flex items-center gap-1 text-[10px] text-gray-400 mb-0.5 italic ${m.is_mine ? 'mr-2' : 'ml-2'}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg> Forwarded message</div>` : ''}
                        
                        <div class="px-3 py-2 shadow-sm relative transition-transform duration-200 touch-pan-y z-10 w-full ${m.is_mine
                            ? 'bg-gradient-to-br from-yellow-500 to-amber-500 text-white rounded-2xl rounded-tr-sm'
                            : 'bg-white text-gray-800 rounded-2xl rounded-tl-sm border border-gray-100'}"
                            oncontextmenu="chatShowReactionPicker(event, ${m.id})"
                            ontouchstart="chatSwipeStart(event, this, '${swipeDataStr}')"
                            ontouchmove="chatSwipeMove(event)"
                            ontouchend="chatSwipeEnd(event)"
                            onmousedown="chatSwipeStart(event, this, '${swipeDataStr}')">
                            
                            ${m.message ? `<p class="text-[13px] leading-relaxed break-words">${escapeHtml(m.message)}</p>` : ''}
                            ${attachmentHtml}
                            
                            ${reactionsHtml}
                        </div>
                        <div class="flex items-center ${m.is_mine ? 'justify-end' : 'justify-start'} gap-1 mt-1 px-1 w-full">
                            ${isLastInCluster ? `<span class="text-[9px] text-gray-400">${m.time}</span>` : ''}
                            ${isLastInCluster ? statusHtml : ''}
                        </div>
                    </div>
                    
                    ${!m.is_mine ? actionMenuHtml : ''}
                </div>
            `}).join('');
            }
            
            // Only scroll to bottom if user is already at the bottom or it's first load
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
        } catch (e) {}
    }

    // â”€â”€â”€ File Attachment Logic â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    window.chatHandleFileSelect = function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (file.size > 10 * 1024 * 1024) {
            alert('File too large. Maximum size is 10MB.');
            event.target.value = '';
            return;
        }

        chatSelectedFile = file;
        document.getElementById('chatAttachmentName').textContent = file.name;
        document.getElementById('chatAttachmentPreview').classList.remove('hidden');
        if (window.lucide) window.lucide.createIcons();
    };

    window.chatClearAttachment = function() {
        chatSelectedFile = null;
        document.getElementById('chatAttachmentInput').value = '';
        document.getElementById('chatAttachmentPreview').classList.add('hidden');
    };

    window.chatSetReply = function(id, name, text) {
        chatReplyToData = { id, name, text };
        document.getElementById('chatReplyName').textContent = name;
        document.getElementById('chatReplyText').textContent = text;
        document.getElementById('chatReplyPreview').classList.remove('hidden');
    };

    window.chatClearReply = function() {
        chatReplyToData = null;
        document.getElementById('chatReplyPreview').classList.add('hidden');
        document.getElementById('chatReplyName').textContent = '';
        document.getElementById('chatReplyText').textContent = '';
    };

    // â”€â”€â”€ Drag and Drop Logic â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const dropOverlay = document.getElementById('chatDropOverlay');
    let dragCounter = 0;

    window.addEventListener('dragenter', (e) => {
        e.preventDefault();
        if (chatOpen) { // Only show overlay if chat is actually open
            dragCounter++;
            if (dragCounter === 1) {
                dropOverlay.classList.remove('hidden');
            }
        }
    });

    window.addEventListener('dragleave', (e) => {
        e.preventDefault();
        if (chatOpen) {
            dragCounter--;
            if (dragCounter <= 0) {
                dragCounter = 0;
                dropOverlay.classList.add('hidden');
            }
        }
    });

    window.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    window.addEventListener('drop', (e) => {
        e.preventDefault();
        dragCounter = 0;
        dropOverlay.classList.add('hidden');
        
        if (chatOpen && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('chatAttachmentInput').files = dataTransfer.files;
            chatHandleFileSelect({ target: document.getElementById('chatAttachmentInput') });
        }
    });

    // â”€â”€â”€ Send Message â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    window.chatSendMessage = async function () {
        if (!chatActiveUser || chatSending) return;
        
        const input = document.getElementById('staffChatMessageInput');
        const msg   = input.value.trim();
        
        if (!msg && !chatSelectedFile) return;
        
        chatSending = true;
        
        // Optimistic UI clear
        input.value = '';
        const prevFile = chatSelectedFile;
        const prevReply = chatReplyToData;
        chatClearAttachment();
        chatClearReply();
        
        try {
            const formData = new FormData();
            formData.append('to_user_id', chatActiveUser.id);
            if (msg) formData.append('message', msg);
            if (prevFile) formData.append('attachment', prevFile);
            if (prevReply) formData.append('reply_to_id', prevReply.id);

            await new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/chat/send');
                xhr.withCredentials = true;
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) resolve();
                    else reject(new Error('Failed'));
                };
                xhr.onerror = () => reject();
                xhr.send(formData);
            });
            
            chatFetchMessages();
        } catch (e) {
            // Revert on failure
            input.value = msg;
            if (prevFile) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(prevFile);
                document.getElementById('chatAttachmentInput').files = dataTransfer.files;
                chatHandleFileSelect({ target: document.getElementById('chatAttachmentInput') });
            }
            if (prevReply) {
                chatSetReply(prevReply.id, prevReply.name, prevReply.text);
            }
            alert('Failed to send message.');
        } finally { 
            chatSending = false; 
        }
    };

    // â”€â”€â”€ Polling â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function chatStartPolling() {
        chatStopPolling();
        chatPollTimer = setInterval(() => {
            if (chatActiveUser) chatFetchMessages();
            else if (chatOpen)  chatLoadUsers();
        }, 3000);
        if (!window._chatVisAdded) {
            window._chatVisAdded = true;
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) chatStopPolling();
                else if (chatOpen)   chatStartPolling();
            });
        }
    }

    function chatStopPolling() {
        if (chatPollTimer) { clearInterval(chatPollTimer); chatPollTimer = null; }
    }

    function escapeHtml(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    // â”€â”€â”€ Global unread badge periodic update â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function pollUnread() {
        xhrPromise('/chat/unread?_t=' + Date.now(), { 
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        })
            .then(d => {
                const badge = document.getElementById('chatUnreadBadge');
                if (!badge) return;
                badge.textContent = d.count;
                d.count > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
            })
            .catch(() => {});
    }
    setInterval(pollUnread, 10000);

    // â”€â”€â”€ Drag & Click Event System â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function initWidgetDrag() {
        const container = document.getElementById('chatWidgetContainer');
        const header    = document.getElementById('chatDragHandle');
        const button    = document.getElementById('chatOpenBtn');
        if (!container || !header || !button) return;

        let startX = 0, startY = 0;
        let initialLeft = 0, initialTop = 0;
        let hasDragged = false;
        let activeDrag = false;

        function getCoords(e) {
            if (e.touches && e.touches.length > 0) {
                return { x: e.touches[0].clientX, y: e.touches[0].clientY };
            }
            return { x: e.clientX, y: e.clientY };
        }

        function toLeftTop() {
            if (container.style.right !== 'auto') {
                const rect = container.getBoundingClientRect();
                container.style.left = rect.left + 'px';
                container.style.top = rect.top + 'px';
                container.style.right = 'auto';
                container.style.bottom = 'auto';
            }
        }

        function dragStart(e) {
            if (e.target.closest('button') && e.target.closest('button') !== button) {
                return;
            }

            activeDrag = true;
            hasDragged = false;

            const coords = getCoords(e);
            startX = coords.x;
            startY = coords.y;

            toLeftTop();
            initialLeft = parseFloat(container.style.left) || 0;
            initialTop  = parseFloat(container.style.top)  || 0;

            if (e.type === 'touchstart') {
                document.addEventListener('touchmove', dragMove, { passive: false });
                document.addEventListener('touchend',  dragEnd);
            } else {
                document.addEventListener('mousemove', dragMove);
                document.addEventListener('mouseup',   dragEnd);
            }
        }

        function dragMove(e) {
            if (!activeDrag) return;

            const coords = getCoords(e);
            const dx = coords.x - startX;
            const dy = coords.y - startY;

            if (!hasDragged && Math.hypot(dx, dy) > 6) {
                hasDragged = true;
                header.style.cursor = 'grabbing';
                button.style.cursor = 'grabbing';
            }

            if (hasDragged) {
                e.preventDefault();
                let nl = initialLeft + dx;
                let nt = initialTop  + dy;

                nl = Math.max(0, Math.min(nl, window.innerWidth  - container.offsetWidth));
                nt = Math.max(0, Math.min(nt, window.innerHeight - container.offsetHeight));

                container.style.left = nl + 'px';
                container.style.top  = nt + 'px';
            }
        }

        function dragEnd(e) {
            activeDrag = false;
            header.style.cursor = 'grab';
            button.style.cursor = 'grab';

            if (e.type === 'touchend') {
                document.removeEventListener('touchmove', dragMove);
                document.removeEventListener('touchend',  dragEnd);
            } else {
                document.removeEventListener('mousemove', dragMove);
                document.removeEventListener('mouseup',   dragEnd);
            }
        }

        header.addEventListener('mousedown', dragStart);
        button.addEventListener('mousedown', dragStart);
        header.addEventListener('touchstart', dragStart, { passive: true });
        button.addEventListener('touchstart', dragStart, { passive: true });

        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (hasDragged) return;
            chatToggleDrawer();
        });

        window.addEventListener('resize', function () {
            if (container.style.left && container.style.left !== 'auto') {
                let nl = parseFloat(container.style.left) || 0;
                let nt = parseFloat(container.style.top)  || 0;
                nl = Math.max(0, Math.min(nl, window.innerWidth  - container.offsetWidth));
                nt = Math.max(0, Math.min(nt, window.innerHeight - container.offsetHeight));
                container.style.left = nl + 'px';
                container.style.top  = nt + 'px';
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWidgetDrag);
    } else {
        initWidgetDrag();
    }
})();

// â”€â”€â”€ Global Media Preview Lightbox â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
window.chatPreviewMedia = function(type, url) {
    const lightbox = document.getElementById('chatMediaLightbox');
    const container = document.getElementById('chatMediaContainer');
    
    if (type === 'image') {
        container.innerHTML = `<img src="${url}" class="w-full h-full object-contain rounded-xl shadow-2xl" onclick="event.stopPropagation()">`;
    } else if (type === 'video') {
        container.innerHTML = `<video src="${url}" controls autoplay class="w-full h-full object-contain rounded-xl shadow-2xl" onclick="event.stopPropagation()"></video>`;
    }
    
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    // small delay for transition
    setTimeout(() => {
        lightbox.style.opacity = '1';
    }, 10);
};

window.chatCloseLightbox = function() {
    const lightbox = document.getElementById('chatMediaLightbox');
    lightbox.style.opacity = '0';
    setTimeout(() => {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.getElementById('chatMediaContainer').innerHTML = ''; // Clear video to stop playing
    }, 200); // matches transition duration
};

// â”€â”€â”€ Emote / Reaction Logic â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
let activeReactionMessageId = null;

window.chatShowReactionPicker = function(event, messageId) {
    if (event) {
        event.preventDefault(); // prevent native right-click menu
        event.stopPropagation(); // prevent document click from hiding it immediately
    }
    
    activeReactionMessageId = messageId;
    const picker = document.getElementById('chatReactionPicker');
    
    // Attempt to get robust coordinates
    let x = window.innerWidth / 2;
    let y = window.innerHeight / 2;
    
    if (event) {
        if (event.clientX !== undefined && event.clientX !== 0) {
            x = event.clientX;
            y = event.clientY;
        } else if (event.touches && event.touches.length > 0) {
            x = event.touches[0].clientX;
            y = event.touches[0].clientY;
        } else if (event.currentTarget) {
            const rect = event.currentTarget.getBoundingClientRect();
            x = rect.left + rect.width / 2;
            y = rect.top;
        }
    }
    
    const drawer = document.getElementById('chatDrawer');
    if (drawer) {
        const rect = drawer.getBoundingClientRect();
        // Adjust x to stay within drawer
        if (x < rect.left + 10) x = rect.left + 10;
        if (x > rect.right - 310) x = rect.right - 310; // picker is roughly 280px wide
        
        // Adjust y to stay within drawer
        let topPos = y - 50;
        if (topPos < rect.top + 10) topPos = y + 20; // if it goes above drawer, put it below the click
        if (topPos > rect.bottom - 60) topPos = rect.bottom - 60;
        
        picker.style.left = x + 'px';
        picker.style.top = topPos + 'px';
    } else {
        // Fallback to viewport bounds
        if (x > window.innerWidth - 200) x = window.innerWidth - 200;
        if (x < 10) x = 10;
        let topPos = y - 50;
        if (topPos < 10) topPos = 10;
        picker.style.left = x + 'px';
        picker.style.top = topPos + 'px';
    }
    
    picker.classList.remove('hidden');
    picker.classList.add('flex');
};

window.chatHideReactionPicker = function() {
    const picker = document.getElementById('chatReactionPicker');
    picker.classList.add('hidden');
    picker.classList.remove('flex');
    activeReactionMessageId = null;
};

window.chatReactToMessage = async function(emoji) {
    if (!activeReactionMessageId) return;
    const msgId = activeReactionMessageId;
    chatHideReactionPicker();
    
    try {
        const payload = JSON.stringify({ reaction: emoji });
        await window.xhrPromise(`/chat/react/${msgId}`, {
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

// â”€â”€â”€ Swipe to Reply Logic â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
let swipeState = {
    active: false,
    startX: 0,
    currentX: 0,
    element: null,
    dataStr: null,
    longPressTimer: null,
    id: null
};


window.chatTriggerReplyFromAction = function(btn) {
    chatSetReply(btn.dataset.id, btn.dataset.name, btn.dataset.text);
    document.getElementById('staffChatMessageInput').focus();
};

window.chatSwipeStart = function(event, el, dataStr) {
    // Only accept left click or touch
    if (event.type === 'mousedown' && event.button !== 0) return;
    
    // Ignore swipe if we are clicking a button inside (e.g., image preview, reaction picker)
    if (event.target.tagName === 'BUTTON' || event.target.tagName === 'IMG' || event.target.closest('button')) return;

    swipeState.active = true;
    swipeState.startX = event.type === 'touchstart' ? event.touches[0].clientX : event.clientX;
    swipeState.currentX = swipeState.startX;
    swipeState.element = el;
    swipeState.dataStr = dataStr;
    
    try {
        swipeState.id = JSON.parse(dataStr.replace(/&quot;/g, '"')).id;
    } catch(e) {}
    
    el.style.transition = 'none';

    // Start long-press timer for both mobile (touchstart) and desktop (mousedown)
    if (event.type === 'touchstart' || event.type === 'mousedown') {
        swipeState.longPressTimer = setTimeout(() => {
            if (swipeState.active && Math.abs(swipeState.currentX - swipeState.startX) < 10) {
                // Trigger context menu manually
                chatShowReactionPicker(event, swipeState.id);
                if (navigator.vibrate) navigator.vibrate(50);
                swipeState.active = false; // cancel swipe
            }
        }, 500); // 500ms long press
    }

    if (event.type === 'mousedown') {
        document.addEventListener('mousemove', chatSwipeMove);
        document.addEventListener('mouseup', chatSwipeEnd);
    }
};

window.chatSwipeMove = function(event) {
    if (!swipeState.active || !swipeState.element) return;
    
    swipeState.currentX = event.type === 'touchmove' ? event.touches[0].clientX : event.clientX;
    let diff = swipeState.currentX - swipeState.startX;
    
    // If finger moves more than 10px, cancel long press
    if (Math.abs(diff) > 10 && swipeState.longPressTimer) {
        clearTimeout(swipeState.longPressTimer);
        swipeState.longPressTimer = null;
    }
    
    // Determine if user swiped left or right.
    if (Math.abs(diff) > 60) {
        diff = diff > 0 ? 60 : -60;
    }
    
    swipeState.element.style.transform = `translateX(${diff}px)`;
};

window.chatSwipeEnd = function(event) {
    if (swipeState.longPressTimer) {
        clearTimeout(swipeState.longPressTimer);
        swipeState.longPressTimer = null;
    }
    
    if (!swipeState.active) return;
    
    let diff = swipeState.currentX - swipeState.startX;
    
    // If swiped more than 40px left or right, trigger reply
    if (Math.abs(diff) > 40 && swipeState.dataStr) {
        try {
            const data = JSON.parse(swipeState.dataStr.replace(/&quot;/g, '"'));
            chatSetReply(data.id, data.name, data.text);
            document.getElementById('staffChatMessageInput').focus();
            
            // Provide haptic feedback if available
            if (navigator.vibrate) navigator.vibrate(50);
        } catch (e) {
            console.error('Failed to parse swipe data', e);
        }
    }
    
    // Reset element position
    if (swipeState.element) {
        swipeState.element.style.transition = 'transform 0.2s ease-out';
        swipeState.element.style.transform = 'translateX(0)';
    }
    
    swipeState.active = false;
    swipeState.element = null;
    swipeState.dataStr = null;
    
    if (event && event.type === 'mouseup') {
        document.removeEventListener('mousemove', chatSwipeMove);
        document.removeEventListener('mouseup', chatSwipeEnd);
    }
};

// Hide reaction picker if clicked outside
document.addEventListener('click', function(e) {
    const picker = document.getElementById('chatReactionPicker');
    if (!picker.classList.contains('hidden') && !e.target.closest('#chatReactionPicker')) {
        chatHideReactionPicker();
    }
});

