<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/order_success.css">
</head>
<body>
  <header>
  <a href="/quick_serve/staff/add-order" class="btn">← Back to Add Order</a>
  <h1>Order Placed Successfully !</h1>
</header>

  <div class="success-box">
    <p>Thank you, <?= htmlspecialchars($customerName ?? '') ?>!</p>

    <?php if (!empty($orderId)): ?>
      <p>Your order number is: 
        <strong>#<?= htmlspecialchars((string)$orderId) ?></strong>
      </p>

      <div class="actions">
        <a href="/quick_serve/staff/receipt?order_id=<?= urlencode((string)$orderId) ?>" class="btn">
          🖨 Print Receipt
        </a>
        <a href="/quick_serve/staff/dashboard" class="btn">← Back to Dashboard</a>
      </div>
    <?php else: ?>
      <p style="color:red;">Order ID not available. Please check logs.</p>
      <div class="actions">
        <a href="/quick_serve/staff/dashboard/add_order_page" class="btn">← Back to Dashboard</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>