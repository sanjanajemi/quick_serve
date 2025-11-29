 document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const role = document.getElementById("role").value;

      // Name: must contain only letters and spaces
      if (!/^[A-Za-z\s]+$/.test(name)) {
        errors.name = "Full Name must contain only letters and spaces.";
      }

      // Email: must be valid format
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        errors.email = "Please enter a valid email address.";
      }

      // Phone: optional, but if present must be exactly 10 digits
      if (phone && !/^\d{10}$/.test(phone)) {
        errors.phone = "Phone number must be exactly 10 digits.";
      }

      // Role: must be selected
      if (!role) {
        errors.role = "Please select a role.";
      }

      // Clear old errors
      document.querySelectorAll(".error-msg").forEach(el => el.remove());

      // Show errors inline
      for (const field in errors) {
        const input = document.getElementById(field);
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
