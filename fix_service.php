<?php
$content = file_get_contents('app/Services/NotificationService.php');
$content = preg_replace('/->where\(function\(\$q\)\s*\{\s*\$q->where\(\'is_resolved\', false\)\s*->orWhereDate\(\'created_at\', today\(\)\);\s*\}\)/', "->where('created_at', '>=', now()->subDays(30))", $content);
file_put_contents('app/Services/NotificationService.php', $content);
echo "Replaced all occurrences.\n";
