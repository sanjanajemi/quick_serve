  let cart = {};

    // Add item to cart
    function addToCart(id, name, price) {
      if (!cart[id]) cart[id] = {
        name,
        price,
        qty: 0
      };
      cart[id].qty++;
      renderCart();
    }

    // Remove one unit of an item
    function removeFromCart(id) {
      if (cart[id]) {
        cart[id].qty--;
        if (cart[id].qty <= 0) {
          delete cart[id];
        }
        renderCart();
      }
    }

    // Clear the entire cart
    function clearCart() {
      cart = {};
      renderCart();
    }

    // Render cart items
    function renderCart() {
      const cartDiv = document.getElementById('cartItems');
      cartDiv.innerHTML = '';
      let total = 0;

      for (const id in cart) {
        const item = cart[id];
        total += item.price * item.qty;
        cartDiv.innerHTML += `
      <div class="cart-item">
        <span>${item.name} x ${item.qty} (DKK ${(item.price * item.qty).toFixed(2)})</span>
        <button type="button" class="remove-btn" onclick="removeFromCart(${id})">Remove</button>
        <input type="hidden" name="items[${id}]" value="${item.qty}">
      </div>`;
      }

      if (Object.keys(cart).length > 0) {
        cartDiv.innerHTML += `
      <button type="button" class="clear-btn" onclick="clearCart()">Clear Cart</button>
    `;
      }

      document.getElementById('finalAmount').value = total.toFixed(2);
    }

    // Comment validation
    document.getElementById('orderForm').addEventListener('submit', function(e) {
      const commentField = document.getElementById('comments');
      const errorMsg = document.getElementById('commentError');
      const commentValue = commentField.value.trim();

      // Allow letters, numbers, spaces, and . , ! ? - '
      const validPattern = /^[a-zA-Z0-9\s.,!?'-]*$/;

      if (commentValue.length > 0 && !validPattern.test(commentValue)) {
        e.preventDefault(); // stop form submission
        errorMsg.style.display = 'block';
        commentField.focus();
      } else {
        errorMsg.style.display = 'none';
      }
    });
    // Search filter
    document.getElementById('menuSearch').addEventListener('keyup', function() {
      const query = this.value.toLowerCase();
      document.querySelectorAll('.menu-item').forEach(item => {
        const name = item.querySelector('strong').textContent.toLowerCase();
        const description = item.querySelector('p').textContent.toLowerCase();
        if (name.includes(query) || description.includes(query)) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    });

    function searchCustomer() {
  const email = document.getElementById('searchCustomerEmail').value.trim();
  const errorMsg = document.getElementById('customerSearchError');

  if (!email) return;

  fetch(`/quick_serve/staff/find-customer?email=${encodeURIComponent(email)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        errorMsg.style.display = 'none';
        const customer = data.customer;

        // Fill form fields
        document.querySelector('input[name="customer_name"]').value = customer.name || '';
        document.querySelector('input[name="customer_email"]').value = customer.email || '';
        if (customer.phone) {
          let phoneInput = document.querySelector('input[name="customer_phone"]');
          if (phoneInput) phoneInput.value = customer.phone;
        }
        if (customer.address) {
          let addressInput = document.querySelector('input[name="customer_address"]');
          if (addressInput) addressInput.value = customer.address;
        }
      } else {
        errorMsg.style.display = 'block';
      }
    })
    .catch(() => {
      errorMsg.style.display = 'block';
    });
}