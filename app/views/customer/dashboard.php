<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../core/Database.php';

use App\Core\Database;

if (!isset($_SESSION['customer_id'])) {
    header("Location: /quick_serve/customer/login");
    exit;
}

$customerName   = htmlspecialchars($_SESSION['customer_name']);
$customerAvatar = $_SESSION['customer_avatar'] ?? 'default.png';

// Cart count for badge (uses the real cart)
$cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));

// Fetch deals
$pdo = Database::connect();
$stmt = $pdo->prepare("SELECT * FROM menu_item WHERE is_deal = 1 ORDER BY name");
$stmt->execute();
$deals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Brock Café — Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/dashboard.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/cart.css">
</head>

<body>
<div class="global-bg"></div>
<div class="global-bg-overlay"></div>

<!-- CART ICON (same behaviour as menu page) -->
<div class="cart-icon" id="openCart">
    🛒 <span id="cart-count"><?= $cartCount ?></span>
</div>

<!-- CART DRAWER (same structure as menu page) -->
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

<!--TOPBAR  -->
<div class="topbar">
    <div class="top-left">
        <img src="/quick_serve/assets/images/logo/logo.png" class="topbar-logo" alt="Logo">

        <!-- Menu Dropdown -->
        <div class="dropdown">
            <button type="button"
                    class="nav-btn dropbtn"
                    onclick="toggleDropdown('menuDropdown')">
                🍽 Our Menu ▾
            </button>
            <div id="menuDropdown" class="dropdown-content">
                <a href="/quick_serve/app/views/customer/menu_view.php?category=Beverage">Beverage</a>
                <a href="/quick_serve/app/views/customer/menu_view.php?category=Food">Food</a>
                <a href="/quick_serve/app/views/customer/menu_view.php?category=Bakery">Bakery</a>
            </div>
        </div>

        <!-- Settings Dropdown -->
        <div class="dropdown">
            <button type="button" class="nav-btn dropbtn" onclick="toggleDropdown('settingsDropdown')">
                ⚙️ Settings ▾
            </button>
            <div id="settingsDropdown" class="dropdown-content">
                <a href="/quick_serve/app/views/customer/settings.php">✏️ Account Settings</a>
                <a href="#" onclick="openDeletePopup()">🗑️ Delete Account</a>
            </div>
        </div>

        <a href="/quick_serve/app/views/customer/order_history.php" class="nav-btn">📜 Order History</a>
      <a href="/quick_serve/customer/orders/track" class="nav-btn">Track Order</a>
        <a href="/quick_serve/customer/logout" class="nav-btn">📄 Logout</a>
    </div>

    <div class="top-right">
        <span>Hello, <?= $customerName ?> 👋</span>
        <img src="/quick_serve/storage/uploads/<?= $customerAvatar ?>" class="avatar" alt="Avatar">
        <!-- The cart icon is already positioned globally via .cart-icon -->
    </div>
</div>

<!--  HERO -->
<div id="hero-section" class="hero-content">
    <h1 class="hero-title">Welcome back<br>Brock Café</h1>
    <p class="hero-subtitle">Your favourites, your orders, your deals — all in one place</p>
    <br><a href="/quick_serve/app/views/customer/menu_item.php" class="cta-btn">Start an Order</a></br>
</div>

<!--  DEALS SECTION (uses real menu_item deals)  -->
<div id="deals-section" class="deals-section">
    <h2 class="deals-title">🔥 Special Deals for You</h2>

    <?php foreach ($deals as $deal): ?>
        <div class="deal-card" onclick="toggleDeal('deal<?= $deal['menu_item_id'] ?>')">
            <img src="<?= htmlspecialchars($deal['image_url']) ?>" alt="<?= htmlspecialchars($deal['name']) ?>">
            <div class="deal-card-title"><?= htmlspecialchars($deal['name']); ?></div>

            <div class="deal-dropdown" id="deal-deal<?= $deal['menu_item_id'] ?>">
                <p><?= htmlspecialchars($deal['description']); ?></p>

                <!-- EXACT SAME PATTERN AS MENU PAGE: POST -> /customer/cart/add -->
                <form method="POST"
                      action="/quick_serve/customer/cart/add"
                      class="add-to-cart-form">
                    <input type="hidden" name="menu_item_id" value="<?= $deal['menu_item_id'] ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button class="btn" type="submit">Add to Cart</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- DELETE ACCOUNT POPUP (unchanged) -->
