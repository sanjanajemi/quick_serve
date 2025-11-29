document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const orderId = form.querySelector("[name='order_id']").value.trim();
      const status = form.querySelector("[name='status']").value;

      // Validate order ID: must be numeric and positive
      if (!orderId || isNaN(orderId) || parseInt(orderId) <= 0) {
        errors.order_id = "Invalid order ID.";
      }

      // Validate status: must be one of allowed values
      const allowedStatuses = ["Received", "Preparing", "Completed", "Cancelled"];
      if (!status || !allowedStatuses.includes(status)) {
        errors.status = "Invalid status selected.";
      }

      // Clear old errors
      document.querySelectorAll(".error-msg").forEach(el => el.remove());

      // Show errors inline
      for (const field in errors) {
        const input = form.querySelector(`[name='${field}']`) || form.querySelector(`#${field}`);
        if (input) {
          const errorEl = document.createElement("span");
          errorEl.className = "error-msg";
          errorEl.style.color = "red";
          errorEl.textContent = errors[field];
          input.insertAdjacentElement("afterend", errorEl);
        }
      }

      // Stop submission if errors exist
      if (Object.keys(errors).length > 0) {
        e.preventDefault();
      }
    });
  });
