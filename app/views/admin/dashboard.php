<?php
// Prepare recent orders and status counts
$recentOrders = $recentOrders ?? [];
$statusCounts = [
  'Received' => 0,
  'Preparing' => 0,
  'Ready' => 0,
  'Cancelled' => 0,
  'No Status' => 0
];

foreach ($recentOrders as $order) {
  $status = strtolower(trim($order['status'] ?? ''));
  $normalized = ucfirst($status);
  if (isset($statusCounts[$normalized])) {
    $statusCounts[$normalized]++;
  } else {
    $statusCounts['No Status']++;
  }
}

$hasStatusData = array_sum($statusCounts) > 0;

// Calculate today's revenue
$today = date('Y-m-d');
$revenueToday = 0;
if (!empty($weeklySummary)) {
  foreach ($weeklySummary as $entry) {
    if ($entry['summary_date'] === $today) {
      $revenueToday = (float) ($entry['revenue'] ?? 0);
      break;
    }
  }
}

// Calculate 7-day total revenue
$totalRevenue = !empty($weeklySummary)
  ? array_sum(array_map(fn($d) => $d['revenue'] ?? 0, $weeklySummary))
  : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QuickServe Admin Dashboard</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/dashboard.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

  <aside class="sidebar">
    <h2>QuickServe</h2>
    <ul>
      <li class="active"><a href="/quick_serve/admin/dashboard">Dashboard</a></li>
      <li><a href="/quick_serve/admin/profile">Admin Profile</a></li>
      <li><a href="/quick_serve/admin/staff/list">Manage Staff</a></li>
      <li><a href="/quick_serve/admin/menu">Menu Management</a></li>
      <li><a href="/quick_serve/admin/order/list">Order List</a></li>
      <li><a href="/quick_serve/admin/customer/list">Customers</a></li>
      <li><a href="/quick_serve/admin/customer/detail">Customer Detail</a></li>
      <li><a href="/quick_serve/admin/order-analytics">Order Analytics</a></li>
      <li><a href="/quick_serve/admin/logout">Logout</a></li>
    </ul>
  </aside>

  <main class="main-content">
    <header class="topbar">
      <div class="top-right">
        <a href="/quick_serve/admin/logout">Back to Home</a>
      </div>
    </header>

    <section class="metrics">
      <div class="card">Total Orders Today: <strong><?= $metrics['total_orders'] ?? 0 ?></strong></div>
      <div class="card">Cancelled Orders: <strong><?= $metrics['cancelled_orders'] ?? 0 ?></strong></div>
      <div class="card">Revenue Today: <strong>DKK <?= number_format($revenueToday, 2) ?></strong></div>
      <div class="card">
        7-Day Total Revenue:
        <strong>DKK <?= number_format($totalRevenue, 2) ?></strong>
      </div>
    </section>

    <section class="charts">
      <div class="chart-card">
        <h3>Sales Performance</h3>
        <?php if (empty($stats)): ?>
          <p style="color: #f0f0f0;">No sales data available for the last 7 days.</p>
        <?php endif; ?>
        <canvas id="salesChart"></canvas>
      </div>

      <div class="chart-card">
        <h3>Order Status Breakdown</h3>
        <?php if ($hasStatusData): ?>
          <canvas id="statusChart"></canvas>
        <?php else: ?>
          <p style="color: #f0f0f0;">No order status data available for today.</p>
        <?php endif; ?>
      </div>
    </section>

    <section class="orders">
      <h3>Recent Orders</h3>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($recentOrders)): ?>
            <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
              <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
                <td class="status <?= strtolower($order['status'] ?? '') ?>">
                  <?= htmlspecialchars($order['status'] ?? '') ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" style="text-align:center;">No recent orders found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <p style="text-align:right; margin-top: 10px;">
        <a href="/quick_serve/admin/order/list" style="color:#00bcd4;">View All Orders →</a>
      </p>
    </section>
  </main>

  <script>
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($stats, 'summary_date')) ?>,
        datasets: [{
          label: 'Sales (DKK)',
          data: <?= json_encode(array_map('floatval', array_column($stats, 'revenue'))) ?>,
          borderColor: '#00bcd4',
          backgroundColor: 'transparent',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: '#f0f0f0'
            },
            grid: {
              color: '#444'
            }
          },
          x: {
            ticks: {
              color: '#f0f0f0'
            },
            grid: {
              color: '#444'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: '#f0f0f0'
            }
          }
        }
      }
    });

    <?php if ($hasStatusData): ?>
      const statusCtx = document.getElementById('statusChart').getContext('2d');
      new Chart(statusCtx, {
        type: 'pie',
        data: {
          labels: <?= json_encode(array_keys($statusCounts)) ?>,
          datasets: [{
            data: <?= json_encode(array_values($statusCounts)) ?>,
            backgroundColor: [
              '#3498db', '#f39c12', '#9b59b6', '#1abc9c',
              '#2ecc71', '#e74c3c', '#c0392b', '#95a5a6'
            ]
          }]
        },
        options: {
          plugins: {
            legend: {
              labels: {
                color: '#f0f0f0',
                font: {
                  size: 14
                }
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.formattedValue + ' orders';
                }
              }
            }
          }
        }
      });
    <?php endif; ?>
  </script>

</body>

</html>