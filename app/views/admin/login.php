<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="\quick_serve\assets\css\admin\login.css">

</head>
<body>
  <main>
    <h2>Admin Login</h2>

    <?php if (!empty($viewData['error'])): ?>
      <p class="error"><?= htmlspecialchars($viewData['error']) ?></p>
    <?php endif; ?>

    <form method="POST" action="/quick_serve/admin/login">
      <input type="text" name="admin_id" placeholder="Enter Admin ID" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <a href="/quick_serve">← Back to Home</a>
  </main>
</body>
</html>