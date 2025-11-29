document.addEventListener("DOMContentLoaded", () => {
    const errors = [];

    // Validate each order row
    document.querySelectorAll("tbody tr").forEach((row, index) => {
      const cells = row.querySelectorAll("td");
      if (cells.length < 6) return; // skip "No orders found" row

      const orderId = cells[0]?.textContent.trim();
      const customer = cells[1]?.textContent.trim();
      const status = cells[2]?.textContent.trim();
      const total = cells[3]?.textContent.trim();
      const time = cells[4]?.textContent.trim();

      // Order ID: must be numeric and positive
      if (!orderId || isNaN(orderId) || parseInt(orderId) <= 0) {
        errors.push(`Row ${index + 1}: Invalid Order ID.`);
      }

      // Customer name: must contain letters/spaces
      if (customer && !/^[A-Za-z\s]+$/.test(customer)) {
        errors.push(`Row ${index + 1}: Invalid customer name.`);
      }

      // Status: must be one of allowed values or "Not Set"
      const allowedStatuses = ["Received", "Preparing", "Completed", "Cancelled", "Not Set"];
      if (status && !allowedStatuses.includes(status)) {
        errors.push(`Row ${index + 1}: Invalid status value.`);
      }

      // Total: must be numeric
      if (total && isNaN(total.replace(",", ""))) {
        errors.push(`Row ${index + 1}: Invalid total amount.`);
      }

      // Time: must not be empty
      if (!time) {
        errors.push(`Row ${index + 1}: Missing placed time.`);
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

    // Confirm delete action with validation
    document.querySelectorAll("form[action*='order/delete']").forEach(form => {
      form.addEventListener("submit", function (e) {
        const orderId = this.querySelector("[name='order_id']").value;
        if (!orderId || isNaN(orderId) || parseInt(orderId) <= 0) {
          e.preventDefault();
          alert("Invalid order ID. Cannot delete.");
          return;
        }
        if (!confirm("Are you sure you want to delete this order?")) {
          e.preventDefault();
        }
      });
    });
  });
