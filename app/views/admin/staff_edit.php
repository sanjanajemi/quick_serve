<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/staff_edit.css" />
</head>

<body>

  <form action="/quick_serve/admin/staff/update" method="POST" enctype="multipart/form-data">
    <h2>Edit Profile</h2>
 <input type="hidden" name="id" value="<?= isset($staff['staff_id']) ? htmlspecialchars($staff['staff_id']) : '' ?>">
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required>

    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>

    <label for="phone">Phone Number</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['phone'] ?? ''); ?>">

    <label for="profile_picture">Profile Picture</label>
    <input type="file" id="profile_picture" name="profile_picture">
    <?php if (!empty($staff['profile_picture'])): ?>
      <img src="/quick_serve/storage/uploads/<?php echo $staff['profile_picture']; ?>" alt="Profile Picture">
    <?php endif; ?>

    <label for="role">Role</label>
    <select id="role" name="role" required>
      <option value="Manager" <?php if ($staff['role'] === 'Manager') echo 'selected'; ?>>Manager</option>
      <option value="Chef" <?php if ($staff['role'] === 'Chef') echo 'selected'; ?>>Chef</option>
      <option value="Waiter" <?php if ($staff['role'] === 'Waiter') echo 'selected'; ?>>Waiter</option>
    </select>

<div style="display: flex; gap: 10px;">
  <button type="submit">Save Changes</button>
  <a href="/quick_serve/admin/staff/list" class="cancel-button">Cancel</a>
</div>
  </form>

  <script src="\quick_serve\assets\js\admin\staff_edit.js"></script>
</body>

</html>