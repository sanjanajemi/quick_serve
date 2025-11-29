<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../core/Database.php';

use App\Core\Database;

// Must log in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['redirect_after_login'] = "/customer/category";
    header("Location: /quick_serve/customer/login");
    exit;
}

$customerName   = htmlspecialchars($_SESSION['customer_name']);
$customerAvatar = $_SESSION['customer_avatar'] ?? 'default.png';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Brock Café — Categories</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">

    <style>
        /* Compact hero header for category page */
        .page-header {
            text-align: center;
            padding: 60px 20px 20px 20px;
            color: white;
        }

        .page-header h1 {
            font-size: 42px;
            font-weight: 700;
            text-shadow: 0px 3px 10px rgba(0,0,0,0.4);
        }

        .category-grid {
            max-width: 650px;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .cat-btn {
            padding: 15px;
            font-size: 18px;
            border-radius: 10px;
            text-decoration: none;
            color: white !important;
            font-weight: 600;
            display: block;
            text-align: center;
            transition: 0.25s ease;
        }

        .cat-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0px 8px 22px rgba(0,0,0,0.20);
        }
    </style>
</head>

<body class="customer-page">

<!-- Background -->
<div class="page-bg"></div>
<div class="page-bg-overlay"></div>

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
    if (!toast) return;
    toast.classList.add("show");
    setTimeout(()=>toast.classList.remove("show"), 3000);
}, 150);
</script>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="/quick_serve/assets/images/logo/logo.png" class="logo" alt="logo">

    <a href="/quick_serve/customer/dashboard">Dashboard</a>
    <a class="active" href="/quick_serve/customer/category">Categories</a>
    <a href="/quick_serve/customer/menu?category=All">All Items</a>
    <a href="/quick_serve/customer/cart">Cart</a>
    <a href="/quick_serve/customer/orders">Order History</a>
    <a href="/quick_serve/customer/settings">Settings</a>
    <a href="/quick_serve/customer/feedback">Feedback</a>
    <a href="/quick_serve/customer/logout">Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <strong>Select a Category</strong>
    <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($customerAvatar) ?>"
         onerror="this.src='/quick_serve/assets/images/customers/avatars/default.png';">
</div>

<!-- HEADER TITLE -->
<div class="page-header">
    <h1>Choose a Category</h1>
</div>

<!-- CATEGORY BUTTONS -->
<div class="page-content">

    <div class="card" style="max-width:700px; margin:auto;">
        <h2 style="margin-bottom:15px;">Available Categories</h2>

        <div class="category-grid">
            <a href="/quick_serve/customer/menu?category=Beverage"
   class="cat-btn" style="background:#2FA4E7;">
   🍹 Beverage
</a>

<a href="/quick_serve/customer/menu?category=Food"
   class="cat-btn" style="background:#28A745;">
   🍔 Food 
</a>

<a href="/quick_serve/customer/menu?category=Bakery"
   class="cat-btn" style="background:#6F42C1;">
   🥐 Bakery
</a>

<a href="/quick_serve/customer/menu?category=All"
   class="cat-btn" style="background:#17A2B8;">
   📜 View All Items
</a>


            


        </div>
    </div>
</div>

</body>
</html>
