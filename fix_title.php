<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$target = <<<EOF
            const badge = document.getElementById('chatUnreadBadge');
            if (badge) {
                badge.textContent = totalUnread;
                totalUnread > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
            }
            if (window.lucide) window.lucide.createIcons();
        } catch (e) {
EOF;

$replacement = <<<EOF
            const badge = document.getElementById('chatUnreadBadge');
            if (badge) {
                badge.textContent = totalUnread;
                totalUnread > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
            }
            if (!window.originalDocTitle) {
                window.originalDocTitle = document.title.replace(/^\(\d+\)\s*/, '');
            }
            if (totalUnread > 0) {
                document.title = '(' + totalUnread + ') ' + window.originalDocTitle;
            } else {
                document.title = window.originalDocTitle;
            }
            if (window.lucide) window.lucide.createIcons();
        } catch (e) {
EOF;

$content = str_replace($target, $replacement, $content);
file_put_contents($file, $content);
echo "Document title fix applied!";
