<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/view_profile.css" />
</head>
<body>
  <div class="top-nav">
    <a href="/quick_serve/staff/dashboard" class="btn back-btn">←</a>
  </div>
  <main class="profile-card">
    <h2>My Profile</h2>
    <div class="profile-image">
      <?php if (!empty($staff['profile_picture'])): ?>
        <img src="/quick_serve/storage/uploads/<?php echo $staff['profile_picture']; ?>" alt="Profile Picture" class="profile-pic">
      <?php endif; ?>
    </div>
    <ul class="profile-details">
      <li><strong>Name:</strong> <?php echo htmlspecialchars($staff['name']); ?></li>
      <li><strong>Email:</strong> <?php echo htmlspecialchars($staff['email']); ?></li>
      <li><strong>Phone:</strong> <?php echo htmlspecialchars($staff['phone']); ?></li>
      <li><strong>Role:</strong> <?php echo htmlspecialchars($staff['role']); ?></li>
      <li><strong>ID:</strong> <?php echo $_SESSION['staff_id']; ?></li>
    </ul>
    <div class="button-container">
      <a href="/quick_serve/staff/dashboard" class="btn"><-Back to Dashboard</a>
    </div>
  </main>
  <script src="\quick_serve\assets\js\staff\view_profile.js"></script>
</body>
</html>