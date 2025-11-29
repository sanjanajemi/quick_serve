<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cancel Orders</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/cancel_order.css" />
</head>
<body>
  <header>
    <h1>Cancel Orders</h1>
    <nav>
      <a href="/quick_serve/staff/dashboard" class="btn">← Dashboard</a>
      <button class="toggle-btn" onclick="document.body.classList.toggle('dark-mode')">Toggle Mode</button>
    </nav>
  </header>

  <main>
    <?php if (empty($orders)): ?>
      <p class="empty-message">No cancellable orders at the moment.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Placed At</th>
            <th>Amount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr id="order-<?= htmlspecialchars((string)$order['order_id']) ?>">
              <td><?= htmlspecialchars((string)$order['order_id']) ?></td>
              <td><?= htmlspecialchars($order['customer_name'] ?? 'Unknown') ?></td>
              <td><?= htmlspecialchars($order['status'] ?? '') ?></td>
              <td><?= htmlspecialchars($order['placed_at'] ?? '') ?></td>
              <td>DKK <?= number_format((float)$order['final_amount'], 2) ?></td>
              <td>
                <button class="btn danger" onclick="cancelOrder(<?= (int)$order['order_id'] ?>)">Cancel</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>

  <footer>
    &copy; <?= date('Y') ?> QuickServe Staff Dashboard
  </footer>

  <script src="\quick_serve\app\views\staff\cancel_order.js"></script>
</body>
</html>