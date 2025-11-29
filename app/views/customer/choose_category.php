<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$customerName = $_SESSION['customer_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Category - Brock Café</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/choose_category.css">
</head>
<body>

<div class="category-container">
    <h2>Start Your Order</h2>
    <p>Hello <?= htmlspecialchars($customerName) ?>, what would you like?</p>

    <div class="category-grid">

        <a href="/quick_serve/app/views/customer/menu.php?category=Food" class="category-card">
            🍔 Food
        </a>

        <a href="/quick_serve/app/views/customer/menu.php?category=Beverage" class="category-card">
            🍹 Beverage
        </a>

        <a href="/quick_serve/app/views/customer/menu.php?category=Bakery" class="category-card">
            🥐 Bakery
        </a>

    </div>
</div>

</body>
</html>
