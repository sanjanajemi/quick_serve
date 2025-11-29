<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/staff/receipt.css" />
</head>
<body>
  <div class="receipt-card">
    <header>
      <h1>Receipt</h1>
      <p>Order #: <?= htmlspecialchars((string)$order['order_id']) ?></p>
      <p>Customer: <?= htmlspecialchars($order['customer_name']) ?></p>
      <p>Email: <?= htmlspecialchars($order['email']) ?></p>
      <p>Date: <?= htmlspecialchars($order['placed_at']) ?></p>
    </header>

    <main>
      <table>
        <thead>
          <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
        </thead>
        <tbody>
          <?php foreach ($order['items'] as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['item_name']) ?></td>
              <td><?= (int)$item['quantity'] ?></td>
              <td>DKK <?= number_format($item['price'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <p class="total"><strong>Total: DKK <?= number_format($order['final_amount'], 2) ?></strong></p>
    </main>

   <footer>
  <button type="button" class="print-btn" onclick="window.print()">
    🖨 Print
  </button>
  <button type="button" class="back-btn"
          onclick="window.location.href='/quick_serve/staff/add-order'">
    ← Back to Ordering
  </button>
</footer>
  </div>
</body>
</html>