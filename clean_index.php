<?php
$file = 'c:/xampp/htdocs/eurotaxisystem/resources/views/driver-management/index.blade.php';
$content = file_get_contents($file);

// Remove the button
$buttonPattern = '/<button type="button" onclick="openPendingDebtsModal\(\)".*?<\/button>\s*/s';
$content = preg_replace($buttonPattern, '', $content);

// Remove the modal
$modalPattern = '/\{\{-- Pending Debts Modal --\}\}.*?<!-- pending debts modal end -->/s';
// Actually, it doesn't have an end comment. Let's just match the div id="pendingDebtsModal" and its content.
// Since it's hard to match nested divs with regex, let's use string manipulation based on known lines.
$startModal = strpos($content, '{{-- Pending Debts Modal --}}');
$endModal = strpos($content, '<script>', $startModal);
if ($startModal !== false && $endModal !== false) {
    $content = substr_replace($content, '', $startModal, $endModal - $startModal);
}

// Remove the JS functions
$jsStart = strpos($content, 'let showingHistory = false;');
$jsEnd = strpos($content, '</script>', $jsStart);
if ($jsStart !== false && $jsEnd !== false) {
    $content = substr_replace($content, '', $jsStart, $jsEnd - $jsStart);
}

file_put_contents($file, $content);
echo "Cleanup completed successfully.\n";
