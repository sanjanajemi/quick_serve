<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';
use App\Core\Database;
$pdo = Database::connect();

$customerId = $_SESSION['customer_id'] ?? null;
$customerName = $_SESSION['customer_name'] ?? 'Guest';

if (!$customerId) {
    header('Location: /quick_serve/customer/login');
    exit;
}

$message = '';

// Get current customer info
$stmt = $pdo->prepare("SELECT name, email FROM customer WHERE customer_id = ?");
$stmt->execute([$customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $updates = [];
    $params = [];

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate & update name
    if (!empty($name)) {
        if (strlen($name) < 2) {
            $errors[] = "Name must be at least 2 characters.";
        } else {
            $updates[] = "name = ?";
            $params[] = $name;
            $_SESSION['customer_name'] = $name;
        }
    }

    // Validate & update email
    if (!empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } else {
            $updates[] = "email = ?";
            $params[] = $email;
        }
    }

    // Validate & update password
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        } else {
            $updates[] = "password_hash = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    if (empty($updates)) {
        $errors[] = "Please fill at least one field.";
    }

    if (empty($errors)) {
        $query = "UPDATE customer SET " . implode(", ", $updates) . " WHERE customer_id = ?";
        $params[] = $customerId;

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $message = "✅ Profile updated successfully!";
        } catch (PDOException $e) {
            $message = "❌ Error updating profile.";
        }
    } else {
        $message = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Details</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/update_details.css">
</head>

<body class="update-body">
<div class="update-container">
    <h2>✏️ Update Your Details</h2>

    <?php if (!empty($message)): ?>
        <p class="form-message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" class="update-form" id="updateForm">

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>">

        <label>New Password</label>
        <input type="password" name="password">

        <button type="submit">Update Details</button>
    </form>
</div>

<script>
document.getElementById('updateForm').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value.trim();

    let errors = [];

    if (!name && !email && !password) {
        errors.push("Please fill at least one field.");
    }

    if (email !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.push("Invalid email format.");
    }

    if (password !== "" && password.length < 6) {
        errors.push("Password must be at least 6 characters.");
    }

    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>

</body>
</html>
