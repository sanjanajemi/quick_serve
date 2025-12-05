<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Order Analytics Dashboard</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/analytics.css?v=29" />
</head>

<body>
  <div class="top-bar">
    <h2>Order Analytics Dashboard</h2>
    <a href="/quick_serve/admin/dashboard" class="dashboard-button">← Back to Dashboard</a>
  </div>

  <?php
  // Initialize counters for all known statuses
  $statusCounts = [
    'nostatus' => 0,
    'received' => 0,
    'preparing' => 0,
    'ready' => 0,
   

  ];

  $totalOrdersToday = count($ordersToday ?? []);

  foreach ($ordersToday ?? [] as $order) {
    $status = strtolower(trim($order['status'] ?? 'nostatus'));
    if (array_key_exists($status, $statusCounts)) {
      $statusCounts[$status]++;
    } else {
      $statusCounts['nostatus']++;
    }
  }

  // Calculate total revenue from weekly summary
  $totalRevenue = array_sum(array_map(function ($day) {
    return $day['revenue'] ?? 0;
  }, $weeklySummary ?? []));
  ?>

  <div class="metrics-box">
    <div class="box total">
      <h3>Total Orders Today</h3>
      <p><strong><?= $totalOrdersToday ?></strong></p>
    </div>

    <?php foreach ($statusCounts as $status => $count): ?>
      <div class="box <?= str_replace(' ', '-', $status) ?>">
        <h3><?= ucfirst($status) ?> Orders</h3>
        <p><strong><?= $count ?></strong></p>
      </div>
    <?php endforeach; ?>

    <div class="box revenue">
      <h3>Total Revenue (Last 7 Days)</h3>
      <p><strong><?= number_format($totalRevenue, 2) ?> DKK</strong></p>
    </div>
  </div>

 <h3>Last 7 Days Summary</h3>

<?php if (!empty($weeklySummary)): ?>
  <table class="summery-table">

    <thead>
      <tr>
        <th>Date</th>
        <th>Ready Orders</th>
        <th>Revenue (DKK)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($weeklySummary as $day): ?>
        <tr>
          <td><?= date('d M Y', strtotime($day['summary_date'])) ?></td>
          <td><?= $day['ready_orders'] ?? 0 ?></td>
          <td><?= number_format($day['revenue'] ?? 0, 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th scope="row">Weekly Total</th>
        <td><?= array_sum(array_column($weeklySummary, 'ready_orders')) ?></td>
        <td><?= number_format(array_sum(array_column($weeklySummary, 'revenue')), 2) ?></td>
      </tr>
    </tfoot>
  </table>
<?php else: ?>
  <p class="empty-message" aria-live="polite">
    No ready orders found in the last 7 days.
  </p>
<?php endif; ?>
</body>

</html>