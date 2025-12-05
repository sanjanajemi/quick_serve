<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: /quick_serve/customer/login");
    exit;
}

$customerName   = $_SESSION['customer_name'] ?? "";
$customerEmail  = $_SESSION['customer_email'] ?? "";
$customerAvatar = $_SESSION['customer_avatar'] ?? "default.png";
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
        <div class="<?= $_SESSION['flash_type'] ?? 'flash' ?>">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    
    <form method="POST" action="/quick_serve/customer/settings/avatar" enctype="multipart/form-data" class="settings-form">

        <div class="avatar-section">
            <img src="/quick_serve/storage/uploads/<?= htmlspecialchars($customerAvatar) ?>"
                 class="avatar-preview">

            <label class="upload-btn">
                Change Avatar
                <input type="file" name="customer_profile_picture" accept="image/*">
            </label>
        </div>

        <button type="submit" class="save-btn">Update Avatar</button>
    </form>


    
    <form method="POST" action="/quick_serve/customer/settings/update" class="settings-form">

        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($customerName) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($customerEmail) ?>" required>

        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($_SESSION['customer_address'] ?? '') ?>">

        <button type="submit" class="save-btn">Save Profile</button>
    </form>


    
    <form method="POST" action="/quick_serve/customer/settings/password" class="settings-form">

        <h3>🔐 Change Password</h3>

        <label>Current Password</label>
        <input type="password" name="current_password">

        <label>New Password</label>
        <input type="password" name="new_password">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password">

        <button type="submit" class="save-btn">Change Password</button>
    </form>

</div>

</body>
</html>
