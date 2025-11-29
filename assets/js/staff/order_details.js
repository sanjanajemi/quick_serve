document.addEventListener("DOMContentLoaded", () => {
    validateOrderDetails();
    
    // Apply saved mode on load
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }
  });

    function validateOrderDetails() {
    const errors = [];

    // Validate Order ID
    const orderIdEl = document.querySelector("header h1");
    if (orderIdEl) {
      const orderIdText = orderIdEl.textContent.match(/Order #(\d+)/);
      if (!orderIdText || isNaN(orderIdText[1])) {
        errors.push("Invalid order ID.");
      }
    }

    // Validate Final Amount
  const amountEl = document.querySelector(".order-summary li:nth-child(3)");
  if (amountEl) {

  const amountText = amountEl.textContent.replace(/[^0-9.]/g, "").trim();
  if (isNaN(parseFloat(amountText))) {
    errors.push("Final amount is not valid.");
  }
}

    // Validate Customer Email
    const emailEl = document.querySelector(".customer-info li:nth-child(2)");
    if (emailEl) {
      const emailText = emailEl.textContent.replace("Email:", "").trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (emailText && !emailPattern.test(emailText)) {
        errors.push("Customer email format is invalid.");
      }
    }

    // Validate Items
    document.querySelectorAll(".item-list tbody tr").forEach((row, index) => {
      const qty = row.children[1].textContent.trim();
      const price = row.children[2].textContent.replace("DKK", "").trim();

      if (isNaN(parseInt(qty)) || parseInt(qty) <= 0) {
        errors.push(`Item ${index + 1}: Quantity must be a positive number.`);
      }
      if (isNaN(parseFloat(price)) || parseFloat(price) < 0) {
        errors.push(`Item ${index + 1}: Price must be a valid amount.`);
      }
    });

    // Show errors if any
    if (errors.length > 0) {
      alert("Validation Errors:\n\n" + errors.join("\n"));
    }
  }

  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
  }