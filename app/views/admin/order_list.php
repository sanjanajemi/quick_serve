<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order List</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/admin/order_list.css?v=<?= time() ?>" />
</head>

<body>
    <?php if (!empty($_SESSION['flash'])): ?>
        <div style="background:#27ae60; color:white; padding:10px; margin-bottom:10px;">
            <?= $_SESSION['flash'];
            unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <h2>Order List</h2>
    <div class="top-bar">
        <a href="/quick_serve/admin/dashboard" class="dashboard-button">← Back to Dashboard</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total (DKK)</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td>
                        <?= $order['status'] === null || $order['status'] === ''
                            ? 'Not Set'
                            : htmlspecialchars($order['status']) ?>
                    </td>
                    <td><?= number_format($order['final_amount'], 2) ?></td>
                    <td><?= $order['placed_at'] ?></td>
                    <td class="actions">
                        <a href="/quick_serve/admin/order/view?id=<?= $order['order_id'] ?>">View</a>
                        <form method="post" action="/quick_serve/admin/order/delete" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <button type="submit" onclick="return confirm('Delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="\quick_serve\assets\js\admin\staff_add.js"></script>
</body>

</html>