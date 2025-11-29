 document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const currentPassword = document.getElementById("current_password").value.trim();
      const newPassword = document.getElementById("new_password").value.trim();
      const confirmPassword = document.getElementById("confirm_password").value.trim();

      // Current password: required
      if (!currentPassword) {
        errors.current_password = "Current password is required.";
      }

      // New password: required, min length 8, must contain letters and numbers
      if (!newPassword) {
        errors.new_password = "New password is required.";
      } else if (newPassword.length < 8) {
        errors.new_password = "New password must be at least 8 characters long.";
      } else if (!/[A-Za-z]/.test(newPassword) || !/[0-9]/.test(newPassword)) {
        errors.new_password = "New password must contain both letters and numbers.";
      }

      // Confirm password: must match new password
      if (!confirmPassword) {
        errors.confirm_password = "Please confirm your new password.";
      } else if (confirmPassword !== newPassword) {
        errors.confirm_password = "Passwords do not match.";
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