 document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".edit-profile-card");

    form.addEventListener("submit", function (e) {
      const errors = {};

      // Full Name: required, letters/spaces only
      const name = document.getElementById("name").value.trim();
      if (!name || !/^[a-zA-Z\s]+$/.test(name)) {
        errors.name = "Name must contain only letters and spaces.";
      }

      // Email: required, valid format
      const email = document.getElementById("email").value.trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email || !emailPattern.test(email)) {
        errors.email = "Please enter a valid email address.";
      }

      // Phone: optional, but if present must be digits and 7–15 chars
      const phone = document.getElementById("phone").value.trim();
      if (phone && !/^[0-9]{7,15}$/.test(phone)) {
        errors.phone = "Phone number must be 7–15 digits.";
      }

      // Profile Picture: optional, but if present must be image file
      const profilePic = document.getElementById("profile_picture").files[0];
      if (profilePic) {
        const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!allowedTypes.includes(profilePic.type)) {
          errors.profile_picture = "Profile picture must be JPG, PNG, or GIF.";
        }
      }

      // Role: required, must be one of allowed
      const role = document.getElementById("role").value;
      const allowedRoles = ["Manager", "Chef", "Waiter"];
      if (!allowedRoles.includes(role)) {
        errors.role = "Invalid role selected.";
      }

      // Show errors inline
      document.querySelectorAll(".error-msg").forEach(el => el.remove());
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
