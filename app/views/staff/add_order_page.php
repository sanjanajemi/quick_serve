<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Order</title>
  <!-- External CSS -->
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/add_order_page.css">
</head>

<body>

  <header>
    <h1>Order for Customer</h1>
    <button type="button" class="back-btn" onclick="window.location.href='/quick_serve/staff/dashboard'">
      ← Back to Dashboard
    </button>
  </header>

  <div class="search-bar">
    <input type="text" id="menuSearch" placeholder="Search menu items...">
  </div>

  <div class="order-container">
    <!-- Left: Menu -->
    <div class="menu-column">
      <h2>Menu</h2>
      <?php foreach ($menuItems as $item): ?>
        <div class="menu-item">
          <img src="<?= htmlspecialchars($item['image_url']) ?>"
            alt="<?= htmlspecialchars($item['name']) ?>"
            class="menu-img">
          <div class="menu-info">
            <strong><?= htmlspecialchars($item['name']) ?></strong>
            <p><?= htmlspecialchars($item['description']) ?></p>
            <span>DKK <?= number_format($item['price'], 2) ?></span>
            <button type="button"
              onclick="addToCart(<?= $item['menu_item_id'] ?>,
                                     '<?= htmlspecialchars($item['name']) ?>',
                                     <?= $item['price'] ?>)">
              Add
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Right: Cart + Customer Details -->
    <div class="cart-column">
      <h2>Cart</h2>
      <form action="/quick_serve/staff/place-order" method="POST" id="orderForm">
        <div id="cartItems"></div>

        <!-- Customer Lookup -->
        <h3>Find Existing Customer</h3>
        <label>Search by Email</label>
        <div class="customer-search">
          <input type="email" id="searchCustomerEmail" placeholder="Enter customer email">
          <button type="button" onclick="searchCustomer()">Search</button>
        </div>
        <small id="customerSearchError" style="color:red; display:none;">
          No customer found with that email.
        </small>

        <!-- Customer Details -->
        <h3>Customer Details</h3>
        <label>Customer Name</label>
        <input type="text" name="customer_name" required>

        <label>Customer Email</label>
        <input type="email" name="customer_email" required>

        <!-- Order Comments -->
        <label>Comments</label>
        <input type="text" name="comments" id="comments" maxlength="200">
        <small id="commentError" style="color:red; display:none;">
          Invalid characters detected. Only letters, numbers, spaces, and basic punctuation are allowed.
        </small>

        <!-- Final Amount -->
        <label>Total Amount</label>
        <input type="number" step="0.01" name="final_amount" id="finalAmount" readonly>

        <button type="submit">Place Order</button>
      </form>
    </div>
  </div>

  <script src="\quick_serve\app\views\staff\add_order_page.js"></script>

</body>

</html>