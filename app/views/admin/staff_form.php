<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= isset($staff) ? 'Edit Staff' : 'Add Staff' ?> · QuickServe</title>
    <link rel="stylesheet" href=" \quick_serve\assets\css\admin\staff_from.css "/> 
</head>
<body>

  <h2><?= isset($staff) ? 'Edit Staff' : 'Add Staff' ?></h2>

  <form method="POST" class="staff-form" action="<?= isset($staff) ? '/admin/staff/update?id=' . $staff['staff_id'] : '/admin/staff/create' ?>" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= $staff['name'] ?? '' ?>" placeholder="Name" required>
    <input type="email" name="email" value="<?= $staff['email'] ?? '' ?>" placeholder="Email" required>
    <input type="text" name="phone" value="<?= $staff['phone'] ?? '' ?>" placeholder="Phone">

    <select name="role" required>
      <option value="admin" <?= isset($staff) && $staff['role'] === 'admin' ? 'selected' : '' ?>>Manager</option>
      <option value="staff" <?= isset($staff) && $staff['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
      <option value="waiter" <?= isset($staff) && $staff['role'] === 'waiter' ? 'selected' : '' ?>>Waiter</option>
    </select>

    <?php if (!isset($staff)): ?>
      <input type="password" name="password" placeholder="Password" required>
    <?php endif; ?>

    <input type="file" name="profile_picture">
    <button type="submit"><?= isset($staff) ? 'Update' : 'Add' ?> Staff</button>
    <a href="/quick_serve/admin/staff/list" class="button">← Back to Staff List</a>
  </form>

</body>
</html>