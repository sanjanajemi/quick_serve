<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?= $order['order_id'] ?> Details</title>
  <link rel="stylesheet" href="\quick_serve\assets\css\admin\order_view.css"/>
</head>
<body>

    <?php if (!empty($_SESSION['flash'])): ?>
  <div style="background:#27ae60; color:white; padding:10px; margin-bottom:10px;">
    <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
  </div>
<?php endif; ?>



<h2>Order #<?= $order['order_id'] ?></h2>
<p><strong>Customer:</strong> <?= $order['customer_name'] ?></p>
<p><strong>Status:</strong> <?= $order['status'] ?></p>
<p><strong>Placed at:</strong> <?= $order['placed_at'] ?></p>
<p><strong>Final Amount:</strong> $<?= $order['final_amount'] ?></p>
<p><strong>Comments:</strong> <?= $order['comments'] ?></p>

<h3>Items Ordered:</h3>
<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>Item</th>
    <th>Quantity</th>
    <th>Price</th>
  </tr>
  <?php foreach ($order['items'] as $item): ?>
    <tr>
      <td><?= $item['item_name'] ?></td>
      <td><?= $item['quantity'] ?></td>
      <td>$<?= $item['price'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<div class="actions">
  <!-- Status Update Form -->
  <form action="/quick_serve/admin/order/update-status" method="post">
    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>" />
    <label for="status">Update Status:</label>
    <select name="status" id="status">
      <option value="Received" <?= $order['status'] === 'Received' ? 'selected' : '' ?>>Received</option>
      <option value="Preparing" <?= $order['status'] === 'Preparing' ? 'selected' : '' ?>>Preparing</option>
      <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
      <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>
    <button type="submit">Update</button>
  </form>

  <!-- Print Button -->
  <button onclick="window.print()" style="margin-top: 1em; background:#00bcd4; color:white; padding:10px 20px; border:none; border-radius:6px;">
   Print Invoice
</button>
<a href="/quick_serve/admin/order/list" class="back">← Back to Order List</a>

 <script src="\quick_serve\assets\js\admin\staff_add.js"></script>
</body>
</html>