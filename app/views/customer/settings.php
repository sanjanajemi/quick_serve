<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';

use App\Core\Database;

$customerId = $_SESSION['customer_id'] ?? null;

if (!$customerId) {
    header("Location: /quick_serve/customer/login");
    exit;
}

$pdo = Database::connect();

/* -------- Fetch current customer data -------- */
$stmt = $pdo->prepare("SELECT name, email, avatar FROM customer WHERE customer_id = ?");
$stmt->execute([$customerId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name   = $user['name']   ?? "";
$email  = $user['email']  ?? "";
$avatar = $user['avatar'] ?? "default.jpg";

/* -------- Handle Update -------- */
if (isset($_POST['save_changes'])) {

    $newName  = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    $newPass  = $_POST['new_password'] ?? "";
    $oldPass  = $_POST['old_password'] ?? "";

    /* ------ Avatar upload handling ------ */
    if (!empty($_FILES['avatar']['name'])) {

        $file = $_FILES['avatar'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFilename = "avatar_" . $customerId . "_" . time() . "." . $ext;
        $uploadPath = __DIR__ . "/../../../storage/uploads/" . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // update avatar in DB
            $avatar = $newFilename;
            $pdo->prepare("UPDATE customer SET avatar = ? WHERE customer_id = ?")
                ->execute([$newFilename, $customerId]);
            $_SESSION['customer_avatar'] = $newFilename;
        }
    }

    /* ------ Update Name & Email ------ */
    $pdo->prepare("UPDATE customer SET name = ?, email = ? WHERE customer_id = ?")
        ->execute([$newName, $newEmail, $customerId]);

    $_SESSION['customer_name'] = $newName;


    if ($oldPass && $newPass) {

        $stmt = $pdo->prepare("SELECT password FROM customer WHERE customer_id = ?");
        $stmt->execute([$customerId]);
        $currentHash = $stmt->fetchColumn();

        if (password_verify($oldPass, $currentHash)) {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE customer SET password = ? WHERE customer_id = ?")
                ->execute([$newHash, $customerId]);
        }
    }

    // Update session with latest values
    $_SESSION['customer_name'] = $newName;
    if (!empty($avatar)) {
        $_SESSION['customer_avatar'] = $avatar;
    }

    $_SESSION['flash_message'] = "Your account settings have been updated!";

    // Redirect to dashboard
    header("Location: /quick_serve/app/views/customer/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/settings.css">
</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>

    <div class="global-bg"></div>
    <div class="global-bg-overlay"></div>

    <div class="settings-container">

        <h2>⚙️ Account Settings</h2>

        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="success-msg">
                <?= htmlspecialchars($_SESSION['flash_message']) ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="settings-form">

            <!-- PROFILE IMAGE -->
            <div class="avatar-section">
                <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($avatar) ?>"
                    class="avatar-preview"
                    alt="Profile Avatar">

                <label class="upload-btn">
                    Change Avatar
                    <input type="file" name="avatar" accept="image/*">
                </label>
            </div>
            <!-- NAME -->
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <!-- EMAIL -->
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <h3>🔐 Change Password (optional)</h3>

            <!-- OLD PASSWORD -->
            <label>Current Password</label>
            <input type="password" name="old_password">

            <!-- NEW PASSWORD -->
            <label>New Password</label>
            <input type="password" name="new_password">

            <button type="submit" name="save_changes" class="save-btn">Save Changes</button>
        </form>
    </div>

</body>

</html>