<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Staff Profile · QuickServe</title>
  <link rel="stylesheet" href="\quick_serve\assets\css\admin\staff_profile.css" />
  
</head>
<body>

  <div class="profile-container">
    <h2>Staff Profile</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($staff['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($staff['email']) ?></p>
    <p><strong>Phone:</strong> <?= $staff['phone'] ? htmlspecialchars($staff['phone']) : 'Not provided' ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($staff['role']) ?></p>

    <?php if (!empty($staff['profile_picture'])): ?>
      <p><strong>Picture:</strong></p>
      <img src="/uploads/<?= htmlspecialchars($staff['profile_picture']) ?>" alt="Profile Picture">
    <?php endif; ?>

    <a href="/quick_serve/admin/staff/list" class="back-link">← Back to Staff List</a>
  </div>

   <script src="\quick_serve\assets\js\admin\staff_profile.js"></script>
</body>
</html>