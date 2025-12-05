<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$customerId = $_SESSION['customer_id'] ?? null;
if (!$customerId) {
    $_SESSION['redirect_after_login'] = "/customer/delete_account";
    header("Location: /quick_serve/customer/login");
    exit;
}

$customerAvatar = $_SESSION['customer_avatar'] ?? "default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brock Café — Delete Account</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/delete_account.css">
</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>


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
    setTimeout(() => toast?.classList.remove("show"), 3000);
}, 100);
</script>


<div class="page-bg" style="background-image:url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>


<div class="sidebar">
    <img src="/quick_serve/assets/images/logo/logo.png" class="logo">

    <a href="/quick_serve/customer/dashboard">Dashboard</a>
    <a href="/quick_serve/customer/category">Categories</a>
    <a href="/quick_serve/customer/menu?category=All">Menu</a>
    <a href="/quick_serve/customer/cart">Cart</a>
    <a href="/quick_serve/customer/orders">Order History</a>
    <a href="/quick_serve/customer/settings">Settings</a>
    <a href="/quick_serve/customer/feedback">Feedback</a>
    <a href="/quick_serve/customer/logout">Logout</a>
</div>


<div class="topbar">
    <strong>Delete Account</strong>
    <div>
        <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($customerAvatar) ?>"
             onerror="this.src='/quick_serve/assets/images/customers/avatars/default.png';">
    </div>
</div>


<div class="page-content">

    <div class="delete-container">

        <h2>🗑️ Delete Account</h2>

        <p class="warning-text">
            This action is <strong>permanent</strong> and will remove all your data including orders and settings.
        </p>

        <p>Before continuing, please confirm your password.</p>

        
        <form id="deleteForm" method="POST" action="/quick_serve/customer/delete_account/confirm">

            <label for="password"><strong>Enter your password:</strong></label>
            <input type="password" name="password" required placeholder="Enter your password">

            <button type="submit" id="confirmBtn" class="danger-btn">
                Continue to Delete
            </button>

            <button type="button" class="cancel-btn"
                    onclick="window.location.href='/quick_serve/customer/settings'">
                Cancel
            </button>
        </form>

    </div>
</div>

</body>
</html>
