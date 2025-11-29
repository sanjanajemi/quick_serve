<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Access Denied</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #ff4e50, #f9d423);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      text-align: center;
      animation: fadeIn 1s ease-in-out;
    }

    h1 {
      font-size: 4rem;
      margin-bottom: 0.5rem;
    }

    p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }

    a {
      display: inline-block;
      padding: 12px 24px;
      background: #fff;
      color: #ff4e50;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background 0.3s, color 0.3s;
    }

    a:hover {
      background: #ff4e50;
      color: #fff;
    }

    .lock-icon {
      font-size: 5rem;
      margin-bottom: 1rem;
      animation: bounce 1.5s infinite;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="lock-icon">🔒</div>
    <h1>Access Denied</h1>
    <p>You do not have permission to view this page.</p>
    <a href="/home">Return to Home</a>
  </div>
</body>
</html>