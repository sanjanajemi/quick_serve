<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/staff/order_history.css" />
</head>

<body>
    <header>
        <h1>Order History</h1>
        <nav>
            <a href="/quick_serve/staff/dashboard" class="btn">← Back to Dashboard</a>
            <button onclick="toggleDarkMode()" class="toggle-btn">Toggle Dark Mode</button>
            <a href="/quick_serve/staff/logout" class="btn">Logout</a>
        </nav>
    </header>
    <?php if (!empty($_GET['restored'])): ?>
        <div id="toast" class="toast">✅ Order restored to dashboard</div>
        <script>
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        </script>
    <?php endif; ?>
    <main>
        <?php if (empty($orders)): ?>
            <p>No completed or cancelled orders found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Placed At</th>
                        <th>Final Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td data-label="Order #"><?= htmlspecialchars($order['order_id']) ?></td>
                            <td data-label="Customer"><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td data-label="Status"><?= htmlspecialchars($order['status'] ?? 'Not Set') ?></td>
                            <td data-label="Placed At"><?= htmlspecialchars($order['placed_at']) ?></td>
                            <td data-label="Final Amount">DKK <?= number_format($order['final_amount'], 2) ?></td>
                            <td data-label="Actions">
                                <div class="action-cell">
                                    <div class="order-actions" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                       <a href="/quick_serve/staff/order-details?order_id=<?= $order['order_id'] ?>" class="btn view-details">View Details</a>

                                        <?php if (!empty($order['is_cleared'])): ?>
                                            <button class="btn restore" onclick="restoreOrder(<?= $order['order_id'] ?>)">Restore in Dashboard</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Quick Serve</p>
    </footer>
    <script>
        function restoreOrder(orderId) {
            fetch('/quick_serve/staff/restore-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `order_id=${encodeURIComponent(orderId)}`
                })
                .then(() => {
                    window.location.href = '/quick_serve/staff/order-history?restored=1';
                })
                .catch(() => {
                    alert('Failed to restore order.');
                });
        }

        // Apply saved mode on load
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        }
    </script>
     <script src="\quick_serve\assets\js\staff\order_history.js"></script>
</body>

</html>