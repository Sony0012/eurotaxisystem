<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = 'class="absolute bottom-[80px] right-4 bg-yellow-500 text-white rounded-full p-2 shadow-lg hover:bg-yellow-600 transition-all duration-200 opacity-0 pointer-events-none translate-y-4 z-40"';
$replacement = 'class="absolute bottom-[80px] left-1/2 -translate-x-1/2 bg-yellow-500 text-white rounded-full p-2 shadow-lg hover:bg-yellow-600 transition-all duration-200 opacity-0 pointer-events-none translate-y-4 z-40"';

$content = str_replace($target, $replacement, $content);

file_put_contents($file, $content);
echo "Button centered successfully!";
