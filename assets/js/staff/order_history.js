function restoreOrder(orderId) {
    // Validate orderId before sending
    if (!Number.isInteger(orderId) || orderId <= 0) {
      alert("Invalid order ID. Cannot restore order.");
      return;
    }

    // Optional confirmation
    if (!confirm("Are you sure you want to restore this order to the dashboard?")) {
      return;
    }

    // Safe fetch request
    fetch('/quick_serve/staff/restore-order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `order_id=${encodeURIComponent(orderId)}`
    })
    .then(response => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      window.location.href = '/quick_serve/staff/order-history?restored=1';
    })
    .catch(() => {
      alert('Failed to restore order. Please try again.');
    });
  }

  // Apply saved mode on load
  if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
  }

  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
  }
