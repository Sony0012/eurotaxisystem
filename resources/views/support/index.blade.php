@extends('layouts.app')

@section('title', 'Driver Support Chat')
@section('page-heading', 'Driver Support Chat')

@section('content')
<div class="flex h-[calc(100vh-120px)] bg-white rounded-3xl shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
    <!-- Left Sidebar: Driver List -->
    <div class="w-80 border-r border-gray-100 flex flex-col bg-gray-50/30">
        <div class="p-6 border-b border-gray-100 bg-white">
            <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i data-lucide="message-circle" class="w-6 h-6 text-yellow-600"></i>
                Messages
            </h3>
            <div class="mt-4 relative">
                <!-- Fake inputs to cheat Chrome autofill -->
                <input type="text" style="display:none" aria-hidden="true" tabindex="-1">
                <input type="password" style="display:none" aria-hidden="true" tabindex="-1">
                
                <input type="search" id="driver_search_query" name="driver_search_query" autocomplete="off" placeholder="Search drivers..." class="w-full pl-10 pr-4 py-2 bg-gray-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar">
            @forelse($drivers as $driver)
                @php
                    $driverIsOnline = $driver->is_online || ($driver->last_seen_at && \Carbon\Carbon::parse($driver->last_seen_at)->diffInMinutes(now()) <= 5);
                @endphp
                <a href="{{ route('support.index', ['driver_id' => $driver->id]) }}" 
                   class="flex items-center gap-3 p-3 rounded-2xl transition-all duration-200 {{ isset($selectedDriver) && $selectedDriver->id == $driver->id ? 'bg-yellow-600 text-white shadow-lg shadow-yellow-200 translate-x-1' : 'hover:bg-white hover:shadow-md text-gray-700' }}">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gray-200 flex items-center justify-center font-bold text-lg border-2 border-white shadow-sm overflow-hidden">
                            @if($driver->profile_image)
                                @php
                                    $imagePath = str_replace('resources/', '', $driver->profile_image);
                                    $isIcon = str_starts_with($imagePath, 'image/') || str_contains($imagePath, 'resources/assets/');
                                @endphp
                                @if($isIcon)
                                    <img src="{{ asset($imagePath) }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/' . $driver->profile_image) }}" class="w-full h-full object-cover">
                                @endif
                            @else
                                {{ strtoupper(substr($driver->full_name ?? 'D', 0, 1)) }}
                            @endif
                        </div>
                        <span class="driver-online-badge absolute bottom-0 right-0 w-3 h-3 {{ $driverIsOnline ? 'bg-emerald-500' : 'bg-gray-300' }} border-2 border-white rounded-full" data-driver-id="{{ $driver->id }}"></span>
                        @if($driver->unread_count > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-black flex items-center justify-center rounded-full border-2 border-white">
                                {{ $driver->unread_count }}
                            </span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h4 class="text-sm font-bold truncate">{{ $driver->full_name }}</h4>
                            @if($driver->latest_message_time)
                                <span class="text-[10px] {{ isset($selectedDriver) && $selectedDriver->id == $driver->id ? 'text-yellow-100' : 'text-gray-400' }}">
                                    {{ \Carbon\Carbon::parse($driver->latest_message_time)->format('h:i A') }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs truncate {{ isset($selectedDriver) && $selectedDriver->id == $driver->id ? 'text-yellow-50/80' : 'text-gray-500' }}">
                            {{ $driver->latest_message ?? 'No messages yet' }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-400">No drivers found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right: Chat Window -->
    <div class="flex-1 flex flex-col bg-white">
        @if($selectedDriver)
            @php
                $isDriverOnline = isset($isDriverOnline) ? $isDriverOnline : false;
            @endphp
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white/80 backdrop-blur-md z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 font-bold border border-yellow-200">
                         {{ strtoupper(substr($selectedDriver->full_name ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">{{ $selectedDriver->full_name }}</h3>
                        <p id="chatHeaderStatus" class="text-[10px] {{ $isDriverOnline ? 'text-emerald-500' : 'text-gray-400' }} font-bold uppercase tracking-widest flex items-center gap-1.5">
                            <span id="chatHeaderDot" class="w-1.5 h-1.5 {{ $isDriverOnline ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }} rounded-full"></span>
                            <span id="chatHeaderStatusText">{{ $isDriverOnline ? 'Online (Driver App)' : ($driverLastSeen ? 'Active ' . $driverLastSeen : 'Offline') }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <!-- Icons removed per user request -->
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/50 custom-scrollbar">
                @forelse($chatMessages as $msg)
                    <div class="flex {{ $msg->sender_type == 'admin' ? 'justify-end' : 'justify-start' }}" data-msg-id="{{ $msg->id }}">
                        <div class="max-w-[70%] group">
                            <div class="flex items-center gap-2 mb-1 {{ $msg->sender_type == 'admin' ? 'flex-row-reverse' : '' }}">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                                    {{ $msg->created_at->diffForHumans() }}
                                </span>
                                @if($msg->sender_type == 'admin')
                                <div class="relative inline-block dropdown-container">
                                    <button type="button" onclick="toggleDropdown(this)" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100" title="More options">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </button>
                                    <div class="dropdown-menu absolute right-0 top-full mt-1 hidden bg-white rounded-xl shadow-lg border border-gray-100 py-1 min-w-[170px] z-50">
                                        <button type="button" onclick="openUnsendModal({{ $msg->id }}, this)" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="px-4 py-3 rounded-2xl text-sm shadow-sm {{ $msg->sender_type == 'admin' ? 'bg-yellow-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-100 rounded-tl-none' }}">
                                @if($msg->attachment)
                                    <div class="mb-2 rounded-xl overflow-hidden border border-gray-100">
                                        <img src="{{ asset($msg->attachment) }}" class="max-w-full max-h-64 object-cover cursor-pointer" onclick="window.open(this.src, '_blank')">
                                    </div>
                                @endif
                                {{ $msg->message }}
                            </div>
                            @if($msg->sender_type == 'admin')
                                <div class="flex items-center justify-end gap-1 mt-1 text-right message-status-container" data-status-id="{{ $msg->id }}">
                                    @if($msg->is_read)
                                        <span class="inline-flex items-center gap-1 text-[10px] text-blue-500 font-bold" title="Seen by driver">
                                            <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L7 17l-5-5"/><path d="M22 10l-7.5 7.5L13 16"/></svg>
                                            Seen
                                        </span>
                                    @elseif($isDriverOnline)
                                        <span class="inline-flex items-center gap-1 text-[10px] text-gray-400 font-medium" title="Delivered to driver device">
                                            <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L7 17l-5-5"/><path d="M22 10l-7.5 7.5L13 16"/></svg>
                                            Delivered
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] text-gray-400 font-medium" title="Sent to server">
                                            <svg class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            Sent
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div id="emptyChatPlaceholder" class="h-full flex flex-col items-center justify-center text-center p-8">
                        <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="message-square" class="w-8 h-8 text-yellow-600"></i>
                        </div>
                        <h4 class="text-gray-400 font-bold uppercase tracking-widest">Start a conversation</h4>
                        <p class="text-sm text-gray-400 mt-1">Send a message to {{ $selectedDriver->first_name }} to begin.</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input (No global loader on submit) -->
            <div class="p-4 bg-white border-t border-gray-100">
                <form id="chatForm" action="{{ route('support.send') }}" method="POST" data-no-loader="true" class="flex items-end gap-2">
                    @csrf
                    <input type="hidden" name="driver_id" value="{{ $selectedDriver->id }}">
                    
                    <div class="flex-1 relative">
                        <textarea 
                            name="message" 
                            id="messageInput"
                            rows="1" 
                            placeholder="Type a message..." 
                            class="w-full px-4 py-3 bg-gray-100 border-none rounded-2xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none resize-none max-h-32 transition-all overflow-hidden"
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                            required
                        ></textarea>
                    </div>
                    <button type="submit" id="sendButton" class="p-3 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 shadow-lg shadow-yellow-100 transition-all flex items-center justify-center">
                        <i data-lucide="send" id="sendIcon" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center text-center p-12 bg-gray-50/20">
                <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center mb-6 rotate-3">
                    <i data-lucide="messages-square" class="w-12 h-12 text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Your Conversations</h3>
                <p class="text-gray-500 text-sm max-w-xs">Select a driver from the left to start chatting or view support requests.</p>
            </div>
        @endif
    </div>
</div>

<!-- FB Messenger Style Unsend Modal -->
<div id="unsendModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-2xl w-full max-w-[450px] overflow-hidden shadow-2xl transform scale-95 opacity-0 transition-all duration-200" id="unsendModalContainer">
        <!-- Header -->
        <div class="p-4 flex justify-between items-center border-b border-gray-100 relative">
            <h3 class="w-full text-center text-lg font-bold text-gray-900 pr-8 pl-8">Who do you want to unsend this message for?</h3>
            <button type="button" onclick="closeUnsendModal()" class="hover:bg-gray-200 transition-colors" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 32px; height: 32px; min-width: 32px; min-height: 32px; max-width: 32px; max-height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background-color: #f3f4f6; border: none; cursor: pointer; padding: 0; outline: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: block; width: 16px; height: 16px; min-width: 16px; min-height: 16px; max-width: 16px; max-height: 16px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Unsend for everyone -->
            <label class="flex items-start gap-4 cursor-pointer group">
                <div class="relative flex items-center justify-center w-6 h-6 mt-0.5 flex-shrink-0">
                    <input type="radio" name="unsend_type" value="for_everyone" class="peer sr-only" checked>
                    <!-- Unchecked state -->
                    <div class="absolute inset-0 rounded-full border-2 border-gray-300 peer-checked:hidden transition-all"></div>
                    <!-- Checked state -->
                    <div class="absolute inset-0 rounded-full border-[6px] border-blue-600 hidden peer-checked:block transition-all"></div>
                </div>
                <div>
                    <h4 class="font-bold text-base text-gray-900 leading-none mb-1">Unsend for everyone</h4>
                    <p class="text-sm text-gray-500 leading-snug">This message will be unsent for everyone in the chat. Others may have already seen or forwarded it. Unsent messages can still be included in reports.</p>
                </div>
            </label>
            
            <!-- Unsend for you -->
            <label class="flex items-start gap-4 cursor-pointer group">
                <div class="relative flex items-center justify-center w-6 h-6 mt-0.5 flex-shrink-0">
                    <input type="radio" name="unsend_type" value="for_me" class="peer sr-only">
                    <!-- Unchecked state -->
                    <div class="absolute inset-0 rounded-full border-2 border-gray-300 peer-checked:hidden transition-all"></div>
                    <!-- Checked state -->
                    <div class="absolute inset-0 rounded-full border-[6px] border-blue-600 hidden peer-checked:block transition-all"></div>
                </div>
                <div>
                    <h4 class="font-bold text-base text-gray-900 leading-none mb-1">Unsend for you</h4>
                    <p class="text-sm text-gray-500 leading-snug">This will remove the message from your devices. Other chat members will still be able to see it.</p>
                </div>
            </label>
        </div>
        
        <!-- Footer -->
        <div class="p-4 flex justify-end gap-3 border-t border-gray-100 bg-gray-50/50">
            <button type="button" onclick="closeUnsendModal()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <button type="button" id="confirmUnsendBtn" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-200 transition-all">
                Remove
            </button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const sendIcon = document.getElementById('sendIcon');
        
        const notifSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');
        const selectedDriverId = @json($selectedDriver ? $selectedDriver->id : null);
        let driverIsOnline = @json(isset($isDriverOnline) && $isDriverOnline ? true : false);
        let lastMessageCount = @json($chatMessages ? count($chatMessages) : 0);
        let originalTitle = document.title;
        let unreadTotal = 0;

        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // --- Status Badge Helper (Messenger-Style) ---
        window.renderStatusBadge = function(status) {
            if (status === 'sending') {
                return `
                    <span class="inline-flex items-center gap-1 text-[10px] text-gray-400 font-medium" title="Sending message...">
                        <svg class="w-2.5 h-2.5 animate-spin text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Sending...
                    </span>
                `;
            } else if (status === 'sent') {
                return `
                    <span class="inline-flex items-center gap-1 text-[10px] text-gray-400 font-medium" title="Sent to server">
                        <svg class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Sent
                    </span>
                `;
            } else if (status === 'delivered') {
                return `
                    <span class="inline-flex items-center gap-1 text-[10px] text-gray-400 font-medium" title="Delivered to driver device">
                        <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L7 17l-5-5"/>
                            <path d="M22 10l-7.5 7.5L13 16"/>
                        </svg>
                        Delivered
                    </span>
                `;
            } else if (status === 'seen') {
                return `
                    <span class="inline-flex items-center gap-1 text-[10px] text-blue-500 font-bold" title="Seen by driver">
                        <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L7 17l-5-5"/>
                            <path d="M22 10l-7.5 7.5L13 16"/>
                        </svg>
                        Seen
                    </span>
                `;
            } else if (status === 'failed') {
                return `
                    <button type="button" class="retry-send-btn inline-flex items-center gap-1 text-[10px] text-red-500 font-bold hover:underline cursor-pointer" title="Tap to retry">
                        <svg class="w-3 h-3 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Failed. Tap to retry
                    </button>
                `;
            }
            return '';
        };

        // --- Sidebar Driver Search & Anti-Autofill ---
        const searchInput = document.getElementById('driver_search_query');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const driverLinks = document.querySelectorAll('.custom-scrollbar a');
                
                driverLinks.forEach(link => {
                    const nameElement = link.querySelector('.font-bold');
                    if (nameElement) {
                        const name = nameElement.textContent.toLowerCase();
                        if (name.includes(query)) {
                            link.style.display = 'flex';
                        } else {
                            link.style.display = 'none';
                        }
                    }
                });
            });

            const clearAutofill = () => {
                if (searchInput.value.includes('@') || searchInput.value === 'sonysunico02@gmail.com') {
                    searchInput.value = '';
                }
            };
            
            clearAutofill();
            setTimeout(clearAutofill, 50);
            setTimeout(clearAutofill, 150);
            searchInput.addEventListener('focus', clearAutofill, { once: true });
        }

        // --- Polling for Active Chat Messages ---
        if (selectedDriverId) {
            setInterval(async () => {
                try {
                    const response = await fetch(`/support-center/${selectedDriverId}/messages`);
                    const data = await response.json();
                    
                    if (data.success) {
                        // Update driver online state
                        if (typeof data.driver_is_online !== 'undefined') {
                            driverIsOnline = Boolean(data.driver_is_online);
                            updateChatHeaderStatus(driverIsOnline, data.driver_last_seen);
                        }

                        // Check for new driver messages to chime sound
                        if (data.messages.length > lastMessageCount) {
                            const newMsgs = data.messages.slice(lastMessageCount);
                            const hasDriverMsg = newMsgs.some(m => m.sender_type === 'driver');
                            if (hasDriverMsg) {
                                notifSound.play().catch(e => console.log('Sound blocked'));
                                flashTitle('New Message!');
                            }
                        }

                        reconcileMessages(data.messages);
                        lastMessageCount = data.messages.length;
                    }
                } catch (e) {
                    console.error('Polling failed', e);
                }
            }, 1000); // Poll every 1s for real-time responsiveness
        }

        // --- Polling for Driver List Status ---
        setInterval(async () => {
            try {
                const response = await fetch('/support-center/status');
                const data = await response.json();
                if (data.success) {
                    updateDriverList(data.drivers);
                }
            } catch (e) {
                console.error('Status polling failed', e);
            }
        }, 3000);

        function flashTitle(text) {
            let count = 0;
            const interval = setInterval(() => {
                document.title = (count % 2 === 0) ? text : originalTitle;
                if (++count >= 6) {
                    clearInterval(interval);
                    document.title = originalTitle;
                }
            }, 500);
        }

        function updateChatHeaderStatus(isOnline, lastSeen) {
            const headerDot = document.getElementById('chatHeaderDot');
            const headerText = document.getElementById('chatHeaderStatusText');
            const headerStatus = document.getElementById('chatHeaderStatus');
            if (!headerDot || !headerText || !headerStatus) return;

            if (isOnline) {
                headerDot.className = 'w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse';
                headerStatus.className = 'text-[10px] text-emerald-500 font-bold uppercase tracking-widest flex items-center gap-1.5';
                headerText.textContent = 'Online (Driver App)';
            } else {
                headerDot.className = 'w-1.5 h-1.5 bg-gray-400 rounded-full';
                headerStatus.className = 'text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-1.5';
                headerText.textContent = lastSeen ? `Active ${lastSeen}` : 'Offline';
            }
        }

        function updateDriverList(drivers) {
            let currentTotalUnread = 0;
            drivers.forEach(driver => {
                currentTotalUnread += parseInt(driver.unread_count || 0);
                const driverLink = document.querySelector(`a[href*="driver_id=${driver.id}"]`);
                if (!driverLink) return;

                const latestMsgP = driverLink.querySelector('p');
                if (latestMsgP) {
                    latestMsgP.innerText = driver.latest_message || 'No messages yet';
                }

                // Update online badge
                const onlineDot = driverLink.querySelector('.driver-online-badge');
                if (onlineDot) {
                    if (driver.is_online_computed) {
                        onlineDot.className = 'driver-online-badge absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full';
                    } else {
                        onlineDot.className = 'driver-online-badge absolute bottom-0 right-0 w-3 h-3 bg-gray-300 border-2 border-white rounded-full';
                    }
                }

                // Handle unread badge visibility
                const avatarContainer = driverLink.querySelector('.relative');
                if (avatarContainer) {
                    let existingBadge = avatarContainer.querySelector('.bg-red-500');
                    if (driver.unread_count > 0) {
                        if (!existingBadge) {
                            existingBadge = document.createElement('span');
                            existingBadge.className = 'absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-black flex items-center justify-center rounded-full border-2 border-white';
                            avatarContainer.appendChild(existingBadge);
                        }
                        existingBadge.innerText = driver.unread_count;
                    } else if (existingBadge) {
                        existingBadge.remove();
                    }
                }
            });

            if (currentTotalUnread > unreadTotal) {
                if (!selectedDriverId) {
                    notifSound.play().catch(e => console.log('Sound blocked'));
                    flashTitle('New Chat!');
                }
            }
            unreadTotal = currentTotalUnread;
        }

        // --- Smooth Message Reconciliation (No Screen Flicker) ---
        function reconcileMessages(messages) {
            if (!chatContainer) return;

            // Remove placeholder if present
            const placeholder = document.getElementById('emptyChatPlaceholder');
            if (placeholder && messages.length > 0) {
                placeholder.remove();
            }

            const currentScrollAtBottom = (chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight) < 80;
            const existingMsgMap = new Map();
            chatContainer.querySelectorAll('[data-msg-id]').forEach(el => {
                existingMsgMap.set(parseInt(el.getAttribute('data-msg-id')), el);
            });

            messages.forEach(msg => {
                const msgId = parseInt(msg.id);
                if (existingMsgMap.has(msgId)) {
                    // Update existing message status if admin
                    const el = existingMsgMap.get(msgId);
                    if (msg.sender_type === 'admin') {
                        const statusContainer = el.querySelector('.message-status-container');
                        if (statusContainer) {
                            let targetStatus = 'sent';
                            if (msg.is_read) {
                                targetStatus = 'seen';
                            } else if (driverIsOnline) {
                                targetStatus = 'delivered';
                            }
                            const currentHtml = statusContainer.innerHTML.trim();
                            const newHtml = renderStatusBadge(targetStatus).trim();
                            if (currentHtml !== newHtml) {
                                statusContainer.innerHTML = newHtml;
                            }
                        }
                    }
                    existingMsgMap.delete(msgId);
                } else {
                    // New message to append
                    const isSystem = msg.sender_type === 'admin';
                    let initialStatus = 'sent';
                    if (msg.is_read) {
                        initialStatus = 'seen';
                    } else if (driverIsOnline) {
                        initialStatus = 'delivered';
                    }

                    const div = document.createElement('div');
                    div.className = `flex ${isSystem ? 'justify-end' : 'justify-start'}`;
                    div.setAttribute('data-msg-id', msg.id);

                    const time = msg.time || (msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now');
                    
                    div.innerHTML = `
                        <div class="max-w-[70%] group">
                            <div class="flex items-center gap-2 mb-1 ${isSystem ? 'flex-row-reverse' : ''}">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">${time}</span>
                                ${isSystem ? `
                                    <div class="relative inline-block dropdown-container">
                                        <button type="button" onclick="toggleDropdown(this)" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100" title="More options">
                                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                        </button>
                                        <div class="dropdown-menu absolute right-0 top-full mt-1 hidden bg-white rounded-xl shadow-lg border border-gray-100 py-1 min-w-[170px] z-50">
                                            <button type="button" onclick="openUnsendModal(${msg.id}, this)" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                            <div class="px-4 py-3 rounded-2xl text-sm shadow-sm ${isSystem ? 'bg-yellow-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-100 rounded-tl-none'}">
                                ${msg.attachment ? `
                                    <div class="mb-2 rounded-xl overflow-hidden border border-gray-100">
                                        <img src="/${msg.attachment}" class="max-w-full max-h-64 object-cover cursor-pointer" onclick="window.open(this.src, '_blank')">
                                    </div>
                                ` : ''}
                                ${msg.message || ''}
                            </div>
                            ${isSystem ? `
                                <div class="flex items-center justify-end gap-1 mt-1 text-right message-status-container" data-status-id="${msg.id}">
                                    ${renderStatusBadge(initialStatus)}
                                </div>
                            ` : ''}
                        </div>
                    `;
                    chatContainer.appendChild(div);
                    if (window.lucide) lucide.createIcons();
                }
            });

            // If some message was removed (unsent) on server, clean it up
            existingMsgMap.forEach((el) => {
                if (!el.dataset.temp) {
                    el.remove();
                }
            });

            if (currentScrollAtBottom) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }

        // --- Dropdown Toggle & Close Handlers ---
        window.toggleDropdown = function(btn) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== btn.nextElementSibling) {
                    menu.classList.add('hidden');
                }
            });
            btn.nextElementSibling.classList.toggle('hidden');
        };

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // --- Unsend Modal Logic ---
        let currentMessageToUnsend = null;
        let currentUnsendBtnElement = null;

        window.openUnsendModal = function(id, btn) {
            currentMessageToUnsend = id;
            currentUnsendBtnElement = btn;
            
            btn.closest('.dropdown-menu').classList.add('hidden');
            document.querySelector('input[name="unsend_type"][value="for_everyone"]').checked = true;
            
            const modal = document.getElementById('unsendModal');
            const container = document.getElementById('unsendModalContainer');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        window.closeUnsendModal = function() {
            const modal = document.getElementById('unsendModal');
            const container = document.getElementById('unsendModalContainer');
            
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                currentMessageToUnsend = null;
                currentUnsendBtnElement = null;
            }, 200);
        };

        document.getElementById('confirmUnsendBtn')?.addEventListener('click', async function() {
            if (!currentMessageToUnsend || !currentUnsendBtnElement) return;
            
            const selectedType = document.querySelector('input[name="unsend_type"]:checked').value;
            const msgId = currentMessageToUnsend;
            
            closeUnsendModal();
            
            let msgDiv = document.querySelector(`[data-msg-id="${msgId}"]`);
            if (!msgDiv) {
                msgDiv = currentUnsendBtnElement.closest('[data-msg-id]') || currentUnsendBtnElement.closest('#chatMessages > div');
            }
            if (msgDiv) msgDiv.style.opacity = '0.5';
            
            try {
                const response = await fetch(`/support-center/message/${msgId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ type: selectedType })
                });
                
                const data = await response.json();
                if (data.success) {
                    if (msgDiv) {
                        msgDiv.style.transition = 'all 0.3s ease';
                        msgDiv.style.opacity = '0';
                        msgDiv.style.maxHeight = '0';
                        msgDiv.style.overflow = 'hidden';
                        msgDiv.style.marginBottom = '0';
                        msgDiv.style.padding = '0';
                        setTimeout(() => msgDiv.remove(), 300);
                    }
                    lastMessageCount = Math.max(0, lastMessageCount - 1);
                } else {
                    alert(data.message || 'Failed to unsend message');
                    if (msgDiv) msgDiv.style.opacity = '1';
                }
            } catch (e) {
                console.error(e);
                alert('Network error while unsending message');
                if (msgDiv) msgDiv.style.opacity = '1';
            }
        });

        // --- Optimistic Messenger-Style Chat Send Action ---
        if (chatForm) {
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (messageInput.value.trim() !== '') {
                        chatForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    }
                }
            });

            async function performSendMessage(text, targetTempDiv) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('driver_id', selectedDriverId);
                formData.append('message', text);

                try {
                    const response = await fetch(chatForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const resData = await response.json();

                    if (response.ok && resData.success) {
                        const newMsgId = resData.data.id;
                        if (typeof resData.driver_is_online !== 'undefined') {
                            driverIsOnline = Boolean(resData.driver_is_online);
                        }

                        targetTempDiv.removeAttribute('data-temp');
                        targetTempDiv.setAttribute('data-msg-id', newMsgId);
                        targetTempDiv.style.opacity = '1';

                        const statusContainer = targetTempDiv.querySelector('.message-status-container');
                        if (statusContainer) {
                            statusContainer.setAttribute('data-status-id', newMsgId);
                            const nextStatus = driverIsOnline ? 'delivered' : 'sent';
                            statusContainer.innerHTML = renderStatusBadge(nextStatus);
                        }

                        // Attach dropdown menu
                        const headerBar = targetTempDiv.querySelector('.flex.items-center.gap-2.mb-1');
                        if (headerBar && !headerBar.querySelector('.dropdown-container')) {
                            const dropdownHtml = `
                                <div class="relative inline-block dropdown-container">
                                    <button type="button" onclick="toggleDropdown(this)" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100" title="More options">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </button>
                                    <div class="dropdown-menu absolute right-0 top-full mt-1 hidden bg-white rounded-xl shadow-lg border border-gray-100 py-1 min-w-[170px] z-50">
                                        <button type="button" onclick="openUnsendModal(${newMsgId}, this)" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            `;
                            headerBar.insertAdjacentHTML('beforeend', dropdownHtml);
                            if (window.lucide) lucide.createIcons();
                        }

                        lastMessageCount++;
                    } else {
                        // Mark as failed
                        const statusContainer = targetTempDiv.querySelector('.message-status-container');
                        if (statusContainer) {
                            statusContainer.innerHTML = renderStatusBadge('failed');
                            attachRetryListener(targetTempDiv, text);
                        }
                    }
                } catch (e) {
                    console.error('Send request failed', e);
                    const statusContainer = targetTempDiv.querySelector('.message-status-container');
                    if (statusContainer) {
                        statusContainer.innerHTML = renderStatusBadge('failed');
                        attachRetryListener(targetTempDiv, text);
                    }
                }
            }

            function attachRetryListener(tempDiv, text) {
                const retryBtn = tempDiv.querySelector('.retry-send-btn');
                if (retryBtn) {
                    retryBtn.addEventListener('click', function() {
                        const statusContainer = tempDiv.querySelector('.message-status-container');
                        if (statusContainer) {
                            statusContainer.innerHTML = renderStatusBadge('sending');
                        }
                        performSendMessage(text, tempDiv);
                    }, { once: true });
                }
            }

            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                if (!message) return;

                // Remove placeholder if present
                const placeholder = document.getElementById('emptyChatPlaceholder');
                if (placeholder) placeholder.remove();

                // Optimistically render bubble immediately
                const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const tempDiv = document.createElement('div');
                tempDiv.className = 'flex justify-end';
                tempDiv.setAttribute('data-temp', 'true');
                tempDiv.innerHTML = `
                    <div class="max-w-[70%] group">
                        <div class="flex items-center gap-2 mb-1 flex-row-reverse">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">${time}</span>
                        </div>
                        <div class="px-4 py-3 rounded-2xl text-sm shadow-sm bg-yellow-600 text-white rounded-tr-none">
                            ${message.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")}
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-1 text-right message-status-container">
                            ${renderStatusBadge('sending')}
                        </div>
                    </div>
                `;
                chatContainer.appendChild(tempDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;

                // Reset input box immediately
                messageInput.value = '';
                messageInput.style.height = 'auto';

                // Send in background without blocking screen
                performSendMessage(message, tempDiv);
            });
        }
    });
</script>
@endsection

