<?php
$file = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$content = file_get_contents($file);

$pickerStart = strpos($content, "<div id=\"chatReactionPicker\"");
$pickerEnd = strpos($content, "</div>", $pickerStart) + 6;

$newPicker = <<<HTML
  <div id="chatReactionPicker" class="hidden fixed z-[999999] bg-white rounded-full shadow-2xl border border-gray-100 px-3 py-2 items-center gap-2 animate-bounce-short">
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125" data-emoji="??">??</button>
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125" data-emoji="??">??</button>
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125" data-emoji="??">??</button>
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125" data-emoji="??">??</button>
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125" data-emoji="??">??</button>
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125" data-emoji="??">??</button>
      <button class="react-btn text-xl transition-transform origin-bottom active:scale-125 md:hover:scale-125 text-red-500 ml-2 border-l border-gray-200 pl-2" data-emoji="">
          <svg class="w-5 h-5 inline-block pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
      </button>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            document.querySelectorAll(".react-btn").forEach(btn => {
                const triggerReact = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if(window.chatReactToMessage) {
                        window.chatReactToMessage(this.getAttribute("data-emoji"));
                    }
                };
                btn.addEventListener("click", triggerReact);
                btn.addEventListener("touchend", triggerReact);
            });
        }, 1000);
    });
  </script>
HTML;

$content = substr_replace($content, $newPicker, $pickerStart, $pickerEnd - $pickerStart);

file_put_contents($file, $content);
echo "Fixed picker!";

