<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = $viewData['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brock Café — Create Account</title>

    
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">

    
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/signup.css">
</head>

<body>

<button class="go-back-btn" onclick="history.back()">← Go Back</button>

<div class="global-bg"></div>
<div class="global-bg-overlay"></div>

<div class="page-bg" style="background-image: url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>

<div class="center-container">

    <form class="signup-card" method="POST" action="/quick_serve/customer/signup/submit">

        <img src="/quick_serve/assets/images/logo/logo.png" class="auth-logo" alt="Logo">

        <h2 class="signup-title">Create Account</h2>

        <?php if ($error): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your full name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Create a password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm password" required>

        <label class="signup-consent">
            <input type="checkbox" name="consent" required>
            I consent to Brock Café storing my information to create and process my account.
        </label>

        <button type="submit" class="signup-btn">Register</button>

        <div class="signup-footer">
            Already have an account?
            <a href="/quick_serve/customer/login">Login here</a>
        </div>

    </form>

</div>

</body>
</html>
