<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>QuickServe · Home</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/home/home.css" />
</head>

<body>
  <header class="dashboard-header">
    <div class="header-top">
      <h1 style="font-family: Georgia, 'Times New Roman', Times, serif;"> Brock Cafe </h1>

      <nav class="login-nav">
        <a href="/quick_serve/customer/login" class="login-link">Customer Login</a>
        <a href="/quick_serve/customer/signup" class="signup-link">Create Account</a>

        <div class="dropdown">
          <button class="dropbtn">Management Login ▾</button>
          <div class="dropdown-content">
            <a href="/quick_serve/admin/login">Admin</a>
            <a href="/quick_serve/staff/login">Staff</a>
          </div>
        </div>

        <button onclick="toggleDarkMode()" class="toggle-btn">Toggle Dark Mode</button>
      </nav>
    </div>
  </header>

  <main>
    <!-- Welcome Banner -->
    <div class="welcome-banner">
      <?php if (!empty($user['profile_picture'])): ?>
        <img src="/quick_serve/storage/uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="welcome-pic">
      <?php endif; ?>
      <div class="welcome-text">
        <h2 style="font-family: Georgia, 'Times New Roman', Times, serif;">Welcome to Brock Cafe!</h2>
        <p>Order, track and serve — all in one place.</p>
      </div>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="/quick_serve/home/menu" class="search">
      <div class="suggestions">
        <input id="search" name="search" type="text" placeholder="Search menu item..." autocomplete="off" />
        <div id="suggestionsContainer" class="suggestions-list" style="display:none;"></div>
      </div>
      <button type="submit">Search</button>
    </form>

    <!-- Order Here Box -->
    <div class="order-box">
      <h2> Order Here!</h2>
    </div>


    <!-- Today's Menu Section -->
    <div class="menu-section">
      <div class="section-title">Today's Menu</div>
      <div class="grid">
        <div class="card food" onclick="location.href='/quick_serve/home/menu?category=food'">
          <h2> Food</h2>
          <p>Popular mains & combos</p>
        </div>
        <div class="card bakery" onclick="location.href='/quick_serve/home/menu?category=Bakery'">
          <h2> Bakery</h2>
          <p>Freshly baked daily</p>
        </div>
        <div class="card beverage" onclick="location.href='/quick_serve/home/menu?category=Beverage'">
          <h2> Beverages</h2>
          <p>Coffee, shakes & more</p>
        </div>
      </div>
    </div>
  </main>

  <footer class="app-footer">© 2025 QuickServe · Built for speed and simplicity</footer>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
    }

    const searchInput = document.getElementById('search');
    const suggestionsContainer = document.getElementById('suggestionsContainer');
    searchInput.addEventListener('input', function() {
      const query = this.value.trim();
      if (!query) {
        suggestionsContainer.style.display = 'none';
        suggestionsContainer.innerHTML = '';
        return;
      }
      fetch(`/quick_serve/api/search_suggestions?query=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
          suggestionsContainer.innerHTML = '';
          if (data.length === 0) {
            suggestionsContainer.style.display = 'none';
            return;
          }
          suggestionsContainer.style.display = 'block';
          const ul = document.createElement('ul');
          data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.name;
            li.onclick = () => {
              searchInput.value = item.name;
              suggestionsContainer.style.display = 'none';
              suggestionsContainer.innerHTML = '';
            };
            ul.appendChild(li);
          });
          suggestionsContainer.appendChild(ul);
        });
    });
  </script>
</body>

</html>