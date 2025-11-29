<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;

// Must be logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: /quick_serve/customer/login");
    exit;
}

// Must have order id
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header("Location: /quick_serve/customer/orders");
    exit;
}

$pdo = Database::connect();

// Fetch order
$stmt = $pdo->prepare("
    SELECT order_id, placed_at, final_amount, status
    FROM `order`
    WHERE order_id=? AND customer_id=?
");
$stmt->execute([$orderId, $_SESSION['customer_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<h2>Order not found</h2>";
    exit;
}

$status = strtolower($order['status']);
$customerAvatar = $_SESSION['customer_avatar'] ?? 'default.png';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Track Order #<?= $orderId ?></title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/order_tracking.css">

</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>
<div class="global-bg"></div>
<div class="global-bg-overlay"></div>

<!-- FLASH TOAST -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div id="flashToast" class="flash-toast <?= $_SESSION['flash_type'] ?? '' ?>">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<script>
setTimeout(() => {
    const toast = document.getElementById("flashToast");
    if (toast) toast.classList.add("show");
    setTimeout(() => {
        if (toast) toast.classList.remove("show");
    }, 3000);
}, 100);
</script>
<!-- END FLASH TOAST -->

<!-- Background -->
<div class="page-bg" style="background-image:url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>

<!-- Sidebar -->
<div class="sidebar">
    <img src="/quick_serve/assets/images/logo/logo.png">

    <a href="/quick_serve/customer/dashboard">Dashboard</a>
    <a href="/quick_serve/customer/orders">Order History</a>
</div>

<!-- Topbar -->
<div class="topbar">
    <strong>Track Order #<?= $orderId ?></strong>
    <div>
        <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($customerAvatar) ?>"
             onerror="this.src='/quick_serve/assets/images/customers/avatars/default.png';">
    </div>
</div>

<!-- Page -->
<div class="page-content">

    <h1>Order Status</h1>

    <div class="card">

        <p><strong>Current Status:</strong></p>
        <h2><?= htmlspecialchars($order['status']) ?></h2>

        <hr style="border-color:#E3E8EC; margin:20px 0;">

        <h3>Status Timeline</h3>

        <ul style="list-style:none; padding-left:0;">

    <ul class="status-timeline">

    <li class="status-step <?= $status=='received' ? 'active-received' : '' ?>">
        🟡 Received
    </li>

    <li class="status-step <?= $status=='preparing' ? 'active-preparing' : '' ?>">
        🟠 Preparing
    </li>

    <li class="status-step <?= $status=='ready' ? 'active-ready' : '' ?>">
        🟢 Ready for Pickup
    </li>

    <li class="status-step <?= $status=='cancelled' ? 'active-cancelled' : '' ?>">
        ❌ Cancelled
    </li>

</ul>


</ul>

    </div>

    <div class="card" style="text-align:center;">
        <a class="btn" href="/quick_serve/customer/order/track?order_id=<?= $orderId ?>">Refresh Status</a>
        <a class="btn" href="/quick_serve/customer/orders" style="background:#1572A1;">Back to Orders</a>
    </div>

</div>

</body>
</html>
