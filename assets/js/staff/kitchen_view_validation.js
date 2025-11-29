// Validate order ID before any action
  function isValidOrderId(orderId) {
    return Number.isInteger(orderId) && orderId > 0;
  }

  // Validate status before update
  function isValidStatus(status) {
    const allowedStatuses = ["Received", "Preparing", "Ready"];
    return allowedStatuses.includes(status);
  }

  // Update status with validation
  function updateStatus(orderId, status) {
    if (!isValidOrderId(orderId)) {
      alert("Invalid order ID. Cannot update status.");
      return;
    }
    if (!isValidStatus(status)) {
      alert("Invalid status value.");
      return;
    }

    // Proceed with AJAX/fetch call
    fetch("/quick_serve/staff/update-status", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `order_id=${encodeURIComponent(orderId)}&status=${encodeURIComponent(status)}`
    })
    .then(response => {
      if (!response.ok) throw new Error("Failed to update status");
      document.getElementById("statusSound").play();
      location.reload();
    })
    .catch(() => alert("Error updating order status."));
  }

  // Send email with validation
  function sendEmail(orderId) {
    if (!isValidOrderId(orderId)) {
      alert("Invalid order ID. Cannot send email.");
      return;
    }

    fetch("/quick_serve/staff/send-email", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `order_id=${encodeURIComponent(orderId)}`
    })
    .then(response => {
      if (!response.ok) throw new Error("Failed to send email");
      document.getElementById("emailSound").play();
      alert("Email sent successfully.");
    })
    .catch(() => alert("Error sending email."));
  }

  // Remove card safely
  function removeOrderCard(button) {
    const card = button.closest(".order-card");
    if (!card) return;

    const orderId = parseInt(card.dataset.orderId, 10);
    if (!isValidOrderId(orderId)) {
      alert("Invalid order ID. Cannot remove card.");
      return;
    }

    card.remove();
  }

  // Search validation
  function searchOrders() {
    const query = document.getElementById("searchBar").value.trim();
    if (query.length < 2) return; // avoid too-short queries
    // Filtering logic here...
  }

  // Filter validation
  function filterOrders() {
    const filter = document.getElementById("statusFilter").value;
    const allowedFilters = ["all", "Received", "Preparing", "Ready", "nostatus"];
    if (!allowedFilters.includes(filter)) {
      alert("Invalid filter option.");
      return;
    }
    // Filtering logic here...
  }

  // Sort validation
  function sortByWaitingTime() {
    const cards = document.querySelectorAll(".order-card");
    if (cards.length === 0) {
      alert("No orders to sort.");
      return;
    }
    // Sorting logic here...
  }

  // Dark mode toggle
  if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
  }
  function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    const isDark = document.body.classList.contains("dark-mode");
    localStorage.setItem("darkMode", isDark ? "enabled" : "disabled");
  }

  // Sound toggle
  function toggleSound() {
    const btn = document.getElementById("soundToggleBtn");
    const enabled = btn.textContent.includes("ON");
    btn.textContent = enabled ? "🔕 Sound Alerts OFF" : "🔔 Sound Alerts ON";
  }
