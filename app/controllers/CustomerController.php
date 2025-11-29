<?php
namespace App\Controllers;

use App\Models\CustomerModel;
use App\Core\View;
use App\Core\Database;
use PDO;

class CustomerController
{
    /* LOGIN */
    public function loginForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $viewData = [];
        require_once __DIR__ . '/../views/customer/login.php';
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $redirect = $_POST['redirect'] ?? null;

        $customer = CustomerModel::findByEmail($email);

        if ($customer && password_verify($password, $customer['password_hash']) && $customer['account_status'] === 'active') {

            $_SESSION['customer_id']     = $customer['customer_id'];
            $_SESSION['customer_name']   = $customer['name'];
            $_SESSION['customer_avatar'] = $customer['avatar'] ?? 'default.png';

            if ($redirect) {
                header("Location: $redirect");
                exit;
            }

            header("Location: /quick_serve/customer/dashboard");
            exit;
        }

        $viewData['error'] = "Invalid email or password.";
        require_once __DIR__ . '/../views/customer/login.php';
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: /quick_serve");
        exit;
    }

    /*
       SIGNUP
   */
    public function signupForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/signup.php'; 
        // make sure your file is renamed to signup.php
    }

    public function signup()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $name     = $_POST['name'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm) {
            $viewData['error'] = "Passwords do not match.";
            require_once __DIR__ . '/../views/customer/signup.php';
            return;
        }

        if (CustomerModel::emailExists($email)) {
            $viewData['error'] = "Email already exists.";
            require_once __DIR__ . '/../views/customer/signup.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $created = CustomerModel::create($name, $email, $hash);

        if (!$created) {
            $viewData['error'] = "Registration failed.";
            require_once __DIR__ . '/../views/customer/signup.php';
            return;
        }

        $customer = CustomerModel::findByEmail($email);

        $_SESSION['customer_id']     = $customer['customer_id'];
        $_SESSION['customer_name']   = $customer['name'];
        $_SESSION['customer_avatar'] = 'default.png';

        header("Location: /quick_serve/customer/dashboard");
        exit;
    }

    /* 
       DASHBOARD
  */
    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['customer_id'])) {
            header("Location: /quick_serve/customer/login");
            exit;
        }
        require_once __DIR__ . '/../views/customer/dashboard.php';
    }

    /* 
       CATEGORY + MENU
  */
    public function chooseCategory()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/category.php';
    }

    public function menuPage()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/menu.php';
    }

    public function addToCart()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $id = (int)($_POST['menu_item_id'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 1);

    if ($id <= 0) {
        echo json_encode(["error" => "Invalid item"]);
        return;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $pdo = Database::connect();
    $stmt = $pdo->prepare("SELECT * FROM menu_item WHERE menu_item_id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        echo json_encode(["error" => "Item not found"]);
        return;
    }

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = [
            "menu_item_id" => $id,
            "name"         => $item["name"],
            "price"        => (float)$item["price"],
            "quantity"     => $qty
        ];
    } else {
        $_SESSION['cart'][$id]["quantity"] += $qty;
    }

    echo json_encode([
        "success" => true,
        "count" => array_sum(array_column($_SESSION['cart'], 'quantity'))
    ]);
}

public function addDealToCart()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $dealId = (int)($_POST['deal_id'] ?? 0);

    // HARD-CODED DEALS
    $deals = [
        9001 => [
            "name" => "Shawarma Combo Deal",
            "price" => 85.00
        ],
        9002 => [
            "name" => "Morning Brew Deal",
            "price" => 25.00
        ]
    ];

    if (!isset($deals[$dealId])) {
        echo json_encode(["error" => "Invalid deal"]);
        return;
    }

    $deal = $deals[$dealId];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['cart'][$dealId])) {
        $_SESSION['cart'][$dealId] = [
            "menu_item_id" => $dealId,
            "name" => $deal["name"],
            "price" => $deal["price"],
            "quantity" => 1
        ];
    } else {
        $_SESSION['cart'][$dealId]["quantity"]++;
    }

    echo json_encode([
        "success" => true,
        "count" => array_sum(array_column($_SESSION['cart'], 'quantity'))
    ]);
}



