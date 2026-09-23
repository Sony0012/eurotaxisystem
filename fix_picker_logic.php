<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$scriptStart = strpos($content, "<script>\n    document.addEventListener(\"DOMContentLoaded\"");
$scriptEnd = strpos($content, "</script>", $scriptStart);

$newScript = <<<HTML
<script>
  function bindReactButtons() {
      document.querySelectorAll(".react-btn").forEach(btn => {
          // Remove old listeners to prevent duplicates if called multiple times
          const newBtn = btn.cloneNode(true);
          btn.parentNode.replaceChild(newBtn, btn);
          
          const triggerReact = function(e) {
              e.preventDefault();
              e.stopPropagation();
              if(window.chatReactToMessage) {
                  window.chatReactToMessage(this.getAttribute("data-emoji"));
              }
          };
          newBtn.addEventListener("click", triggerReact);
          newBtn.addEventListener("touchend", triggerReact);
      });
  }
  
  if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => setTimeout(bindReactButtons, 500));
  } else {
      setTimeout(bindReactButtons, 500);
  }
</script>
HTML;

$content = substr_replace($content, $newScript, $scriptStart, $scriptEnd - $scriptStart + 9);

file_put_contents($file, $content);
echo "Fixed script logic!";

