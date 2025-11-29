<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Login</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/login.css">
</head>
<body>
 
    <a href="/quick_serve" class="btn top-left">← </a>


  <main>

    <h2>Staff Login</h2>
    
<?php if (!empty($error)): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

    <form id="staffLoginForm" method="POST" action="/quick_serve/staff/login">
      <input type="text" id="staff_id" name="staff_id" placeholder="Enter 6-digit Staff ID" pattern="\d{6}" required>
      <input type="password" id="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <a href="/quick_serve">← Back to Home</a>
  </main>

<script src="/quick_serve/assets/js/common/login_validation.js"></script>
</body>
</html>