<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = $viewData['error'] ?? null;


$redirect = $_GET['redirect'] ?? ($_POST['redirect'] ?? '/quick_serve/customer/dashboard');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brock Café — Login</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/customer/global.css">
</head>

<body>
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>
<div class="global-bg"></div>
<div class="global-bg-overlay"></div>


<?php if (!empty($_SESSION['flash_message'])): ?>
    <div id="flashToast" class="flash-toast <?= $_SESSION['flash_type'] ?? '' ?>">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<script>
setTimeout(() => {
    const toast = document.getElementById("flashToast");
    if (toast) toast.classList.add("show");
    setTimeout(() => toast?.classList.remove("show"), 3000);
}, 100);
</script>


<div class="page-bg" style="background-image: url('/quick_serve/assets/images/customer/hero1.png');"></div>
<div class="page-bg-overlay"></div>


<div class="center-container">

    <form class="auth-card" method="POST" action="/quick_serve/customer/login/submit">


        
        <img
            src="/quick_serve/assets/images/logo/logo.png"
            class="auth-logo"
            alt="Brock Café Logo"
        >

        <h2 class="auth-title">Customer Login</h2>

        
        <?php if ($error): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit" class="btn-primary">Login</button>

        <div class="auth-footer">
            Don't have an account?
            <a href="/quick_serve/customer/signup">Register here</a>


        </div>

    </form>

</div>
</body>
</html>
