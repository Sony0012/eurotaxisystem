<?php
$content = file_get_contents("C:/xampp/htdocs/eurotaxisystem/resources/views/partials/chat-drawer.blade.php");
echo substr_count($content, "window.chatSetReply = function");

