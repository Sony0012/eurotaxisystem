<?php
exec("php -l C:/xampp/htdocs/eurotaxisystem/resources/views/partials/chat-drawer.blade.php", $output, $return_var);
echo implode("\n", $output);

