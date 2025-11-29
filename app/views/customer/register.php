<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;

$pdo = Database::connect();

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT customer_id FROM customer WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            // Insert new customer
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO customer (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);

            $success = "Account created successfully! Please log in.";
            // Optionally redirect to login:
            // header("Location: /quick_serve/customer/login.php");
            // exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brock Café — Create Account</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
</head>

<body>
<div class="page-bg" style="background-image: url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>

<div class="center-container">
    <form class="auth-card" method="POST" action="">
        <img src="/quick_serve/assets/images/logo/logo.png" class="auth-logo" alt="Brock Café Logo">
        <h2 class="auth-title">Create an Account</h2>

        <?php if ($error): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-box"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your full name" required>

        <label>Email Address</label>
        <input type="email" name="email" placeholder="example@domain.com" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Re-enter your password" required>

        <button type="submit" class="btn-primary">Create Account</button>

        <div class="auth-footer">
            Already have an account?
            <a href="/quick_serve/customer/login.php">Login here</a>
        </div>
    </form>
</div>
</body>
</html>
