<?php
require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;


ini_set('display_errors', 1);
error_reporting(E_ALL);


$db = Database::connect();


$category = $_GET['category'] ?? null;


if ($category) {
    $stmt = $db->prepare("SELECT * FROM menu_item WHERE category = ? ORDER BY name");
    $stmt->execute([$category]);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $menuItems = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Menu · Brock Café</title>

    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/menu.css">
</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>


<div class="global-bg"></div>
<div class="global-bg-overlay"></div>

<div class="page-content">

<?php if (!$category): ?>
    
    <h2>Explore Our Menu:</h2>

    <div class="menu-container">

        <div class="menu-card">
            <h3>Bakery</h3>
            <p>Fresh breads & pastries</p>
            <a href="?category=Bakery" class="btn">View Bakery</a>
        </div>

        <div class="menu-card">
            <h3>Food</h3>
            <p>Main dishes & meals</p>
            <a href="?category=Food" class="btn">View Food</a>
        </div>

        <div class="menu-card">
            <h3>Beverage</h3>
            <p>Coffee, tea & drinks</p>
            <a href="?category=Beverage" class="btn">View Drinks</a>
        </div>

    </div>

<?php else: ?>

    
    <h2><?= htmlspecialchars($category) ?> Menu</h2>

    <div class="menu-container">

        <?php if (!empty($menuItems)): ?>
            <?php foreach ($menuItems as $item): ?>
                <div class="menu-card">

                    <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>">

                    <h3><?= htmlspecialchars($item['name']) ?></h3>

                    <p><?= htmlspecialchars($item['description']) ?></p>

                    <p><strong>Price:</strong> <?= number_format($item['price'], 2) ?> DKK</p>

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>No items found in this category.</p>
        <?php endif; ?>

    </div>

<?php endif; ?>

</div>
</body>
</html>
