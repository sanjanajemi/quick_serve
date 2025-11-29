<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/edit_profile.css" />
</head>

<body>

  <div class="top-nav">
    <a href="/quick_serve/staff/dashboard" class="btn back-btn">⇦</a>
  </div>
  <?php if (!empty($error)): ?>
  <div class="error-message">
    <?= $error ?>
  </div>
<?php endif; ?>

  <form action="/quick_serve/staff/update-profile" method="POST" enctype="multipart/form-data" class="edit-profile-card">
    <h2>Edit Profile</h2>

    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required>

    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>

    <label for="phone">Phone Number</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['phone'] ?? ''); ?>">

    <label for="profile_picture">Profile Picture</label>
    <input type="file" id="profile_picture" name="profile_picture">
    <?php if (!empty($staff['profile_picture'])): ?>
      <img src="/quick_serve/storage/uploads/<?php echo $staff['profile_picture']; ?>" alt="Profile Picture" class="profile-img">
    <?php endif; ?>

    <label for="role">Role</label>
    <select id="role" name="role" required>
      <option value="Manager" <?php if ($staff['role'] === 'Manager') echo 'selected'; ?>>Manager</option>
      <option value="Chef" <?php if ($staff['role'] === 'Chef') echo 'selected'; ?>>Chef</option>
      <option value="Waiter" <?php if ($staff['role'] === 'Waiter') echo 'selected'; ?>>Waiter</option>
    </select>

    <div class="form-actions">
      <button type="submit" class="btn">Save Changes</button>
      <a href="/quick_serve/staff/dashboard" class="btn cancel-btn">Cancel</a>
    </div>

    <div class="card-footer">
      <a href="/quick_serve/staff/dashboard" class="btn footer-btn">← Back to Dashboard</a>
    </div>
  </form>

  <script src="/quick_serve/assets/js/staff/kitchen_view_validation.js"></script>
</body>

</html>