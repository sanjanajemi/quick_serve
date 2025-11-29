document.addEventListener("DOMContentLoaded", () => {
    const errors = [];

    // Validate metrics boxes
    document.querySelectorAll(".metrics-box .box").forEach((box, index) => {
      const valueEl = box.querySelector("p strong");
      if (!valueEl) return;
      const valueText = valueEl.textContent.trim();

      // Check numeric values
      if (box.classList.contains("total") || box.classList.contains("revenue")) {
        const num = parseFloat(valueText.replace(",", ""));
        if (isNaN(num) || num < 0) {
          errors.push(`Metric ${index + 1}: Invalid numeric value (${valueText}).`);
        }
      } else {
        const num = parseInt(valueText, 10);
        if (isNaN(num) || num < 0) {
          errors.push(`Metric ${index + 1}: Invalid order count (${valueText}).`);
        }
      }
    });

    // Validate weekly summary table
    document.querySelectorAll("table tbody tr").forEach((row, index) => {
      const cells = row.querySelectorAll("td");
      if (cells.length < 3) return;

      const dateText = cells[0].textContent.trim();
      const ordersText = cells[1].textContent.trim();
      const revenueText = cells[2].textContent.trim();

      // Date must be valid
      if (isNaN(Date.parse(dateText))) {
        errors.push(`Row ${index + 1}: Invalid date (${dateText}).`);
      }

      // Orders must be non-negative integer
      if (isNaN(parseInt(ordersText, 10)) || parseInt(ordersText, 10) < 0) {
        errors.push(`Row ${index + 1}: Invalid received orders (${ordersText}).`);
      }

      // Revenue must be numeric and non-negative
      const revenueNum = parseFloat(revenueText.replace(",", ""));
      if (isNaN(revenueNum) || revenueNum < 0) {
        errors.push(`Row ${index + 1}: Invalid revenue (${revenueText}).`);
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
  });
