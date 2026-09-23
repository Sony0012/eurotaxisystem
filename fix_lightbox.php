<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

// Update chatMediaContainer
$targetContainer = 'id="chatMediaContainer" class="max-w-[90vw] max-h-[90vh] flex items-center justify-center"';
$replacementContainer = 'id="chatMediaContainer" class="w-full h-full p-4 md:p-10 flex items-center justify-center"';
$content = str_replace($targetContainer, $replacementContainer, $content);

// Update img injection
$targetImg = '<img src="${url}" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl"';
$replacementImg = '<img src="${url}" class="w-full h-full object-contain rounded-xl shadow-2xl"';
$content = str_replace($targetImg, $replacementImg, $content);

// Update video injection
$targetVideo = '<video src="${url}" controls autoplay class="max-w-full max-h-[90vh] rounded-xl shadow-2xl"';
$replacementVideo = '<video src="${url}" controls autoplay class="w-full h-full object-contain rounded-xl shadow-2xl"';
$content = str_replace($targetVideo, $replacementVideo, $content);

file_put_contents($file, $content);
echo "Lightbox size fixed!";
