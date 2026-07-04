<?php
$file = "C:/xampp/htdocs/eurotaxisystem/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = "if (x > rect.right - 220) x = rect.right - 220; // picker is roughly 200px wide";
$replacement = "if (x > rect.right - 310) x = rect.right - 310; // picker is roughly 280px wide";

$content = str_replace($target, $replacement, $content);
file_put_contents($file, $content);

echo "Fixed locally!";

