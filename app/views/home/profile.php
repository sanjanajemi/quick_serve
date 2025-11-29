<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      padding: 40px;
      text-align: center;
    }

    .profile-box {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 400px;
      margin: auto;
    }

    h2 {
      color: #00c9ff;
      margin-bottom: 20px;
    }

    p {
      font-size: 1.1rem;
      margin: 10px 0;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #00c9ff;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
    }

    a:hover {
      background-color: #009ecf;
    }
  </style>
</head>
<body>
  <div class="profile-box">
    <h2>My Profile</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($customer['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($customer['account_status']) ?></p>
    <a href="/quick_serve/home/dashboard">← Back to Dashboard</a>
  </div>
</body>
</html>