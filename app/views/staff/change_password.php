<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/change_password.css" />
</head>
<body>

  <div class="top-nav">
    <a href="/quick_serve/staff/dashboard" class="btn back-btn"> ⇦ </a>
  </div>

  <main class="change-password-card">
    <h2>Change Password</h2>

    <?php if (!empty($error)): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="/quick_serve/staff/change-password">
      <label for="current_password">Current Password</label>
      <input type="password" name="current_password" id="current_password" required>

      <label for="new_password">New Password</label>
      <input type="password" name="new_password" id="new_password" required>

      <label for="confirm_password">Confirm New Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required>

      <button type="submit" class="btn">Update Password</button>
    </form>
  </main>
  
<script src="/quick_serve/app/views/staff/change_password.js"></script></body>
</html>