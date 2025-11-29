 document.addEventListener("DOMContentLoaded", () => {
    // Apply saved mode on load
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }

    // Attach validation to all action links
    document.querySelectorAll("a.button").forEach(link => {
      if (link.href.includes("publish") || link.href.includes("unpublish")) {
        link.addEventListener("click", function (e) {
          const urlParams = new URLSearchParams(this.href.split("?")[1]);
          const itemId = urlParams.get("id");

          // Validate menu_item_id
          if (!itemId || isNaN(itemId) || parseInt(itemId) <= 0) {
            e.preventDefault();
            alert("Invalid menu item ID. Action cannot be performed.");
            return;
          }

          // Optional confirmation
          const action = this.href.includes("publish") ? "publish" : "unpublish";
          if (!confirm(`Are you sure you want to ${action} this item?`)) {
            e.preventDefault();
          }
        });
      }
    });
  });

  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
  }
