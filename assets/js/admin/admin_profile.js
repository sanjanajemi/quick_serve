 document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".admin-create-form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      // Name: required, letters/spaces only
      if (!name || !/^[A-Za-z\s]+$/.test(name)) {
        errors.name = "Name must contain only letters and spaces.";
      }

      // Email: required, valid format
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email || !emailPattern.test(email)) {
        errors.email = "Please enter a valid email address.";
      }

      // Password: required, min length 6, must contain letters and numbers
      if (!password || password.length < 6) {
        errors.password = "Password must be at least 6 characters long.";
      } else if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) {
        errors.password = "Password must contain both letters and numbers.";
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