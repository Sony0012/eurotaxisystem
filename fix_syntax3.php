<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (strpos($line, "window.chatReactToMessage = async function") !== false) {
        $start = $i;
    }
    if (strpos($line, "Swipe to Reply Logic") !== false) {
        $end = $i;
    }
}

// Check lines between start and end for floating };
for ($i = $start + 2; $i < $end; $i++) {
    if (trim($lines[$i]) === "};" && trim($lines[$i-1]) === "") {
        if (trim($lines[$i-2]) === "};") {
            $lines[$i] = ""; // remove the floating one
            echo "Removed floating bracket at line $i\n";
        }
    }
}

file_put_contents($file, implode("\n", $lines));

