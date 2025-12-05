<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart · Brock Café</title>

    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/menu.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/cart.css">
</head>

<body>
<button class="go-back-btn" onclick="history.back()">← Go Back</button>
<div class="global-bg"></div>
<div class="global-bg-overlay"></div>

<div class="page-content">

    <h2>Your Cart</h2>

    

    
    <div id="cart-container"></div>

</div>

<script>
function loadCart() {
    fetch("/quick_serve/customer/cart/items")
        .then(res => res.json())
        .then(data => {

            document.getElementById("cart-container").innerHTML =
                data.html +
                `<div class="cart-page-total">
                    <strong>Total:</strong> ${data.total.toFixed(2)} kr
                </div>
                <br>
                <a href="/quick_serve/customer/order_start" class="checkout-btn">
                    Proceed to Checkout
                </a>`;
        });
}
function updateCart(id, action) {
    fetch("/quick_serve/customer/cart/update", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `menu_item_id=${id}&action=${action}`
    })
    .then(() => loadCart());
}

function removeFromCart(id) {
    fetch("/quick_serve/customer/cart/remove", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `menu_item_id=${id}`
    })
    .then(() => loadCart());
}






loadCart();
</script>

</body>
</html>
