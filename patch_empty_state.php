<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = <<<EOF
            const box = document.getElementById('staffChatMessages');
            let lastIsMine = null;

            box.innerHTML = messages.map((m, index) => {
EOF;

$replacement = <<<EOF
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
                box.innerHTML = messages.map((m, index) => {
EOF;

$content = str_replace($target, $replacement, $content);

// We also need to close the else block further down.
$target2 = <<<EOF
            // Only scroll to bottom if user is already at the bottom or it's first load
EOF;

$replacement2 = <<<EOF
                }).join('');
            }
            
            // Only scroll to bottom if user is already at the bottom or it's first load
EOF;

// wait, the original code already has:
/*
                return `...`;
            }).join('');
            
            // Only scroll to bottom...
*/
// so I just need to remove the `.join('')` from the original code and put it inside the `}` of the `else`.

$target3 = <<<EOF
                </div>
            `}).join('');
            
            // Only scroll to bottom if user is already at the bottom or it's first load
EOF;

$replacement3 = <<<EOF
                </div>
            `}).join('');
            }
            
            // Only scroll to bottom if user is already at the bottom or it's first load
EOF;

$content = str_replace($target3, $replacement3, $content);

file_put_contents($file, $content);
echo "Empty state applied!";
