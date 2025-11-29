document.addEventListener("DOMContentLoaded", () => {
  const errors = [];

  // Validate table rows
  document.querySelectorAll("table tr").forEach((row, index) => {
    if (index === 0) return; // skip header row
    const cells = row.querySelectorAll("td");

    const name = cells[0]?.textContent.trim();
    const email = cells[1]?.textContent.trim();
    const phone = cells[3]?.textContent.trim(); // role skipped

    // Name validation
    if (name && !/^[a-zA-Z\s]+$/.test(name)) {
      errors.push(`Row ${index}: Invalid name format.`);
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && !emailPattern.test(email)) {
      errors.push(`Row ${index}: Invalid email format.`);
    }

    // Phone validation
    if (phone && !/^[0-9]{7,15}$/.test(phone)) {
      errors.push(`Row ${index}: Phone number must be 7–15 digits.`);
    }
  });

  // Show validation errors if any
  if (errors.length > 0) {
    const errorBox = document.createElement("div");
    errorBox.className = "error-box";
    errorBox.style.color = "red";
    errorBox.style.marginTop = "10px";
    errorBox.innerHTML = "<strong>Validation Errors:</strong><br>" + errors.join("<br>");
    document.body.insertBefore(errorBox, document.body.firstChild);
  }

  // Confirm delete action
  document.querySelectorAll("a[href*='delete']").forEach(link => {
    link.addEventListener("click", function (e) {
      const staffId = new URL(this.href).searchParams.get("id");
      if (!staffId || isNaN(staffId)) {
        e.preventDefault();
        alert("Invalid staff ID. Cannot delete.");
        return;
      }
      if (!confirm("Are you sure you want to delete this staff member?")) {
        e.preventDefault();
      }
    });
  });
});