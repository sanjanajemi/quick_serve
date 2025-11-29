document.addEventListener("DOMContentLoaded", () => {
    const errors = [];

    // Validate Name
    const nameEl = document.querySelector(".profile-container p:nth-of-type(1)");
    if (nameEl) {
      const nameText = nameEl.textContent.replace("Name:", "").trim();
      if (!/^[a-zA-Z\s]+$/.test(nameText)) {
        errors.push("Invalid name format.");
      }
    }

    // Validate Email
    const emailEl = document.querySelector(".profile-container p:nth-of-type(2)");
    if (emailEl) {
      const emailText = emailEl.textContent.replace("Email:", "").trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (emailText && !emailPattern.test(emailText)) {
        errors.push("Invalid email format.");
      }
    }

    // Validate Phone (if provided)
    const phoneEl = document.querySelector(".profile-container p:nth-of-type(3)");
    if (phoneEl) {
      const phoneText = phoneEl.textContent.replace("Phone:", "").trim();
      if (phoneText !== "Not provided" && !/^[0-9]{7,15}$/.test(phoneText)) {
        errors.push("Phone number must be 7–15 digits.");
      }
    }

    // Validate Role
    const roleEl = document.querySelector(".profile-container p:nth-of-type(4)");
    if (roleEl) {
      const roleText = roleEl.textContent.replace("Role:", "").trim();
      const allowedRoles = ["Manager", "Chef", "Waiter", "Staff"];
      if (!allowedRoles.includes(roleText)) {
        errors.push("Invalid role value.");
      }
    }

    // Validate Profile Picture filename
    const imgEl = document.querySelector(".profile-container img");
    if (imgEl) {
      const src = imgEl.getAttribute("src");
      if (!/^[a-zA-Z0-9_\-\/\.]+$/.test(src)) {
        errors.push("Invalid profile picture filename.");
      }
    }

    // Show errors if any
    if (errors.length > 0) {
      const errorBox = document.createElement("div");
      errorBox.className = "error-box";
      errorBox.style.color = "red";
      errorBox.style.marginTop = "10px";
      errorBox.innerHTML = "<strong>Validation Errors:</strong><br>" + errors.join("<br>");
      document.querySelector(".profile-container").appendChild(errorBox);
    }
  });