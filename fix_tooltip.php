<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = "cursor-pointer z-20 border border-gray-100\" onclick=\"chatShowReactionPicker(event, \${m.id})\">";
$replacement = "cursor-pointer z-20 border border-gray-100\" title=\"\${tooltipText}\" onclick=\"chatShowReactionPicker(event, \${m.id})\">";

if (strpos($content, $target) !== false) {
    $content = str_replace($target, $replacement, $content);
    file_put_contents($file, $content);
    echo "Tooltip added successfully!";
} else {
    echo "Target string not found!";
}

