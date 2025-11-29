<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$customerName = isset($_SESSION['customer_name']) && $_SESSION['customer_name'] !== ''
  ? htmlspecialchars($_SESSION['customer_name'])
  : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to Brock Café</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/customer/home.css">
</head>
<body>
  <header class="top-nav">
    <img src="/quick_serve/assets/images/logo/logo.png" alt="Brock Café Logo" class="logo">
    <nav class="nav-links">
      <a href="/quick_serve/customer/login">Login</a>
      <a href="/quick_serve/customer/signup">Sign Up</a>
      <a href="/quick_serve/customer/menu.php">Browse Menu</a>
    </nav>
  </header>

  <section class="welcome-banner">
    <h1>Welcome to Brock Café</h1>
    <p>Order, track and enjoy your meals — all in one place.</p>

  </section>

  <section class="search-bar">
    <form method="GET" action="/quick_serve/customer/menu.php">
      <input type="text" name="search" placeholder="Search menu item..." />
      <button type="submit">Search</button>
    </form>
  </section>

  <section class="quick-nav">
    <h2>Explore Categories</h2>
    <div class="grid">
      <a href="/quick_serve/customer/menu.php?category=Food" class="category-card">
        <img src="/quick_serve/assets/images/menu_item/food/fried_chicken.jpg" alt="Food">
        <h3>Food</h3>
      </a>
      <a href="/quick_serve/customer/menu.php?category=Beverages" class="category-card">
        <img src="/quick_serve/assets/images/menu_item/food/mozarella_fries_stick.jpg" alt="Beverages">
        <h3>Beverages</h3>
      </a>
      <a href="/quick_serve/customer/menu.php?category=Bakery" class="category-card">
        <img src="/quick_serve/assets/images/menu_item/food/veg_shawarma_wrap.jpg" alt="Bakery">
        <h3>Bakery</h3>
      </a>
    </div>
  </section>

  <section class="special-offers">
    <h2>🔥 Special Offers</h2>
    <div class="offers-grid">
      <a href="/quick_serve/customer/deal.php?deal=shawarma_combo" class="offer-card">
        <img src="/quick_serve/assets/images/logo/Shawarma%20combo.png" alt="Shawarma Combo">
        <h4>Shawarma Combo</h4>
        <p>Get 2 wraps + fries for just 85 kr!</p>
      </a>
      <a href="/quick_serve/customer/deal.php?deal=coffee_discount" class="offer-card">
        <img src="/quick_serve/assets/images/logo/Morning%20brews.png" alt="Morning Brew">
        <h4>Morning Brew</h4>
        <p>20% off all McCafé drinks before 11 AM</p>
      </a>
    </div>
  </section>

  <footer class="app-footer">
    © 2025 Brock Café · Built for speed and simplicity
  </footer>
</body>
</html>
