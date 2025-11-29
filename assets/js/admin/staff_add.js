 document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const name = form.querySelector("[name='name']").value.trim();
      const email = form.querySelector("[name='email']").value.trim();
      const phone = form.querySelector("[name='phone']").value.trim();
      const role = form.querySelector("[name='role']").value;
      const password = form.querySelector("[name='password']").value.trim();
      const profilePic = form.querySelector("[name='profile_picture']").files[0];

      // Name: required, letters/spaces only
      if (!name || !/^[A-Za-z\s]+$/.test(name)) {
        errors.name = "Name must contain only letters and spaces.";
      }

      // Email: required, valid format
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email || !emailPattern.test(email)) {
        errors.email = "Please enter a valid email address.";
      }

      // Phone: optional, but if present must be 7–15 digits
      if (phone && !/^[0-9]{7,15}$/.test(phone)) {
        errors.phone = "Phone number must be 7–15 digits.";
      }

      // Role: required, must be one of allowed
      const allowedRoles = ["manager", "staff", "waiter"];
      if (!role || !allowedRoles.includes(role)) {
        errors.role = "Invalid role selected.";
      }

      // Password: required, min length 6
      if (!password || password.length < 6) {
        errors.password = "Password must be at least 6 characters long.";
      }

      // Profile picture: optional, must be image file if provided
      if (profilePic) {
        const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!allowedTypes.includes(profilePic.type)) {
          errors.profile_picture = "Profile picture must be JPG, PNG, or GIF.";
        }
      }

      // Clear old errors
      document.querySelectorAll(".error-msg").forEach(el => el.remove());

      // Show errors inline
      for (const field in errors) {
        const input = form.querySelector(`[name='${field}']`);
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
