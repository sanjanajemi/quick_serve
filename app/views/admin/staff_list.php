<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Staff</title>
  <link rel="stylesheet" href="\quick_serve\assets\css\admin\staff_list.css?v=17" />
  
</head>
<body>

  <h2>Staff Members</h2>

  <!-- Navigation Buttons -->
  <a href="/quick_serve/admin/dashboard" class="button alt-button"> Back to Dashboard</a>
  <a href="/quick_serve/admin/staff/add" class="button"> Add New Staff</a>

  <?php if (!empty($staff)): ?>
    <table>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($staff as $member): ?>
        <tr>
          <td><?= htmlspecialchars($member['name']) ?></td>
          <td><?= htmlspecialchars($member['email']) ?></td>
          <td><?= htmlspecialchars($member['role']) ?></td>
          <td><?= htmlspecialchars($member['phone']) ?></td>
          <td>
            <a href="/quick_serve/admin/staff/view?id=<?= urlencode($member['staff_id']) ?>"> View</a>
            <a href="/quick_serve/admin/staff/edit?id=<?= urlencode($member['staff_id']) ?>"> Edit</a>
            <a href="/quick_serve/admin/staff/delete?id=<?= urlencode($member['staff_id']) ?>" onclick="return confirm('Delete this staff member?')"> Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No staff found.</p>
  <?php endif; ?>

    <script src="\quick_serve\assets\js\admin\staff_list.js"></script>
</body>
</html>