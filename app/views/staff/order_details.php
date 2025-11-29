<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Details</title>
  <link rel="stylesheet" href="\quick_serve\assets\css\staff\order_details.css">
</head>
<body>
  <header>
    <h1>Order #<?= htmlspecialchars($order['order_id']) ?> Details</h1>
    <nav>
      <a href="/quick_serve/staff/order-history" class="btn">← Back to History</a>
      <button onclick="toggleDarkMode()" class="toggle-btn">Toggle Dark Mode</button>
      <a href="/quick_serve/staff/logout" class="btn">Logout</a>
    </nav>
  </header>

  <main>
    <section class="order-summary">
      <h2>Order Summary</h2>
      <ul>
        <li><strong>Status:</strong> <?= htmlspecialchars($order['status'] ?? 'Not Set') ?></li>
        <li><strong>Placed At:</strong> <?= htmlspecialchars($order['placed_at']) ?></li>
        <li><strong>Final Amount:</strong> DKK <?= number_format($order['final_amount'], 2) ?></li>
        <li><strong>Comments:</strong> <?= htmlspecialchars($order['comments'] ?? 'None') ?></li>
      </ul>
    </section>

    <section class="customer-info">
      <h2>Customer Info</h2>
      <ul>
        <li><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></li>
      </ul>
    </section>

    <section class="item-list">
      <h2>Ordered Items</h2>
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Unit Price</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['items'] as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['item_name']) ?></td>
              <td><?= htmlspecialchars($item['quantity']) ?></td>
              <td>DKK <?= number_format($item['price'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> Quick Serve</p>
  </footer>

  <script>
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }

    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
      const isDark = document.body.classList.contains('dark-mode');
      localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
    }
  </script>

  <script src="\quick_serve\assets\js\staff\order_details.js"></script>
</body>
</html>