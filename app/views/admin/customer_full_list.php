<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Customer Details</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/customer_full_list.css?v=23"/>
  <style>
    .no-results { color: red; font-weight: bold; }
  </style>
</head>
<body>

  <h2>All Customer Details</h2>

  <div class="top-bar">
    <input type="text" id="customerSearch" class="search-input" placeholder="Search by name or email...">
    <a href="/quick_serve/admin/dashboard" class="dashboard-button">Back to Dashboard</a>
  </div>

  <p id="noResults" class="no-results" style="display:none;">No matching customers found.</p>

  <?php foreach ($customers as $customer): ?>
    <div class="customer-card"
         data-name="<?= strtolower(trim($customer['name'] ?? '')) ?>"
         data-email="<?= strtolower(trim($customer['email'] ?? '')) ?>">

      <h3><?= htmlspecialchars($customer['name'] ?? '') ?></h3>
      <p><strong>Email:</strong> <?= htmlspecialchars($customer['email'] ?? '') ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($customer['account_status'] ?? '') ?></p>

      <div class="actions">
        <?php if (($customer['account_status'] ?? '') === 'active'): ?>
          <a href="/quick_serve/admin/customer/status?id=<?= urlencode($customer['customer_id'] ?? '') ?>&status=inactive"
             onclick="return confirm('Deactivate this customer?');">Deactivate</a>
        <?php else: ?>
          <a href="/quick_serve/admin/customer/status?id=<?= urlencode($customer['customer_id'] ?? '') ?>&status=active"
             onclick="return confirm('Activate this customer?');">Activate</a>
        <?php endif; ?>

        <a href="/quick_serve/admin/customer/delete?id=<?= urlencode($customer['customer_id'] ?? '') ?>"
           class="delete"
           onclick="return confirm('Delete this customer permanently?');">Delete</a>
      </div>

      <?php if (!empty($customer['orders'])): ?>
        <h4>Order History</h4>
        <table>
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
                <td><?= htmlspecialchars($order['order_id'] ?? '') ?></td>
                <td><?= !empty($order['placed_at']) ? date('d M Y', strtotime($order['placed_at'])) : '' ?></td>
 <td><?= !empty($order['status']) ? htmlspecialchars($order['status']) : 'Nostatus' ?></td>
                <td><?= htmlspecialchars($order['final_amount'] ?? '0') ?> DKK</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No orders found.</p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <script>
    document.getElementById('customerSearch').addEventListener('input', function () {
      const query = this.value.toLowerCase().trim();
      const cards = document.querySelectorAll('.customer-card');
      let anyMatch = false;

      cards.forEach(card => {
        const name = (card.getAttribute('data-name') || '').toLowerCase();
        const email = (card.getAttribute('data-email') || '').toLowerCase();
        const match = name.includes(query) || email.includes(query);

        card.style.display = match ? 'block' : 'none';
        if (match) anyMatch = true;
      });

      document.getElementById('noResults').style.display = anyMatch ? 'none' : 'block';
    });
  </script>

  <script src="/quick_serve/assets/js/admin/customer_full_list.js"></script>
</body>
</html>