<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
<link rel="stylesheet" href="\quick_serve\assets\css\admin\admin_password.css" />
</head>
<body>
  <main class="change-password-card">
    <h2>Change Password</h2>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach ($errors as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="success">
        <p><?= htmlspecialchars($success) ?></p>
      </div>
    <?php endif; ?>

    <form method="post" action="/quick_serve/admin/profile/password/update">
      <label for="current_password">Current Password</label>
      <input type="password" name="current_password" id="current_password" required>

      <label for="new_password">New Password</label>
      <input type="password" name="new_password" id="new_password" required>

      <label for="confirm_password">Confirm New Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required>

      <button type="submit" class="btn">Update Password</button>
    </form>

    <div class="button-container">
      <a href="/quick_serve/admin/profile" class="btn">Back to Profile</a>
    </div>
  </main>

   <script src="\quick_serve\assets\js\admin\admin_password.js"></script>
</body>
</body>
</html>