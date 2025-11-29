
    // Toggle dark mode
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
    }

    // Sound toggle
  const soundToggleBtn = document.getElementById("soundToggleBtn");

  // Load saved preference or default to true
  let soundEnabled = localStorage.getItem("soundEnabled") === "false" ? false : true;

  // Set initial button label
  updateSoundButton();

  function toggleSound() {
    soundEnabled = !soundEnabled;
    localStorage.setItem("soundEnabled", soundEnabled);
    updateSoundButton();
  }

  function updateSoundButton() {
    soundToggleBtn.textContent = soundEnabled
      ? "🔔 Sound Alerts ON"
      : "🔕 Sound Alerts OFF";
  }

  function playAlertSound() {
    if (!soundEnabled) return;
    const sound = document.getElementById("alertSound");
    if (sound) {
      sound.currentTime = 0;
      sound.play().catch(err => console.warn("Sound play failed:", err));
    }

}
    //  Search orders
    function searchOrders() {
      const query = document.getElementById('searchBar').value.toLowerCase();
      const cards = document.querySelectorAll('.order-card');

      cards.forEach(card => {
        const orderId = card.dataset.orderId.toLowerCase();
        const itemsText = card.innerText.toLowerCase();
        card.style.display = (orderId.includes(query) || itemsText.includes(query)) ? 'block' : 'none';
      });
    }

    //  Filter orders by status
 function filterOrders() {
  const status = document.getElementById('statusFilter').value;
  const cards = document.querySelectorAll('.order-card');

  cards.forEach(card => {
    const classList = Array.from(card.classList);
    const statusClass = classList.find(cls =>
      ['received', 'preparing', 'ready', 'nostatus'].includes(cls)
    );

    const match = (status === 'all') || (statusClass === status.toLowerCase());
    card.style.display = match ? 'block' : 'none';
  });
}
    //  Sort orders by waiting time
    function sortByWaitingTime() {
      const container = document.querySelector('.order-list');
      const cards = Array.from(container.querySelectorAll('.order-card'));

      cards.sort((a, b) => parseInt(b.dataset.waiting) - parseInt(a.dataset.waiting));
      cards.forEach(card => container.appendChild(card));
    }

    //  Update order status
    function updateStatus(orderId, newStatus) {
      fetch('/quick_serve/staff/update_status', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            order_id: orderId,
            status: newStatus
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const sound = document.getElementById('statusSound');
            if (soundEnabled && sound) {
              sound.play().catch(err => console.warn('Sound blocked:', err));
            }
            showToast(`Order #${orderId} marked as ${newStatus}`);
            setTimeout(() => location.reload(), 500); 
          } else {
            alert('Failed to update order status.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Something went wrong.');
        });
    }

    // Send mail
    function sendEmail(orderId) {
     fetch('/quick_serve/staff/send_email', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            order_id: orderId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const sound = document.getElementById('emailSound');
            if (soundEnabled && sound) {
              sound.play().catch(err => console.warn('Sound blocked:', err));
            }
            showToast(`Email sent for Order #${orderId}`);
          } else {
            alert('Failed to send email: ' + (data.error || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Something went wrong.');
        });
    }

    //  Poll for new orders---fix it tomorrowmorning
  

    function checkForNewOrders() {
      fetch('/quick_serve/staff/kitchen_poll')
        .then(response => response.json())
        .then(data => {
          const currentCount = data.order_count;
          if (currentCount > lastOrderCount) {
            const sound = document.getElementById('newOrderSound');
            if (soundEnabled && sound) {
              sound.play().catch(err => console.warn('Sound blocked:', err));
            }
            showToast('🔔 New order received!');
            location.reload();
          }
          lastOrderCount = currentCount;
        })
        .catch(error => console.error('Polling error:', error));
    }

    setInterval(checkForNewOrders, 30000);

    //  Toast notification
    function showToast(message) {
      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.innerText = message;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }


    //NEW
  function removeOrderCard(button) {
  const card = button.closest('.order-card');
  const orderId = card?.dataset.orderId;

  if (!orderId) return;

  fetch('/quick_serve/staff/clear-order', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ order_id: orderId })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) card.remove();
    else alert('Failed to clear order.');
  });

}