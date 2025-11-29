<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$customerAvatar = $_SESSION['customer_avatar'] ?? 'default.png';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Brock Café — Feedback</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
</head>

<body>

<!-- FLASH MESSAGE -->
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

<!-- BACKGROUND -->
<div class="page-bg" style="background-image: url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="/quick_serve/assets/images/logo/logo.png" class="logo">

    <a href="/quick_serve/customer/dashboard">Dashboard</a>
    <a href="/quick_serve/customer/category">Categories</a>
    <a href="/quick_serve/customer/menu?category=All">Menu</a>
    <a href="/quick_serve/customer/cart">Cart</a>
    <a href="/quick_serve/customer/orders">Order History</a>
    <a href="/quick_serve/customer/settings">Settings</a>
    <a href="/quick_serve/customer/feedback" class="active">Feedback</a>
    <a href="/quick_serve/customer/logout">Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div><strong>Feedback</strong></div>

    <div>
        <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($customerAvatar) ?>"
             onerror="this.src='/quick_serve/assets/images/customers/avatars/default.png';">
    </div>
</div>

<!-- PAGE CONTENT -->
<div class="page-content">

    <h1>We Value Your Feedback</h1>

    <div class="card">

        <h3>Tell us about your experience</h3>

        <form method="POST" action="/quick_serve/customer/feedback/submit">

            <!-- RATING -->
            <label>Rating</label>
            <select name="rating" required>
                <option value="">Select Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ — Excellent</option>
                <option value="4">⭐⭐⭐⭐ — Very Good</option>
                <option value="3">⭐⭐⭐ — Good</option>
                <option value="2">⭐⭐ — Poor</option>
                <option value="1">⭐ — Very Poor</option>
            </select>

            <!-- MESSAGE -->
            <label>Your Message</label>
            <textarea name="message" rows="5" placeholder="Write your feedback here..." required></textarea>

            <button class="btn" type="submit">Submit Feedback</button>
        </form>

    </div>

</div>

</body>
</html>
