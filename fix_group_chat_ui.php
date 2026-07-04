<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = <<<EOF
                    <div class="flex flex-col \${m.is_mine ? 'items-end' : 'items-start'} max-w-[75%] relative">
                        \${repliedTopHtml}
                        \${repliedMessageBubbleHtml}
EOF;

$replacement = <<<EOF
                    <div class="flex flex-col \${m.is_mine ? 'items-end' : 'items-start'} max-w-[75%] relative">
                        \${(!m.is_mine && chatActiveUser.id === 0) ? `<span class="text-[10px] text-gray-500 font-bold ml-1 mb-0.5">\${escapeHtml(m.sender)}</span>` : ''}
                        \${repliedTopHtml}
                        \${repliedMessageBubbleHtml}
EOF;

$content = str_replace($target, $replacement, $content);

file_put_contents($file, $content);
echo "Group chat UI updated successfully!";
