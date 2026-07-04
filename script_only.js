
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
