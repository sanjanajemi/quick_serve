<?php
// Show errors while developing
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;

$pdo = Database::connect();

// 1) Make sure user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "<h1 style='color:red;'>You must be logged in to use deals.</h1>";
    echo '<p><a href="/quick_serve/app/views/customer/login.php">Go to login</a></p>';
    exit;
}

$customerId = (int)$_SESSION['customer_id'];
$dealCode   = $_GET['deal'] ?? '';

$orderId     = null;
$orderTotal  = 0.0;
$successText = "";

// ------------------------------------------------------
// 2) Handle each deal type
// ------------------------------------------------------
try {
    $pdo->beginTransaction();

    // ───────────────────────────────
    // A) Shawarma Combo – fixed 85 kr
    // ───────────────────────────────
    if ($dealCode === 'shawarma_combo') {
        // 2 x Shawarma Wrap (Veg, menu_item_id = 5) @ 30 kr
        // 1 x Fries (menu_item_id = 7) @ 25 kr
        // Total = 85 kr
        $dealLines = [
            [
                'menu_item_id' => 5,
                'quantity'     => 2,
                'unit_price'   => 30.00,
            ],
            [
                'menu_item_id' => 7,
                'quantity'     => 1,
                'unit_price'   => 25.00,
            ],
        ];

        $orderTotal = 0.0;
        foreach ($dealLines as &$line) {
            $line['total_price'] = $line['unit_price'] * $line['quantity'];
            $orderTotal += $line['total_price'];
        }
        unset($line);

        // Insert order
        $orderStmt = $pdo->prepare("
            INSERT INTO `order` (customer_id, placed_at, final_amount, status)
            VALUES (?, NOW(), ?, ?)
        ");
        $orderStmt->execute([
            $customerId,
            $orderTotal,      // 85.00
            'Pending',
        ]);
        $orderId = $pdo->lastInsertId();

        // Insert items
        $itemStmt = $pdo->prepare("
            INSERT INTO order_item (order_id, menu_item_id, unit_price, total_price, quantity)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($dealLines as $line) {
            $itemStmt->execute([
                $orderId,
                (int)$line['menu_item_id'],
                (float)$line['unit_price'],
                (float)$line['total_price'],
                (int)$line['quantity'],
            ]);
        }

        $successText = "Shawarma Combo Ordered!";

    // ───────────────────────────────
    // B) Morning Brew – 10% off ONE drink type (id 24), any quantity
    // ───────────────────────────────
    } elseif ($dealCode === 'coffee_discount') {

        $MORNING_BREW_ID = 24;   // the drink included in the deal

        // quantity can come from URL, default = 1
        $quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Load original price + name
        $priceStmt = $pdo->prepare("
            SELECT name, price
            FROM menu_item
            WHERE menu_item_id = ?
        ");
        $priceStmt->execute([$MORNING_BREW_ID]);
        $menuItem = $priceStmt->fetch(PDO::FETCH_ASSOC);

        if (!$menuItem) {
            $pdo->rollBack();
            echo "<h1 style='color:red;'>Morning Brew item not found.</h1>";
            echo "<p>menu_item_id used: " . htmlspecialchars($MORNING_BREW_ID) . "</p>";
            exit;
        }

        $originalPrice   = (float)$menuItem['price'];
        $discountedPrice = round($originalPrice * 0.9, 2); // 10% off per cup
        $lineTotal       = $discountedPrice * $quantity;
        $orderTotal      = $lineTotal;

        // Insert order
        $orderStmt = $pdo->prepare("
            INSERT INTO `order` (customer_id, placed_at, final_amount, status)
            VALUES (?, NOW(), ?, ?)
        ");
        $orderStmt->execute([
            $customerId,
            $orderTotal,
            'Pending',
        ]);
        $orderId = $pdo->lastInsertId();

        // Insert single order item with chosen quantity
        $itemStmt = $pdo->prepare("
            INSERT INTO order_item (order_id, menu_item_id, unit_price, total_price, quantity)
            VALUES (?, ?, ?, ?, ?)
        ");
        $itemStmt->execute([
            $orderId,
            $MORNING_BREW_ID,
            $discountedPrice,  // discounted price per drink
            $lineTotal,        // discounted total for all drinks
            $quantity,
        ]);

        $successText = "Morning Brew Deal Applied! (10% off " 
            . (int)$quantity . " × " . htmlspecialchars($menuItem['name']) . ")";

    } else {
        // Unknown or missing deal → back to dashboard
        $pdo->rollBack();
        header('Location: /quick_serve/home/dashboard');
        exit;
    }

    $pdo->commit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<h1 style='color:red;'>Error placing deal order</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo '<p><a href="/quick_serve/home/dashboard">Back to dashboard</a></p>';
    exit;
}

// ------------------------------------------------------
// 3) Success page (shared for both deals)
// ------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deal placed</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/dashboard.css">
</head>
<body>
  <header class="top-nav">
    <div class="top-left">
      <img src="/quick_serve/assets/images/logo/logo.png" alt="Brock Café Logo" class="logo">
    </div>
    <nav class="nav-links">
      <a href="/quick_serve/home/dashboard" class="nav-link">Dashboard</a>
      <a href="/quick_serve/app/views/customer/logout.php" class="nav-link">Logout</a>
    </nav>
  </header>

  <div style="max-width:600px;margin:40px auto;background:#f1f8e9;padding:20px 24px;border-radius:12px;text-align:center;">
      <h1 style="color:#1b5e20;margin-bottom:10px;">✅ <?= htmlspecialchars($successText) ?></h1>
      <p>Order #<?= (int)$orderId ?> has been created.</p>
      <p><strong>Total: <?= number_format($orderTotal, 2) ?> kr</strong></p>

      <div style="margin-top:20px;">
          <a href="/quick_serve/app/views/customer/cart.php?order_id=<?= (int)$orderId ?>"
             style="display:inline-block;margin:5px 10px;padding:10px 18px;border-radius:8px;background:#2e7d32;color:#fffde7;text-decoration:none;font-weight:600;">
             View in Cart
          </a>

          <a href="/quick_serve/home/dashboard"
             style="display:inline-block;margin:5px 10px;padding:10px 18px;border-radius:8px;background:#ccc;color:#333;text-decoration:none;font-weight:600;">
             Back to Dashboard
          </a>
      </div>
  </div>

  <footer class="app-footer">
    © 2025 Brock Café · Your dashboard for delicious decisions
  </footer>
</body>
</html>
