<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Admin Info</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/edit_profile.css" />
    
</head>
<body>
  <div class="form-card">
    <h2>Edit Admin Info</h2>
    <form method="POST" action="/quick_serve/admin/profile/update">
  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
  <input type="hidden" name="id" value="<?= htmlspecialchars($admin['admin_id']) ?>">

  <label for="name">Name</label>
  <input type="text" id="name" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>

  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

  <div class="btn-group">
    <button type="submit" class="btn">Save Changes</button>
    <a href="/quick_serve/admin/profile" class="btn gray">Cancel</a>
  </div>
</form>
  </div>

  <div class="back-button">
    <a href="/quick_serve/admin/profile">⬅ Back to profile</a>
  </div>

    <script src="/quick_serve/assets/js/admin/admin_edit.js"></script>
</body>
</html>