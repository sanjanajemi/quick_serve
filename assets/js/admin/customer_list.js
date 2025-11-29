 document.addEventListener("DOMContentLoaded", () => {
    const errors = [];

    // Validate each customer row
    document.querySelectorAll("#customerTable tbody tr").forEach((row, index) => {
      const cells = row.querySelectorAll("td");
      if (cells.length < 3) return;

      const name = cells[0]?.textContent.trim();
      const email = cells[1]?.textContent.trim();

      // Name validation: must contain letters/spaces
      if (name && !/^[A-Za-z\s]+$/.test(name)) {
        errors.push(`Row ${index + 1}: Invalid customer name (${name}).`);
      }

      // Email validation: must be valid format
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email && !emailPattern.test(email)) {
        errors.push(`Row ${index + 1}: Invalid email address (${email}).`);
      }
    });

    // Show validation errors if any
    if (errors.length > 0) {
      const errorBox = document.createElement("div");
      errorBox.className = "error-box";
      errorBox.style.color = "red";
      errorBox.style.margin = "10px 0";
      errorBox.innerHTML = "<strong>Validation Errors:</strong><br>" + errors.join("<br>");
      document.body.insertBefore(errorBox, document.body.firstChild);
    }

    // Validate "View" action links
    document.querySelectorAll("a.view-link").forEach(link => {
      link.addEventListener("click", function (e) {
        const urlParams = new URLSearchParams(this.href.split("?")[1]);
        const customerId = urlParams.get("id");
        if (!customerId || isNaN(customerId) || parseInt(customerId) <= 0) {
          e.preventDefault();
          alert("Invalid customer ID. Cannot view details.");
        }
      });
    });
  });