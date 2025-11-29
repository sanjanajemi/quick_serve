<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;

// User must be logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: /quick_serve/customer/login");
    exit;
}

// Cart must not be empty
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: /quick_serve/customer/cart");
    exit;
}

$customerId = $_SESSION['customer_id'];
$comments   = trim($_POST['comments'] ?? "");

$pdo = Database::connect();
$pdo->beginTransaction();

try {


    // 1. INSERT ORDER
 
    $totalAmount = 0;
    foreach ($cart as $c) {
        $totalAmount += $c['price'] * $c['quantity'];
    }

    $stmt = $pdo->prepare("
        INSERT INTO `order` (customer_id, final_amount, placed_at, comments, status)
        VALUES (?, ?, NOW(), ?, 'Pending')
    ");
    $stmt->execute([$customerId, $totalAmount, $comments]);

    $orderId = $pdo->lastInsertId();


 
    // 2. INSERT ORDER ITEMS
  
    $itemStmt = $pdo->prepare("
        INSERT INTO order_item (order_id, menu_item_id, unit_price, total_price, quantity)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($cart as $item) {
        $unit = $item['price'];
        $line = $unit * $item['quantity'];

        $itemStmt->execute([
            $orderId,
            $item['menu_item_id'],
            $unit,
            $line,
            $item['quantity']
        ]);
    }

    // 3. CLEAR CART

    unset($_SESSION['cart']);

    $pdo->commit();

    // FLASH SUCCESS
    $_SESSION['flash_message'] = "Order #$orderId placed successfully!";
    $_SESSION['flash_type'] = "success";

} catch (Exception $e) {
    $pdo->rollBack();
    die("Order failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmed — Brock Café</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <meta http-equiv="refresh" content="3; url=/quick_serve/customer/receipt?order_id=<?= $orderId ?>">
</head>

<body>

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

<!-- BACKGROUND -->
<div class="page-bg" style="background-image:url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>

<div class="page-content" style="max-width:600px; margin:auto; text-align:center;">

    <div class="card">
        <h1>✔ Order Placed Successfully!</h1>

        <p>Thank you for your order 🎉</p>
        <p>Order ID: <strong>#<?= $orderId ?></strong></p>

        <p>Your receipt will appear shortly...</p>

        <a href="/quick_serve/customer/receipt?order_id=<?= $orderId ?>" class="btn" style="margin-top:20px;">
            View Receipt Now
        </a>
    </div>

</div>

</body>
</html>
