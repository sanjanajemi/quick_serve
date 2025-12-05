<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Staff Dashboard</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/staff_dashboard.css" />
</head>

<body>
  <header>
    <div class="brand">
    <img src= "\quick_serve\assets\images\staff\staff.jpg" alt="Brock Café Logo" class="brand-logo">
    <h1 style="color: #f5f7fa;">Staff Dashboard</h1>
    </div>
    
    <nav>
      <button onclick="toggleDarkMode()" class="toggle-btn">Toggle Dark Mode</button>
      <a href="/quick_serve/staff/logout">Logout</a>
    </nav>
  </header>

  <!-- Quick Menu Navigation Bar -->
  <nav class="quick-nav-bar">
    <a href="/quick_serve/staff/edit-profile">Edit Profile</a>
    <a href="/quick_serve/staff/change-password">Change Password</a>
    <a href="/quick_serve/staff/delete-account" class="danger">Delete Account</a>

  </nav>

  <main>
    <div class="welcome-banner">
      <?php if (!empty($staff['profile_picture'])): ?>
        <img src="/quick_serve/storage/uploads/<?php echo $staff['profile_picture']; ?>" alt="Profile Picture" class="welcome-pic">
      <?php endif; ?>
      <div class="welcome-text">
        <h2>Welcome <?php echo htmlspecialchars($staff['name']); ?> ! </h2>
        <p><strong>Role:</strong><?php echo htmlspecialchars($staff['role']); ?></p>
      </div>
    </div>

    <!-- //View Profile Button  -->

    <div style="text-align: center; margin-top: 10px;">
      <a href="/quick_serve/staff/view-profile" class="btn">View Profile</a>
    </div>

    <!-- Work Tools Section -->
    <div class="section-title">Work Tools</div>
    <div class="grid">
      <div class="card">
        <h2>Kitchen View</h2>
        <p>Monitor current orders, preparation status, and kitchen alerts.</p>
        <a href="/quick_serve/staff/kitchen" class="btn">Go to Kitchen</a>
      </div>

      <div class="card">
        <h2>Order History & Restore</h2>
        <p>Browse all orders + Restore cleared orders.</p>
        <a href="/quick_serve/staff/order-history" class="btn">View History</a>
      </div>

      <div class="card">
        <h2>Manage Menu</h2>
        <p>Show or hide menu items for customers.</p>
        <a href="/quick_serve/staff/menu" class="btn">Manage Menu</a>
      </div>

      <div class="card">
        <h2>Cancel Order</h2>
        <p>Cancel active orders that are no longer needed.</p>
        <a href="/quick_serve/staff/cancel-order" class="btn danger">Cancel Order</a>
      </div>

      <div class="card">
        <h2>Add Order</h2>
        <p>Create a new order by selecting menu items and customer details.</p>
        <a href="/quick_serve/staff/add-order" class="btn">Add Order</a>
      </div>
    </div>

  </main>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
    }

    function toggleMenu() {
      document.getElementById('sidebarMenu').classList.toggle('hidden');
    }
  </script>
</body>

</html>