public function loadCart()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $cart = $_SESSION['cart'] ?? [];
    $html = "";
    $total = 0;

    if (empty($cart)) {
        echo json_encode([
            "html" => "<p>Your cart is empty.</p>",
            "total" => 0
        ]);
        return;
    }

    foreach ($cart as $item) {

        $id = $item['menu_item_id'];
        $name = htmlspecialchars($item['name']);
        $price = number_format($item['price'], 2);
        $qty = $item['quantity'];

        $lineTotal = $item['price'] * $qty;
        $total += $lineTotal;

        // IMPORTANT → use HEREDOC for clean output
        $html .= <<<HTML
        <div class="cart-page-item">
            <div class="info">
                <strong>{$name}</strong><br>
                {$price} kr × {$qty}
            </div>

            <div class="actions">
                <button class="qty-btn" onclick="updateCart({$id}, 'minus')">−</button>
                <button class="qty-btn" onclick="updateCart({$id}, 'plus')">+</button>
                <button class="delete-btn" onclick="removeFromCart({$id})">🗑</button>
            </div>
        </div>
        HTML;
    }
    

    echo json_encode([
        "html" => $html,
        "total" => $total
    ]);
}
public function updateCart()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $id = $_POST['menu_item_id'] ?? null;
    $action = $_POST['action'] ?? '';

    if (!$id || !isset($_SESSION['cart'][$id])) {
        echo json_encode(["error" => "Item not found"]);
        return;
    }

    if ($action === "plus") {
        $_SESSION['cart'][$id]['quantity']++;
    }

    if ($action === "minus") {
        $_SESSION['cart'][$id]['quantity']--;
        if ($_SESSION['cart'][$id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    echo json_encode(["success" => true]);
}
public function removeFromCart()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $id = $_POST['menu_item_id'] ?? null;

    if ($id && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    echo json_encode(["success" => true]);
}
    /* 
       CHECKOUT
    */
    public function orderStart()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['customer_id'])) {
            header("Location: /quick_serve/customer/login?redirect=/quick_serve/customer/order_start");
            exit;
        }

        require_once __DIR__ . '/../views/customer/order_start.php';
    }

    public function orderConfirm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['customer_id'])) {
            header("Location: /quick_serve/customer/login?redirect=/quick_serve/customer/order_start");
            exit;
        }

        $customerId = $_SESSION['customer_id'];
        $cart       = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            header("Location: /quick_serve/customer/cart");
            exit;
        }

        $pdo = Database::connect();

        $total = 0;
        foreach ($cart as $c) {
            $total += $c['price'] * $c['quantity'];
        }

   $stmt = $pdo->prepare("
    INSERT INTO `order` (customer_id, placed_at, final_amount, status)
    VALUES (?, NOW(), ?, NULL)
");
$stmt->execute([$customerId, $total]);

        $orderId = $pdo->lastInsertId();

        $stmtItem = $pdo->prepare("
            INSERT INTO order_item (order_id, menu_item_id, unit_price, total_price, quantity)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($cart as $c) {
            $lineTotal = $c['price'] * $c['quantity'];
            $stmtItem->execute([
                $orderId,
                $c['menu_item_id'],
                $c['price'],
                $lineTotal,
                $c['quantity']
            ]);
        }

        unset($_SESSION['cart']);

        header("Location: /quick_serve/customer/receipt?order_id=$orderId");
        exit;
    }

public function checkout()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    View::render('customer.order_start', [
        'cart'  => $cart,
        'total' => $total
    ]);
}
    /* 
       ORDER HISTORY + RECEIPT
    */
    public function orderHistory()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/order_history.php';
    }

    public function receiptPage()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/receipt.php';
    }

    public function downloadReceipt()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $orderId = $_GET['order_id'] ?? null;
    if (!$orderId) {
        die("Invalid receipt request");
    }

    $pdo = Database::connect();
    $stmt = $pdo->prepare("
        SELECT o.*, c.name AS customer_name
        FROM `order` o
        JOIN customer c ON o.customer_id = c.customer_id
        WHERE o.order_id = ?
        LIMIT 1
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Order not found");
    }

    
    $html = "
    <html>
    <head>
        <style>
            body { font-family: Arial; }
            h2 { text-align: center; }
            .line { border-bottom: 1px solid #000; margin: 10px 0; }
        </style>
    </head>
    <body>
        <h2>Receipt #{$orderId}</h2>
        <div class='line'></div>
        <p><strong>Name:</strong> {$order['customer_name']}</p>
        <p><strong>Date:</strong> {$order['placed_at']}</p>
        <p><strong>Total Amount:</strong> {$order['final_amount']} DKK</p>
        <div class='line'></div>
        <p>Thank you for your order at Brock Café!</p>
    </body>
    </html>
    ";

    
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=receipt_$orderId.pdf");

    echo $html;
    exit;
}
public function currentOrderStatus()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $customerId = $_SESSION['customer_id'] ?? null;
    if (!$customerId) {
        echo json_encode(["order" => null]);
        exit;
    }

    $pdo = Database::connect();
    $stmt = $pdo->prepare("
        SELECT order_id, placed_at, final_amount, status
        FROM `order`
        WHERE customer_id = ?
        ORDER BY order_id DESC
        LIMIT 1
    ");
    $stmt->execute([$customerId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "order" => $order ?: null
    ]);
    exit;
}
public function trackOrderPage()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    // Must be logged in
    if (!isset($_SESSION['customer_id'])) {
        header("Location: /quick_serve/customer/login");
        exit;
    }

    require __DIR__ . '/../views/customer/order_tracking.php';
}



 /* 
       SETTINGS
  */
    public function settingsPage()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/settings.php';
    }

    public function update_profile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE customer SET name=?, email=?, address=? WHERE customer_id=?");
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['address'],
            $_SESSION['customer_id']
        ]);

        $_SESSION['customer_name'] = $_POST['name'];

        header("Location: /quick_serve/customer/settings");
        exit;
    }

    public function change_password()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $pdo = Database::connect();
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new !== $confirm) {
            $_SESSION['flash_message'] = "New passwords do not match.";
            header("Location: /quick_serve/customer/settings");
            exit;
        }

        $stmt = $pdo->prepare("SELECT password_hash FROM customer WHERE customer_id=?");
        $stmt->execute([$_SESSION['customer_id']]);
        $stored = $stmt->fetchColumn();

        if (!password_verify($current, $stored)) {
            $_SESSION['flash_message'] = "Incorrect current password.";
            header("Location: /quick_serve/customer/settings");
            exit;
        }

        $hash = password_hash($new, PASSWORD_DEFAULT);

        $pdo->prepare("UPDATE customer SET password_hash=? WHERE customer_id=?")
            ->execute([$hash, $_SESSION['customer_id']]);

        header("Location: /quick_serve/customer/settings");
        exit;
    }

    /*
       AVATAR UPDATE
  */
    public function updateAvatar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_FILES['customer_profile_picture'])) {
            header("Location: /quick_serve/customer/settings");
            exit;
        }

        $file = $_FILES['customer_profile_picture'];
        $allowed = ['image/png', 'image/jpg', 'image/jpeg'];

        if (!in_array($file['type'], $allowed)) {
            $_SESSION['flash_message'] = "Only JPG & PNG allowed.";
            header("Location: /quick_serve/customer/settings");
            exit;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = "avatar_" . $_SESSION['customer_id'] . "_" . time() . "." . $ext;

        $uploadDir = __DIR__ . '/../../storage/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        move_uploaded_file($file['tmp_name'], $uploadDir . $newName);

        $pdo = Database::connect();
        $pdo->prepare("UPDATE customer SET avatar=? WHERE customer_id=?")
            ->execute([$newName, $_SESSION['customer_id']]);

        $_SESSION['customer_avatar'] = $newName;

        header("Location: /quick_serve/customer/settings");
        exit;
    }

    /*
       DELETE ACCOUNT (FINAL VERSION B)
     */
    public function delete_account()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['customer_id'])) {
            $_SESSION['flash_message'] = "Please login first.";
            $_SESSION['flash_type'] = "error";
            header("Location: /quick_serve/customer/login");
            exit;
        }

        require_once __DIR__ . '/../views/customer/delete_account.php';
    }

    public function confirm_delete_account()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['customer_id'])) {
            header("Location: /quick_serve/customer/login");
            exit;
        }

        $customerId = $_SESSION['customer_id'];
        $password   = $_POST['password'] ?? '';

        $pdo = Database::connect();

        $stmt = $pdo->prepare("SELECT password_hash, avatar FROM customer WHERE customer_id=? LIMIT 1");
        $stmt->execute([$customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer || !password_verify($password, $customer['password_hash'])) {
            $_SESSION['flash_message'] = "Incorrect password. Try again.";
            $_SESSION['flash_type'] = "error";
            header("Location: /quick_serve/customer/delete_account");
            exit;
        }

        $pdo->prepare("
            DELETE FROM order_item
            WHERE order_id IN (SELECT order_id FROM `order` WHERE customer_id=?)
        ")->execute([$customerId]);

        $pdo->prepare("DELETE FROM `order` WHERE customer_id=?")->execute([$customerId]);

        if (!empty($customer['avatar']) && $customer['avatar'] !== 'default.png') {
            $path = __DIR__ . '/../../storage/uploads/' . $customer['avatar'];
            if (file_exists($path)) unlink($path);
        }

        $pdo->prepare("DELETE FROM customer WHERE customer_id=?")->execute([$customerId]);

        session_unset();
        session_destroy();

        header("Location: /quick_serve/customer/login?deleted=1");
        exit;
    }

    /*
       FEEDBACK
    */
    public function feedbackForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/customer/feedback.php';
    }

    public function submitFeedback()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $customerId = $_SESSION['customer_id'];
        $name       = $_SESSION['customer_name'];
        $msg        = trim($_POST['message']);
        $rating     = $_POST['rating'];

        if ($msg === '') {
            header("Location: /quick_serve/customer/feedback?error=1");
            exit;
        }

        $dir = __DIR__ . '/../../storage/feedback';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $line = json_encode([
            'time'         => date('Y-m-d H:i:s'),
            'customer_id'  => $customerId,
            'name'         => $name,
            'rating'       => $rating,
            'message'      => $msg
        ]) . PHP_EOL;

        file_put_contents($dir . '/feedback.log', $line, FILE_APPEND);

        header("Location: /quick_serve/customer/feedback?success=1");
        exit;
    }
}
?>
