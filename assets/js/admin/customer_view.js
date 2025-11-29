document.addEventListener("DOMContentLoaded", () => {
    const errors = [];

    // Validate customer details table
    const name = document.querySelector(".details tr:nth-of-type(1) td")?.textContent.trim();
    const email = document.querySelector(".details tr:nth-of-type(2) td")?.textContent.trim();
    const accountStatus = document.querySelector(".details tr:nth-of-type(3) td")?.textContent.trim();

    // Name: must contain letters/spaces
    if (name && !/^[A-Za-z\s]+$/.test(name)) {
      errors.push("Invalid customer name format.");
    }

    // Email: must be valid format
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && !emailPattern.test(email)) {
      errors.push("Invalid email address format.");
    }

    // Account status: must be one of allowed values
    const allowedStatuses = ["Active", "Inactive", "Suspended", "N/A"];
    if (accountStatus && !allowedStatuses.includes(accountStatus)) {
      errors.push(`Invalid account status: ${accountStatus}`);
    }

    // Validate order history table if present
    document.querySelectorAll("#orderHistory tbody tr").forEach((row, index) => {
      const cells = row.querySelectorAll("td");
      if (cells.length < 4) return;

      const orderId = cells[0].textContent.trim();
      const dateText = cells[1].textContent.trim();
      const status = cells[2].textContent.trim();
      const amountText = cells[3].textContent.replace("DKK", "").trim();

      // Order ID: must be numeric
      if (!orderId || isNaN(orderId) || parseInt(orderId) <= 0) {
        errors.push(`Row ${index + 1}: Invalid Order ID.`);
      }

      // Date: must be valid
      if (isNaN(Date.parse(dateText))) {
        errors.push(`Row ${index + 1}: Invalid date (${dateText}).`);
      }

      // Status: must be non-empty
      if (!status) {
        errors.push(`Row ${index + 1}: Missing order status.`);
      }

      // Amount: must be numeric and non-negative
      const amount = parseFloat(amountText);
      if (isNaN(amount) || amount < 0) {
        errors.push(`Row ${index + 1}: Invalid amount (${amountText}).`);
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
    document.querySelectorAll("a.delete").forEach(link => {
      link.addEventListener("click", function (e) {
        const urlParams = new URLSearchParams(this.href.split("?")[1]);
        const customerId = urlParams.get("id");
        if (!customerId || isNaN(customerId) || parseInt(customerId) <= 0) {
          e.preventDefault();
          alert("Invalid customer ID. Cannot delete.");
          return;
        }
        if (!confirm("Are you sure you want to delete this customer?")) {
          e.preventDefault();
        }
      });
    });
  });
