<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../core/Database.php';

use App\Core\Database;

$pdo = Database::connect();

// Validate order_id
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    echo "Invalid receipt.";
    exit;
}

// Fetch order
$stmt = $pdo->prepare("SELECT * FROM `order` WHERE order_id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch items
$stmtItems = $pdo->prepare("
    SELECT oi.quantity, oi.unit_price, oi.total_price, mi.name
    FROM order_item oi
    JOIN menu_item mi ON oi.menu_item_id = mi.menu_item_id
    WHERE oi.order_id = ?
");
$stmtItems->execute([$orderId]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Receipt · Brock Café</title>

    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/receipt.css">
</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>


    <!-- 🔥 GLOBAL BACKGROUND (you asked to include this!) -->
    <div class="global-bg"></div>
    <div class="global-bg-overlay"></div>

    <div class="receipt-container">

        <div class="receipt-card">

            <h1>Order Confirmed ✔</h1>
            <p class="subtitle">Thank you for ordering with Brock Café!</p>

            <div class="order-info">
                <p><strong>Order #:</strong> <?= htmlspecialchars($orderId) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($order['placed_at']) ?></p>
               <p><strong>Status:</strong> <?= htmlspecialchars($order['status'] ?? 'Not Set') ?></p>
            </div>

            <h2>Order Summary</h2>

            <div class="receipt-items">
                <?php foreach ($items as $item): ?>
                    <div class="receipt-item">
                        <span><?= htmlspecialchars($item['name']) ?></span>
                        <span><?= $item['quantity'] ?> × <?= number_format($item['unit_price'], 2) ?> DKK</span>
                        <strong><?= number_format($item['total_price'], 2) ?> DKK</strong>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="receipt-total">
                <strong>Total Paid:</strong>
                <span><?= number_format($order['final_amount'], 2) ?> DKK</span>
            </div>

            <div class="receipt-actions">
                <a href="/quick_serve/customer/orders/track" class="btn">Track Order</a>
                <a href="/quick_serve/customer/receipt/download?order_id=<?= $orderId ?>" class="btn">
                    Download Receipt (PDF)
                </a>

            </div>

        </div>

    </div>

</body>

</html>