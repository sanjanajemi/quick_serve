document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const currentPassword = document.getElementById("current_password").value.trim();
      const newPassword = document.getElementById("new_password").value.trim();
      const confirmPassword = document.getElementById("confirm_password").value.trim();

      // Current password: required
      if (currentPassword.length === 0) {
        errors.current_password = "Please enter your current password.";
      }

      // New password: required, min length 6, must differ from current
      if (newPassword.length === 0) {
        errors.new_password = "New password is required.";
      } else if (newPassword.length < 6) {
        errors.new_password = "New password must be at least 6 characters long.";
      } else if (newPassword === currentPassword) {
        errors.new_password = "New password must be different from current password.";
      }

      // Confirm password: must match new password
      if (confirmPassword.length === 0) {
        errors.confirm_password = "Please confirm your new password.";
      } else if (newPassword !== confirmPassword) {
        errors.confirm_password = "New password and confirmation do not match.";
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