<div id="deletePopup" class="delete-modal">
    <div class="delete-box">
        <h2 id="popupTitle">Are you absolutely sure?</h2>
        <p id="popupText">Your account will be permanently deleted.</p>

        <div id="timerDisplay" class="timer" style="display:none;">30</div>

        <div id="popupButtons">
            <button class="danger-btn" onclick="startCountdown()">Yes — Delete My Account</button>
            <button class="cancel-btn" onclick="cancelDelete()">Cancel</button>
        </div>

        <form id="deleteForm" method="POST"
              action="/quick_serve/app/views/customer/delete_account.php"
              style="display:none;">
            <input type="hidden" name="confirm_delete" value="1">
        </form>
    </div>
</div>

<script>
/* 
   DROPDOWNS
*/
function toggleDropdown(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}

/* 
   DELETE ACCOUNT POPUP
*/
function openDeletePopup() {
    document.getElementById("deletePopup").style.display = "flex";
    document.getElementById("hero-section").classList.add("blur-background");
    document.getElementById("deals-section").classList.add("blur-background");
}

function cancelDelete() {
    document.getElementById("deletePopup").style.display = "none";
    document.getElementById("hero-section").classList.remove("blur-background");
    document.getElementById("deals-section").classList.remove("blur-background");
}

let countdown = 30;
let timer;

function startCountdown() {
    document.getElementById("popupTitle").textContent = "Deleting your account in...";
    document.getElementById("popupText").textContent = "You can still cancel before time runs out.";
    document.getElementById("timerDisplay").style.display = "block";

    document.querySelector(".danger-btn").style.display = "none";

    timer = setInterval(() => {
        countdown--;
        document.getElementById("timerDisplay").textContent = countdown;

        if (countdown <= 0) {
            clearInterval(timer);
            document.getElementById("deleteForm").submit();
        }
    }, 1000);
}

/* 
   DEAL DROPDOWNS
 */
function toggleDeal(id) {
    const dropdown = document.getElementById("deal-" + id);
    if (!dropdown) return;
    dropdown.classList.toggle("open");
}

/* 
   ADD TO CART (same as menu page)
 */
document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = new FormData(form);

        const res = await fetch(form.action, {
            method: "POST",
            body: data
        });

        const json = await res.json();

        // Update badge
        if (json.count !== undefined && document.getElementById("cart-count")) {
            document.getElementById("cart-count").textContent = json.count;
        }

        loadCart();
    });
});

/* 
   LOAD CART
 */
async function loadCart() {
    const res = await fetch("/quick_serve/customer/cart/items");
    const data = await res.json();

    document.getElementById("cartItems").innerHTML = data.html;
    document.getElementById("cartTotal").textContent = data.total.toFixed(2) + " DKK";

    if (data.count !== undefined && document.getElementById("cart-count")) {
        document.getElementById("cart-count").textContent = data.count;
    }
}

/* 
   UPDATE QUANTITY
 */
async function updateCart(id, action) {
    await fetch("/quick_serve/customer/cart/update", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `menu_item_id=${id}&action=${action}`
    });

    loadCart();
}

/* 
   REMOVE FROM CART
*/
async function removeFromCart(id) {
    await fetch("/quick_serve/customer/cart/remove", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `menu_item_id=${id}`
    });

    loadCart();
}

/* 
   CART DRAWER OPEN/CLOSE
 */
document.getElementById("openCart").onclick = () => {
    document.getElementById("cartDrawer").classList.add("open");
    loadCart();
};

document.getElementById("closeCart").onclick = () => {
    document.getElementById("cartDrawer").classList.remove("open");
};

document.getElementById("continueShopping").onclick = () => {
    document.getElementById("cartDrawer").classList.remove("open");
};

/* 
   CHECKOUT (uses your controller)
 */
document.getElementById("goCheckout").onclick = () => {
    window.location.href = "/quick_serve/customer/order_start";
};
</script>

</body>
</html>
