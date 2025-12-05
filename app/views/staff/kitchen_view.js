
    /**Toggle dark mode on or off
     * Add or removes dark mode css class from the body element
     */ 
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
    }

    // Sound toggle
  const soundToggleBtn = document.getElementById("soundToggleBtn");
  // Load saved preference or default to true
  let soundEnabled = localStorage.getItem("soundEnabled") === "false" ? false : true;
  // Set initial button label
  updateSoundButton();




  /**
   * Toggle sound alerts on or off
   * Saves thepreference in local storage and  updates the button label
   */
  function toggleSound() {
    soundEnabled = !soundEnabled;
    localStorage.setItem("soundEnabled", soundEnabled);
    updateSoundButton();
  }


  /**
   * Update the sound toggle button label based on current state
   */
  function updateSoundButton() {
    soundToggleBtn.textContent = soundEnabled
      ? "🔔 Sound Alerts ON"
      : "🔕 Sound Alerts OFF";
  }


  /**
   * Play an alert sound if enabled
   * Resets playback position before playing.
   */
  function playAlertSound() {
    if (!soundEnabled) return;
    const sound = document.getElementById("alertSound");
    if (sound) {
      sound.currentTime = 0;
      sound.play().catch(err => console.warn("Sound play failed:", err));
    }

}



    /**
     * Search orders by query string
     * Matches against order ID and order card text content.
    */ 
    function searchOrders() {
      const query = document.getElementById('searchBar').value.toLowerCase();
      const cards = document.querySelectorAll('.order-card');

      cards.forEach(card => {
        const orderId = card.dataset.orderId.toLowerCase();
        const itemsText = card.innerText.toLowerCase();
        card.style.display = (orderId.includes(query) || itemsText.includes(query)) ? 'block' : 'none';
      });
    }



    /**
     * Filter order by status
     * Shows only card matching the selected status or all if 'all' is selected
    */ 
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


    /** 
     * Sort orders by waiting time in descending order
     * Reorders DOM elements inside the order list container
    */
   
    function sortByWaitingTime() {
      const container = document.querySelector('.order-list');
      const cards = Array.from(container.querySelectorAll('.order-card'));

      cards.sort((a, b) => parseInt(b.dataset.waiting) - parseInt(a.dataset.waiting));
      cards.forEach(card => container.appendChild(card));
    }

    /**
     * Update order status
     * Sends POST request to the server and updates the UI with feedback
     * @param {number} orderId - Identifier for the order
     * @param {string} newStatus - New status to assign to order.
     * */
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

    /**
     * Send  an email related to an order
     * Plays sound and shows toast on success
     * @param {number} orderId - Identifier for the order
     *  */ 
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

   
  /**
   * Poll the server for new orders every 30 seconds
   * Plays sound and reloads page if new orders are found
   */

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
    //Poll every 30 seconds
    setInterval(checkForNewOrders, 30000);



    /**
     * Display a temporary toast notification message
     * Automatically removes the toast after 3 seconds
     * @param {string} message - The message to display in the toast
     * */
    function showToast(message) {
      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.innerText = message;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }


    /**
     * Remove order card from the kitchen view
     * Sends POST request to server to clear the order
     * @param {HTMLElement} button - The button element at to right of the order card
     *  */ 

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