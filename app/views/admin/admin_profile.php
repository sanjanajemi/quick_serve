<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/admin_profile.css" />
</head>
<body>
  <div class="profile-card">
    <h2>Admin Profile</h2>

    <?php if (!empty($success)): ?>
      <div class="success-box">
        <p><?= htmlspecialchars($success) ?></p>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="info-block">
      <?php if (is_array($admin)): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
        <p><strong>ID:</strong> <?= htmlspecialchars($admin['admin_id']) ?></p>
        <p><strong>Status:</strong> <span class="status-badge">Active</span></p>
      <?php else: ?>
        <p><strong>Name:</strong> Not found</p>
        <p><strong>Email:</strong> Not found</p>
        <p><strong>ID:</strong> <?= htmlspecialchars($_SESSION['admin_id'] ?? 'Unknown') ?></p>
        <p><strong>Status:</strong> <span class="status-badge">Unknown</span></p>
      <?php endif; ?>
    </div>

    <div class="button-group">
      <a href="/quick_serve/admin/profile/edit" class="btn">Edit Info</a>
      <a href="/quick_serve/admin/profile/password" class="btn">Change Password</a>

      <!-- ✅ Updated Delete Account flow -->
      <form method="POST" action="/quick_serve/admin/profile/delete">
        <button type="submit" class="btn red">Delete Account</button>
      </form>

      <button class="btn teal" onclick="toggleAdminForm()">Add New Admin</button>
      <button class="btn gray" onclick="toggleMode()">Toggle Mode</button>

      <form method="POST" action="/quick_serve/admin/logout">
        <button type="submit" class="btn red">Logout</button>
      </form>
    </div>
  </div>

  <div class="admin-create-wrapper" id="adminForm" style="display: none;">
    <h3 style="color: #00bcd4;">Create New Admin</h3>

    <form method="POST" action="/quick_serve/admin/create" class="admin-create-form">
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit" class="btn teal">Create Admin</button>
    </form>
  </div>

  <div class="back-button">
    <a href="/quick_serve/admin/dashboard">⬅ Back to Dashboard</a>
  </div>

  <script>
    function toggleMode() {
      document.body.classList.toggle('light-mode');
    }

    function toggleAdminForm() {
      const form = document.getElementById('adminForm');
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
      if (form.style.display === 'block') {
        window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });
      }
    }

    <?php if (!empty($success)): ?>
      document.getElementById('adminForm').style.display = 'block';
    <?php endif; ?>
  </script>

    <script src="\quick_serve\assets\js\admin\admin_profile.js"></script>
</body>
</html>