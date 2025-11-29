<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>404 - Page Not Found</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #667eea, #f1c593ff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #edecf5ff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      flex-direction: column;
      text-align: center;
    }

    .error-code {
      font-size: 8rem;
      font-weight: bold;
      animation: pulse 1.5s infinite;
    }

    .message {
      font-size: 1.5rem;
      margin-bottom: 2rem;
    }
a {
  display: inline-block;
  padding: 12px 24px;
  background-color: #ffffff;
  color: #764ba2;
  text-decoration: none;
  border-radius: 8px;
  font-weight: bold;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
}

a:hover {
  background-color: #764ba2;
  color: #ffffff;
  transform: translateY(-2px);
}


    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
  </style>
</head>
<body>
  <div class="error-code">404</div>
  <div class="message">Oops! The page you're looking for doesn't exist.</div>
<a href="/quick_serve/home/dashboard">Go back to home</a>
</body>
</html>