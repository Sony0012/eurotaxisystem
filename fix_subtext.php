<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = "document.getElementById('chatHeaderTitle').innerHTML =\n            `\${iconHtml} <span class=\"pointer-events-none\">\${userName}</span>`;";
$replacement = "document.getElementById('chatHeaderTitle').innerHTML =\n            `\${iconHtml} <span class=\"pointer-events-none\">\${userName}</span>`;\n        document.getElementById('chatHeaderSub').innerHTML = subText;";

$content = str_replace($target, $replacement, $content);

file_put_contents($file, $content);
echo "Subtext updated!";
