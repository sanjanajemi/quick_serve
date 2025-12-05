<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Details</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/customer_view.css?v=21">
</head>
<body>

  <h2>Customer Details</h2>

  <table class="details">
    <tr><th>Name:</th><td><?= htmlspecialchars($customer['name']) ?></td></tr>
    <tr><th>Email:</th><td><?= htmlspecialchars($customer['email']) ?></td></tr>
    <!-- Removed Account Status row -->
  </table>

  <?php if (!empty($customer['orders'])): ?>
    <h3>Order History</h3>
    <table id="orderHistory" class="display">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Status</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customer['orders'] as $order): ?>
          <tr>
            <td><?= htmlspecialchars($order['order_id']) ?></td>
            <td><?= date('d M Y', strtotime($order['placed_at'])) ?></td>
            <td><?= !empty($order['status']) ? htmlspecialchars($order['status']) : 'Nostatus' ?></td>
            <td><?= htmlspecialchars($order['final_amount']) ?> DKK</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No orders found for this customer.</p>
  <?php endif; ?>

  <a class="back-link" href="/quick_serve/admin/customer/list">← Back to Customer List</a>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#orderHistory').DataTable({
        pageLength: 5,
        lengthChange: false,
        order: [[1, 'desc']],
        language: {
          search: "Search orders:",
          paginate: {
            previous: "Prev",
            next: "Next"
          }
        }
      });
    });
  </script>

  <script src="/quick_serve/assets/js/admin/customer_view.js"></script>
</body>
</html>