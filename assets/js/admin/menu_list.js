 document.addEventListener("DOMContentLoaded", () => {
    const errors = [];

    // Validate each menu card
    document.querySelectorAll(".menu-card").forEach((card, index) => {
      const name = card.querySelector("h3")?.textContent.trim();
      const variant = card.querySelector("p:nth-of-type(1)")?.textContent.replace("Variant:", "").trim();
      const priceText = card.querySelector("p:nth-of-type(2)")?.textContent.trim();
      const category = card.querySelector("p:nth-of-type(3)")?.textContent.replace("Category:", "").trim();
      const status = card.querySelector(".status-badge")?.textContent.trim();

      //  Name validation: allow letters, numbers, spaces, hyphens, commas, periods
      if (!name || !/^[A-Za-z0-9\s\-.,]+$/.test(name)) {
        errors.push(`Card ${index + 1}: Invalid item name (${name}).`);
      }

      // Variant validation: optional, but if present allow alphanumeric + hyphens/spaces
      if (variant && !/^[A-Za-z0-9\s\-]+$/.test(variant)) {
        errors.push(`Card ${index + 1}: Invalid variant type (${variant}).`);
      }

      //  Price validation: strip non-numeric characters before parsing
      const priceClean = priceText.replace(/[^0-9.]/g, "");
      const price = parseFloat(priceClean);
      if (isNaN(price) || price < 0) {
        errors.push(`Card ${index + 1}: Invalid price value (${priceText}).`);
      }

      //  Category validation: optional, but must be at least 2 characters
      if (category && category.length < 2) {
        errors.push(`Card ${index + 1}: Invalid category (${category}).`);
      }

      //  Status validation: must be published or unpublished
      const allowedStatuses = ["published", "unpublished"];
      if (status && !allowedStatuses.includes(status.toLowerCase())) {
        errors.push(`Card ${index + 1}: Invalid status (${status}).`);
      }
    });

    // Show validation errors if any
    if (errors.length > 0) {
      const errorBox = document.createElement("div");
      errorBox.className = "error-box";
      errorBox.style.color = "red";
      errorBox.style.margin = "10px 0";
      errorBox.style.padding = "10px";
      errorBox.style.border = "1px solid red";
      errorBox.innerHTML = "<strong>Validation Errors:</strong><br>" + errors.join("<br>");
      document.body.insertBefore(errorBox, document.body.firstChild);
    }

    // Confirm delete action with validation
    document.querySelectorAll("a.delete").forEach(link => {
      link.addEventListener("click", function (e) {
        const urlParams = new URLSearchParams(this.href.split("?")[1]);
        const itemId = urlParams.get("id");
        if (!itemId || isNaN(itemId) || parseInt(itemId) <= 0) {
          e.preventDefault();
          alert("Invalid menu item ID. Cannot delete.");
          return;
        }
        if (!confirm("Are you sure you want to delete this item?")) {
          e.preventDefault();
        }
      });
    });

    // Confirm publish/unpublish actions
    document.querySelectorAll(".actions a").forEach(link => {
      if (link.href.includes("publish") || link.href.includes("unpublish")) {
        link.addEventListener("click", function (e) {
          const urlParams = new URLSearchParams(this.href.split("?")[1]);
          const itemId = urlParams.get("id");
          if (!itemId || isNaN(itemId) || parseInt(itemId) <= 0) {
            e.preventDefault();
            alert("Invalid menu item ID. Cannot change status.");
            return;
          }
        });
      }
    });
  });