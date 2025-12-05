<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Staff · QuickServe</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/staff_add.css?v=2" />
</head>
<body>
  <h2>Add Staff</h2>

<form method="POST" action="/quick_serve/admin/staff/create" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone">

    <select name="role" required>
      <option value="manager">Manager</option>
      <option value="staff">Staff</option>
      <option value="waiter">Waiter</option>
    </select>

    <input type="password" name="password" placeholder="Password" required>
    <input type="file" name="profile_picture">
    <button type="submit">Add Staff</button>
    
  </form>
    <a href="/quick_serve/admin/staff/list" class="back-link">← Back to Staff List</a>
   
   <script src="\quick_serve\assets\js\admin\staff_add.js"></script>
</body>
</html>