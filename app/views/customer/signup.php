<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Customer Account</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #ffecd2, #fcb69f);
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    main {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
      width: 100%;
      max-width: 420px;
    }

    h2 {
      color: #f77f00;
      margin-bottom: 20px;
    }

    label {
      display: block;
      text-align: left;
      margin-top: 10px;
      font-size: 0.95rem;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    .consent {
      margin: 15px 0;
      text-align: left;
      font-size: 0.9rem;
      color: #555;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #f77f00;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 10px;
    }

    button:hover {
      background-color: #d66a00;
    }

    .error {
      color: red;
      margin-bottom: 15px;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      color: #f77f00;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <button class="go-back-btn" onclick="history.back()">← Go Back</button>
  <div class="global-bg"></div>
<div class="global-bg-overlay"></div>
  <main>
    <h2>Create Account</h2>

    <?php if (!empty($viewData['error'])): ?>
      <p class="error"><?= htmlspecialchars($viewData['error']) ?></p>
    <?php endif; ?>

    <form method="POST" action="/quick_serve/customer/signup/submit">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Full Name" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="Email address" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Password" required>

      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>

      <!-- Consent checkbox -->
      <div class="consent">
  <p>We value your trust. Your details will only be used to create your account and process orders.</p>
  <label for="consent">
    <input type="checkbox" id="consent" name="consent" required>
    I consent to this use.
  </label>
</div>

      <button type="submit">Register</button>
    </form>

    <a href="/quick_serve">← Back to Home</a>
  </main>
</body>
</html>