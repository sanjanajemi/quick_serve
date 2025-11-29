<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Kitchen Dashboard</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/kitchen_view.css" />
</head>

<body>
  <header class="main-header">
 
    <div class="header-left">
      <h1>Kitchen Dashboard</h1>
    </div>
    <div class="header-right">
      <a href="/quick_serve/staff/dashboard" class="btn">← Dashboard</a>
      <button onclick="toggleDarkMode()" class="btn">Toggle Dark Mode</button>
      <button id="soundToggleBtn" class="btn" onclick="toggleSound()">🔔 Sound Alerts ON</button>
      <a href="/quick_serve/staff/logout" class="btn">Logout</a>
    </div>
  </header>

  <main>
    <div class="controls">
      <input type="text" id="searchBar" placeholder="Search Order ID or Item..." oninput="searchOrders()" />
      <select id="statusFilter" onchange="filterOrders()">
        <option value="all">All</option>
        <option value="Received">Received</option>
        <option value="Preparing">Preparing</option>
        <option value="Ready">Ready</option>
        <option value="nostatus">Not Set</option>
      </select>
      <button onclick="location.reload()" class="btn">♻️ Refresh Orders</button>
      <button onclick="sortByWaitingTime()" class="btn">⏱ Sort by Waiting Time</button>
    </div>

    <?php if (empty($orders)): ?>
      <p class="empty-message">No active orders at the moment.</p>
    <?php else: ?>
      <div class="order-list">
        <?php foreach ($orders as $order): ?>
          <?php
          $statusClass = strtolower($order['status'] ?? 'nostatus');
          $cardClass = $statusClass;
          if (!empty($order['comments'])) {
            $cardClass .= ' has-comment';
          }
          ?>
          <div class="order-card <?= $cardClass ?>" data-order-id="<?= $order['order_id'] ?>" data-waiting="<?= $order['waiting_time'] ?>">
            <div class="clear-btn-wrapper">
              <button class="clear-btn" onclick="removeOrderCard(this)"> ✖ </button>
            </div>
            <h2>Order #<?= htmlspecialchars($order['order_id']) ?></h2>
            <p><strong>Placed:</strong> <?= htmlspecialchars($order['placed_at']) ?> (<span class="elapsed-time" data-time="<?= htmlspecialchars($order['placed_at']) ?>"></span> ago)</p>
            <ul>
              <?php foreach ($order['items'] as $item): ?>
                <li><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></li>
              <?php endforeach; ?>
            </ul>
            <?php if (!empty($order['comments'])): ?>
              <p class="order-comment"><?= htmlspecialchars($order['comments']) ?></p>
            <?php endif; ?>
            <div class="customer-details">
              <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?> (<?= htmlspecialchars($order['email']) ?>)</p>
            </div>

            <div class="status-box <?= $statusClass ?>">
              Status: <?= $order['status'] ?? '_____' ?>
            </div>

            <div class="order-actions">
              <!-- Received Button -->
              <button
                class="<?= empty($order['status']) ? 'active' : 'disabled' ?>"
                <?= empty($order['status']) ? '' : 'disabled' ?>
                onclick="updateStatus(<?= $order['order_id'] ?>, 'Received')">
                🟡 Received
              </button>

              <!-- Preparing Button -->
              <button
                class="<?= $order['status'] === 'Received' ? 'active' : 'disabled' ?>"
                <?= $order['status'] === 'Received' ? '' : 'disabled' ?>
                onclick="updateStatus(<?= $order['order_id'] ?>, 'Preparing')">
                🟠 Preparing
              </button>

              <!-- Ready Button -->
              <button
                class="<?= $order['status'] === 'Preparing' ? 'active' : 'disabled' ?>"
                <?= $order['status'] === 'Preparing' ? '' : 'disabled' ?>
                onclick="updateStatus(<?= $order['order_id'] ?>, 'Ready')">
                🟢 Ready
              </button>

              <!-- Send Email Button -->
              <?php if ($order['status'] === 'Ready'): ?>
                <button class="active" onclick="sendEmail(<?= $order['order_id'] ?>)">📧 Send Email</button>
              <?php else: ?>
                <button class="disabled" disabled>📧 Send Email</button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <audio id="statusSound" src="/quick_serve/assets/sounds/status_change.mp3"></audio>
    <audio id="emailSound" src="/quick_serve/assets/sounds/email_sent.mp3"></audio>
    <audio id="newOrderSound" src="/quick_serve/assets/sounds/new_order.mp3"></audio>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> Quick Serve Kitchen | Last updated: <?= date('H:i:s') ?></p>
  </footer>

  <script>
    const lastOrderCount = <?= count($orders) ?>;
  </script>
  <script src="/quick_serve/assets/js/staff/kitchen_view.js"></script>
    <script src="\quick_serve\app\views\staff\kitchen_view.js"></script>
</body>

</html>