function cancelOrder(orderId) {
  if (!confirm(`Are you sure you want to cancel order #${orderId}?`)) {
    return;
  }

  // Show immediate feedback
  showToast(`📧 Sending cancellation email for order #${orderId}...`);

  fetch('/quick_serve/staff/cancel_order', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ order_id: orderId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast(`✅ Order #${orderId} cancelled successfully. Email sent to customer.`);
      // Optionally remove row instantly
      document.getElementById(`order-${orderId}`)?.remove();
      setTimeout(() => location.reload(), 1000);
    } else {
      showToast(`❌ Failed to cancel order: ${data.error || 'Unknown error'}`);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showToast('⚠️ Something went wrong.');
  });
}

// Simple toast popup
function showToast(message) {
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}