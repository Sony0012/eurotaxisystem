<?php
$file = "C:/xampp/htdocs/eurotaxisystem/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);
$content = str_replace("hover:scale-125", "active:scale-125 md:hover:scale-125", $content);
$content = str_replace("hover:-translate-y-1", "active:-translate-y-1 md:hover:-translate-y-1", $content);
file_put_contents($file, $content);

