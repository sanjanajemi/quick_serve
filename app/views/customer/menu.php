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

$customerAvatar = $_SESSION['customer_avatar'] ?? 'default.png';

// Get category from URL
$activeCategory = $_GET['category'] ?? "All";

// Connect to DB (PDO)
$conn = Database::connect();

// Build query
if ($activeCategory === "All") {
    $sql = "SELECT * FROM menu_item WHERE status = 'published'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
} else {
    $sql = "SELECT * FROM menu_item WHERE category = :category AND status = 'published'";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['category' => $activeCategory]);
}

// Fetch items into array
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Brock Café — Menu</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <style>
        h1 { color: white; text-shadow: 0px 3px 8px rgba(0,0,0,0.4); }
        .menu-container { width: 90%; max-width: 1100px; margin: 40px auto; }
        .category-tabs { display: flex; gap: 15px; margin-bottom: 25px; justify-content: center; }
        .tab-btn { padding: 10px 20px; background: rgba(255,255,255,0.3); border-radius: 10px; color: white; font-weight: 600; text-decoration: none; transition: .2s; }
        .tab-btn:hover { background: rgba(255,255,255,0.45); }
        .tab-btn.active { background: #0aa06e; color: white; box-shadow: 0px 4px 14px rgba(0,0,0,0.25); }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 22px; }
        .menu-card { background: rgba(255,255,255,0.9); padding: 15px; border-radius: 14px; box-shadow: 0px 6px 18px rgba(0,0,0,0.15); transition: .25s; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0px 10px 25px rgba(0,0,0,0.22); }
        .menu-card img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; }
        .item-title { margin-top: 10px; font-size: 18px; font-weight: 700; color: #003B5C; }
        .item-price { color: #0c7b52; font-weight: 700; margin: 6px 0; }
        .add-btn { display: block; padding: 10px 16px; text-align: center; background: #1a73e8; color: white; border-radius: 8px; margin-top: 10px; font-weight: 600; text-decoration: none; transition: .25s; }
        .add-btn:hover { background: #0b5fcc; }
    </style>
</head>
<body>
<div class="page-bg"></div>
<div class="page-bg-overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="/quick_serve/assets/images/logo/logo.png" class="logo" alt="Logo">
    <a href="/quick_serve/customer/dashboard">Dashboard</a>
    <a href="/quick_serve/customer/category">Categories</a>
    <a class="active" href="/quick_serve/customer/menu?category=All">All Items</a>
    <a href="/quick_serve/customer/cart">Cart</a>
    <a href="/quick_serve/customer/orders">Order History</a>
    <a href="/quick_serve/customer/settings">Settings</a>
    <a href="/quick_serve/customer/feedback">Feedback</a>
    <a href="/quick_serve/customer/logout">Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <strong>Our Menu</strong>
    <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($customerAvatar) ?>"
         onerror="this.src='/quick_serve/assets/images/customers/avatars/default.png';">
</div>

<!-- PAGE CONTENT -->
<div class="page-content">
    <h1 style="margin-bottom:20px;">Menu</h1>
    <div class="menu-container">

        <!-- CATEGORY FILTER TABS -->
        <div class="category-tabs">
            <a href="/quick_serve/customer/menu?category=All"
               class="tab-btn <?= ($activeCategory=='All'?'active':'') ?>">All</a>
            <a href="/quick_serve/customer/menu?category=Beverage"
               class="tab-btn <?= ($activeCategory=='Beverage'?'active':'') ?>">Beverage</a>
            <a href="/quick_serve/customer/menu?category=Food"
               class="tab-btn <?= ($activeCategory=='Food'?'active':'') ?>">Food</a>
            <a href="/quick_serve/customer/menu?category=Bakery"
               class="tab-btn <?= ($activeCategory=='Bakery'?'active':'') ?>">Bakery</a>
        </div>

        <!-- MENU GRID -->
        <div class="menu-grid">
            <?php if (empty($items)): ?>
                <p style="color:white; font-size:20px;">No items found.</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="menu-card">
                        <!-- Use full URL from DB directly -->
                        <img src="<?= htmlspecialchars($item['image_url']) ?>"
                             onerror="this.src='/quick_serve/assets/images/customer/hero1.png';">

                        <div class="item-title"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="item-price"><?= number_format($item['price'], 2) ?> kr</div>

                        <form method="POST" action="/quick_serve/customer/cart/add">
                            <input type="hidden" name="menu_item_id" value="<?= $item['menu_item_id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
