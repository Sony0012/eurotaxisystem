<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = <<<EOF
                            <div class="flex items-center justify-end gap-1 mt-1">
                                <span class="text-[9px] \${m.is_mine ? 'text-yellow-100' : 'text-gray-400'}">\${m.time}</span>
                                \${statusHtml}
                            </div>
                            
                            \${reactionsHtml}
                        </div>
EOF;

$replacement = <<<EOF
                            \${reactionsHtml}
                        </div>
                        <div class="flex items-center \${m.is_mine ? 'justify-end' : 'justify-start'} gap-1 mt-1 px-1 w-full">
                            <span class="text-[9px] text-gray-400">\${m.time}</span>
                            \${statusHtml}
                        </div>
EOF;

$content = str_replace($target, $replacement, $content);

file_put_contents($file, $content);
echo "Time moved below the card successfully!";
