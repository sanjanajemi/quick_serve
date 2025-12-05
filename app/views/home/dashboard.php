<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Brock Café · Home</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/home/home.css" />
</head>

<body>
 <header class="dashboard-header">
  <div class="header-top">
    <div class="brand">
      <img src="/quick_serve/assets/images/home/niels.png" alt="Brock Cafe Logo" class="brand-logo">
      <h1 style="font-family: Georgia, 'Times New Roman', Times, serif;">Brock Café</h1>
    </div>


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
  <div class="welcome-text">
    <h2 style="font-family: Georgia, 'Times New Roman', Times, serif;">
      Welcome to Brock Café!
    </h2>
    <div class="marquee">
      <span style="font-family: Georgia, 'Times New Roman', Times, serif;">
        Fuel your day with Brock Café</div>
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


<!-- About Section -->
<section class="about-section">
  <div class="about-container">
    <h2>About Brock Café</h2>
    <p>
      Brock Café began its journey in the heart of Niels Brock Copenhagen Business College more than a decade ago, 
      created to give students and staff a welcoming space to recharge between lectures and projects. 
      What started as a small canteen has grown into a vibrant hub of campus life.
    </p>
    <p>
      Today, Brock Café serves over 500 people every day — from early-morning coffee seekers 
      to late-afternoon snack lovers. It has become a cornerstone of the college experience, 
      offering quick service without compromising on quality.
    </p>
    <p>
      The café is especially popular for its freshly baked pastries, hearty lunch combos, 
      and signature beverages that keep the community energized. Whether you’re here for a 
      grab-and-go meal or to catch up with friends, Brock Café is known for speed, simplicity, 
      and a warm atmosphere that feels like home.
    </p>
  </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
  <div class="contact-container">
    <h2>Contact Us</h2>
    <p>Email: <a href="mailto:brockcafe@nielsbrock.dk">brockcafe@nielsbrock.dk</a></p>
    <p>Phone: +45 33 41 60 00</p>
    <p>Address: Niels Brock College, Copenhagen, Denmark</p>

    <div class="social-links">
      <a href="https://www.facebook.com/nielsbrock" target="_blank" class="social fb">Facebook</a>
      <a href="https://www.instagram.com/nielsbrock" target="_blank" class="social insta">Instagram</a>
      <a href="https://www.linkedin.com/school/niels-brock/" target="_blank" class="social linkedin">LinkedIn</a>
    </div>
  </div>
</section>
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

  <script>
 
  const aboutSection = document.querySelector('.about-section');

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        aboutSection.classList.add('show');
        observer.unobserve(aboutSection); 
      }
    });
  }, { threshold: 0.3 });

  observer.observe(aboutSection);
</script>

</body>

</html>