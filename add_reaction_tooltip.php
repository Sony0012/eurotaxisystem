<?php
$file = "C:/xampp/htdocs/eurotaxisystem/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = "const uniqueEmojis = [...new Set(Object.values(m.reactions))];";
$replacement = <<<JAVASCRIPT
const uniqueEmojis = [...new Set(Object.values(m.reactions))];
                let tooltipParts = [];
                for (const [uId, emj] of Object.entries(m.reactions)) {
                    if (String(uId) === String(chatActiveUser.id)) {
                        tooltipParts.push(chatActiveUser.name + " " + emj);
                    } else {
                        tooltipParts.push("You " + emj);
                    }
                }
                const tooltipText = tooltipParts.join("\\n");
JAVASCRIPT;

$content = str_replace($target, $replacement, $content);

$target2 = "px-1.5 py-0.5 text-[10px] flex items-center gap-0.5 cursor-pointer hover:bg-gray-50 z-20\"";
$replacement2 = "px-1.5 py-0.5 text-[10px] flex items-center gap-0.5 cursor-pointer hover:bg-gray-50 z-20\" title=\"\${tooltipText}\"";

$content = str_replace($target2, $replacement2, $content);

file_put_contents($file, $content);
echo "Added tooltips locally!";

