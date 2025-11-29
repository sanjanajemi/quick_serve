<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account Created</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #e6fff2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .success-box {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 400px;
    }

    h2 {
      color: #28a745;
    }

    p {
      font-size: 1.1rem;
      margin: 20px 0;
    }

    a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #28a745;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }

    a:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <button class="go-back-btn" onclick="history.back()">← Go Back</button>
  <div class="success-box">
    <h2>Account Created Successfully!</h2>
    <p>Welcome, <?= htmlspecialchars($viewData['name']) ?>. Your account has been created.</p>
    <a href="/quick_serve/customer/dashboard">Go to Dashboard</a>
  </div>
</body>
</html>