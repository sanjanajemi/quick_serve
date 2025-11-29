<?php
require_once __DIR__ . '/../../core/Database.php';
session_start();

use App\Core\Database;

$db = Database::connect();

// Selected category
$category = $_GET['category'] ?? null;

// Fetch menu items using OOP (later you will use MenuController)
if ($category) {
    $stmt = $db->prepare("SELECT * FROM menu_item WHERE category = ? ORDER BY name");
    $stmt->execute([$category]);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $menuItems = [];
}

// Init dashboard cart (ONLY CHANGE REQUIRED)
if (!isset($_SESSION['dashboard_cart'])) {
    $_SESSION['dashboard_cart'] = [];
}
$cartCount = count($_SESSION['dashboard_cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start Your Order · Brock Café</title>

    <!-- Exact same CSS -->
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/menu.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/cart.css">
</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>

<!-- Global background -->
<div class="global-bg"></div>
<div class="global-bg-overlay"></div>

<!-- CART ICON -->
<div class="cart-icon" id="openCart"> Cart 🛒 <span id="cart-count"><?= $cartCount ?></span>
</div>

<!-- CART DRAWER -->
<div class="cart-drawer" id="cartDrawer">

    <div class="cart-overlay" id="closeCart"></div>

    <div class="cart-panel">
        <h2>Your Cart</h2>

        <div class="cart-items" id="cartItems"></div>

        <div class="cart-footer">

            <button class="btn continue-btn" type="button" id="continueShopping">
                ← Continue Shopping
            </button>

            <strong>Total:</strong> <span id="cartTotal">0 DKK</span>

            <button class="btn checkout-btn" type="button" id="goCheckout">
                Proceed to Checkout
            </button>

        </div>

    </div>
</div>

<div class="topbar"></div>

<div class="page-content">

<?php if (!$category): ?>

    <h2>Select a category to start your order:</h2>

    <div class="menu-container">

        <div class="menu-card">
            <h3>Bakery</h3>
            <p>Freshly baked pastries & bread</p>
            <a href="?category=Bakery" class="btn">View Bakery</a>
        </div>

        <div class="menu-card">
            <h3>Food</h3>
            <p>Delicious meals & favorites</p>
            <a href="?category=Food" class="btn">View Food</a>
        </div>

        <div class="menu-card">
            <h3>Beverage</h3>
            <p>Coffee, tea & refreshing drinks</p>
            <a href="?category=Beverage" class="btn">View Drinks</a>
        </div>

    </div>

<?php else: ?>

    <h2><?= htmlspecialchars($category) ?> Menu</h2>

    <a href="menu_item.php" class="btn" style="margin-bottom:20px;">← Back to Categories</a>

    <div class="menu-container">

        <?php if (!empty($menuItems)): ?>
            <?php foreach ($menuItems as $item): ?>
                <div class="menu-card">

                    <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>">

                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p><?= htmlspecialchars($item['description']) ?></p>
                    <p><strong>Price:</strong> <?= number_format($item['price'], 2) ?> DKK</p>

                    <!-- ONLY URL CHANGED -->
                    <form method="POST" action="/quick_serve/customer/cart/add" class="add-to-cart-form">

                        <input type="hidden" name="menu_item_id" value="<?= $item['menu_item_id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn" type="submit">Add to Cart</button>
                    </form>

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>No items found.</p>
        <?php endif; ?>

    </div>

<?php endif; ?>

</div>

<script>

// EXACT SAME JS — only endpoints changed!

document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = new FormData(form);

        const res = await fetch(form.action, {
            method: "POST",
            body: data
        });

        const json = await res.json();

        document.getElementById("cart-count").textContent = json.count;

        loadCart();
    });
});

async function loadCart() {
    const res = await fetch("/quick_serve/customer/cart/items");
    const data = await res.json();

    document.getElementById("cartItems").innerHTML = data.html;
    document.getElementById("cartTotal").textContent = data.total.toFixed(2) + " DKK";

    if (data.count !== undefined && document.getElementById("cart-count")) {
        document.getElementById("cart-count").textContent = data.count;
    }
}

async function updateCart(id, action) {
    const body = `menu_item_id=${id}&action=${action}`;

    await fetch("/quick_serve/customer/cart/update", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body
    });

    loadCart();
}

async function removeFromCart(id) {
    await fetch("/quick_serve/customer/cart/remove", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `menu_item_id=${id}`
    });

    loadCart();
}

document.getElementById("openCart").onclick = () => {
    document.getElementById("cartDrawer").classList.add("open");
    loadCart(); 
};

document.getElementById("closeCart").onclick = () => {
    document.getElementById("cartDrawer").classList.remove("open");
};

document.getElementById("goCheckout").onclick = () => {
    window.location.href = "/quick_serve/customer/order_start";
};

document.getElementById("continueShopping").onclick = () => {
    document.getElementById("cartDrawer").classList.remove("open");
};

</script>

</body>
</html>
