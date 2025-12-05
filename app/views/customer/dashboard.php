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
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
}


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






<div class="topbar">
    <div class="top-left">
        <img src="/quick_serve/assets/images/logo/logo.png" class="topbar-logo" alt="Logo">

        
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
        
    </div>
</div>


<div id="hero-section" class="hero-content">
    <h1 class="hero-title">Welcome back<br>Brock Café</h1>
    <p class="hero-subtitle">Your favourites, your orders, your deals — all in one place</p>
    <br><a href="/quick_serve/app/views/customer/menu_item.php" class="cta-btn">Start an Order</a></br>
</div>


<div id="deals-section" class="deals-section">
    <h2 class="deals-title">🔥 Special Deals for You</h2>
     <?php foreach ($deals as $deal): ?>
    <div class="deal-card" onclick="toggleDeal(<?= $deal['menu_item_id'] ?>)">
        <img src="<?= htmlspecialchars($deal['image_url']) ?>" 
             alt="<?= htmlspecialchars($deal['name']) ?>">

        <div class="deal-card-title"><?= htmlspecialchars($deal['name']); ?></div>

        <div class="deal-dropdown" id="deal<?= $deal['menu_item_id'] ?>">
            <p><?= htmlspecialchars($deal['description']); ?></p>

            <form method="POST"
                  action="/quick_serve/customer/cart/add"
                  class="add-to-cart-form"
                  onclick="event.stopPropagation()">

                <input type="hidden" name="menu_item_id" value="<?= $deal['menu_item_id'] ?>">
                <input type="hidden" name="quantity" value="1">
               <button class="btn" type="submit" onclick="event.stopPropagation()">
                Add to Cart
            </button>
        </form> 
        </div>
    </div>
<?php endforeach; ?>

    
</div>


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
document.addEventListener("DOMContentLoaded", function() {

function toggleDropdown(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}


window.openDeletePopup = function() {
    document.getElementById("deletePopup").style.display = "flex";
    document.getElementById("hero-section").classList.add("blur-background");
    document.getElementById("deals-section").classList.add("blur-background");
}

window.cancelDelete = function() {
    document.getElementById("deletePopup").style.display = "none";
    document.getElementById("hero-section").classList.remove("blur-background");
    document.getElementById("deals-section").classList.remove("blur-background");
}

let countdown = 30;
let timer;

window.startCountdown = function() {
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

document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = new FormData(form);
        await fetch(form.action, { method: "POST", body: data });

        showAddedPopup();

        setTimeout(() => {
    window.location.href = "/quick_serve/app/views/customer/menu_item.php?open_cart=1";
}, 1500);

    });
});

function showAddedPopup() {
    const popup = document.createElement("div");
    popup.innerHTML = "✔ Item added to cart! Redirecting…";
    popup.style.position = "fixed";
    popup.style.top = "50%";
    popup.style.left = "50%";
    popup.style.transform = "translate(-50%, -50%)";
    popup.style.background = "#4CAF50";
    popup.style.color = "#fff";
    popup.style.padding = "15px 20px";
    popup.style.borderRadius = "10px";
    popup.style.fontSize = "18px";
    popup.style.zIndex = "9999";
    popup.style.boxShadow = "0 5px 15px rgba(0,0,0,0.3)";
    document.body.appendChild(popup);

    setTimeout(() => popup.remove(), 1500);
}

window.toggleDeal = function(id) {
    const el = document.getElementById("deal" + id);
    if (el) el.classList.toggle("open");
};

});
</script>


</body>
</html>