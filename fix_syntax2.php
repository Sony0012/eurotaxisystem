<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = "};
    
    
};

// --- Swipe to Reply Logic ------------------------------------";

if (strpos($content, $target) !== false) {
    $replacement = "};

// --- Swipe to Reply Logic ------------------------------------";
    $content = str_replace($target, $replacement, $content);
    file_put_contents($file, $content);
    echo "Fixed floating bracket!";
} else {
    // maybe spacing is different
    $content = preg_replace("/};\s*};\s*\/\/\s*--- Swipe to Reply Logic/m", "};\n\n// --- Swipe to Reply Logic", $content);
    file_put_contents($file, $content);
    echo "Used regex to fix floating bracket!";
}

