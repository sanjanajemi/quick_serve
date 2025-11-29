
  function validateProfileForm() {
    const errors = {};

    // Grab values
    const name = document.querySelector("[name='name']").value.trim();
    const email = document.querySelector("[name='email']").value.trim();
    const phone = document.querySelector("[name='phone']").value.trim();
    const role = document.querySelector("[name='role']").value.trim();

    // Name: required, only letters/spaces
    if (!name || !/^[a-zA-Z\s]+$/.test(name)) {
      errors.name = "Name must contain only letters and spaces.";
    }

    // Email: required, valid format
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errors.email = "Please enter a valid email address.";
    }

    // Phone: optional, but if present must be digits and 7–15 chars
    if (phone && !/^[0-9]{7,15}$/.test(phone)) {
      errors.phone = "Phone number must be 7–15 digits.";
    }

    // Role: required, must be one of allowed roles
    const allowedRoles = ["Manager", "Chef", "Waiter", "Staff"];
    if (!role || !allowedRoles.includes(role)) {
      errors.role = "Invalid role selected.";
    }

    // Show errors
    displayErrors(errors);

    // Return false if any errors
    return Object.keys(errors).length === 0;
  }

  function displayErrors(errors) {
    // Clear old errors
    document.querySelectorAll(".error-msg").forEach(el => el.remove());

    for (const field in errors) {
      const input = document.querySelector(`[name='${field}']`);
      if (input) {
        const errorEl = document.createElement("span");
        errorEl.className = "error-msg";
        errorEl.style.color = "red";
        errorEl.textContent = errors[field];
        input.parentNode.appendChild(errorEl);
      }
    }
  }

  // Attach validation to form submit
  document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#profileForm");
    if (form) {
      form.addEventListener("submit", function (e) {
        if (!validateProfileForm()) {
          e.preventDefault(); // stop submission if invalid
        }
      });
    }
  });