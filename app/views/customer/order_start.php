<?php
$cart  = $viewData['cart']  ?? [];
$total = $viewData['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout · Brock Café</title>

    
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/order_start.css">
</head>
<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>

    
    <div class="global-bg"></div>
    <div class="global-bg-overlay"></div>

    
    <div class="content">

        
        <div class="hero-header">
            <h1>Checkout</h1>
        </div>

        <div class="checkout-container">

            <h2>Order Summary</h2>

            <div class="summary-box">

                <h3>Your Items</h3>

                <?php if (!empty($cart)): ?>
                    <?php foreach ($cart as $item): ?>
                        <div class="item-row">
                            <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                            <?= number_format($item['price'], 2) ?> kr × <?= $item['quantity'] ?><br>
                            <span class="subtotal">
                                Subtotal: <?= number_format($item['price'] * $item['quantity'], 2) ?> kr
                            </span>
                            <hr>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>

                <div class="total-row">
                    <strong>Total: <?= number_format($total, 2) ?> kr</strong>
                </div>

                <form method="POST" action="/quick_serve/customer/order_confirm">
                    <textarea name="comments" placeholder="Order comments? Extra sauce? No onions?..."></textarea>
                    <button class="confirm-btn" type="submit">Confirm Order</button>
                </form>

            </div>
        </div>

    </div>
</body>
</html>