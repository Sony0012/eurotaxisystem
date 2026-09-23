<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

// Find the start of the garbage
$garbageStart = strpos($content, "    if (!activeReactionMessageId) {\n        debug(\"ERROR: No activeReactionMessageId\");");

if ($garbageStart !== false) {
    // Find where the garbage ends. It should end at the next </script> or another known block.
    // Actually, I can just replace everything from $garbageStart to the end of the original try/catch block.
    $garbageEnd = strpos($content, "    }\n};\n", $garbageStart);
    if ($garbageEnd !== false) {
        $garbageEnd += 5; // include "}\n};\n"
        $content = substr_replace($content, "", $garbageStart, $garbageEnd - $garbageStart);
        file_put_contents($file, $content);
        echo "Fixed garbage!";
    } else {
        echo "Could not find garbage end.";
    }
} else {
    echo "Could not find garbage start.";
}

