<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;

// Protect page
if (!isset($_SESSION['customer_id'])) {
    header("Location: /quick_serve/customer/login");
    exit;
}

$customerId = $_SESSION['customer_id'];

$pdo = Database::connect();

// Fetch all orders
$stmt = $pdo->prepare("
    SELECT o.order_id, o.placed_at, o.final_amount, o.status
    FROM `order` o
    WHERE o.customer_id = ?
    ORDER BY o.placed_at DESC
");
$stmt->execute([$customerId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Order History</title>

    <!-- Reuse your dashboard CSS so background works -->
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/order_history.css">

</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>
    <!-- same hero background as dashboard -->
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="order-history-container">
        <h1 class="order-history-title">📜 Your Order History</h1>

        <?php if (empty($orders)): ?>
            <p class="no-orders-text">You don’t have any past orders yet.</p>
        <?php else: ?>
            <div class="order-list">
                <?php foreach ($orders as $order): ?>
                    <?php
                        $statusRaw = (string)$order['status'];
                        $status    = strtolower(trim($statusRaw));

                        $statusClass = 'status-pending';
                        if ($status === 'preparing' || $status === 'in progress') {
                            $statusClass = 'status-preparing';
                        } elseif ($status === 'ready' || $status === 'ready for pickup') {
                            $statusClass = 'status-ready';
                        } elseif ($status === 'completed') {
                            $statusClass = 'status-completed';
                        } elseif ($status === 'cancelled' || $status === 'canceled') {
                            $statusClass = 'status-cancelled';
                        }
                    ?>
                    <div class="order-card">
                        <div class="order-card-header">
                            <span>Order #<?= (int)$order['order_id'] ?></span>
                            <span class="order-status <?= $statusClass ?>">
                                <?= htmlspecialchars($statusRaw) ?>
                            </span>
                        </div>

                        <div class="order-card-meta">
                            <span><?= date('M d, Y H:i', strtotime($order['placed_at'])) ?></span>
                            <span><strong>Total:</strong> <?= number_format($order['final_amount'], 2) ?> DKK</span>
                        </div>

                        <?php if (!empty($order['items'])): ?>
                            <ul class="order-items">
                                <?php foreach ($order['items'] as $item): ?>
                                    <li><?= htmlspecialchars($item['name']) ?> × <?= (int)$item['quantity'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <a href="/quick_serve/app/views/customer/receipt.php?order_id=<?= (int)$order['order_id'] ?>"
                           class="receipt-link">
                            📄 View Receipt
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
