<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Menu Items</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/menu_list.css" />
</head>

<body>

  <header class="main-header">
    <h1>Manage Menu Items</h1>
    <nav class="header-right">
      <a href="/quick_serve/staff/dashboard" class="button">← Dashboard</a>
      <<a href="#" onclick="toggleDarkMode()" class="button">Toggle Dark Mode</a>
      <a href="/quick_serve/staff/logout" class="button">Logout</a>
    </nav>
  </header>

  <?php
  $categories = ['food', 'bakery', 'beverage'];
  foreach ($categories as $category):
    $filteredItems = array_filter($menuItems, fn($item) => strtolower($item['category'] ?? '') === $category);
    if (count($filteredItems) === 0) continue;
  ?>
    <h3 style="margin-top: 30px; color: var(--accent-light); text-transform: capitalize;">
      <?= ucfirst($category) ?>
    </h3>

    <table>
      <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      <?php foreach ($filteredItems as $item): ?>
        <?php
        $imageUrl = trim($item['image_url'] ?? '');
        $isValidImage = filter_var($imageUrl, FILTER_VALIDATE_URL);
        ?>
        <tr>
          <td class="item-name">
            <?php if ($isValidImage): ?>
              <img src="<?= htmlspecialchars($imageUrl) ?>"
                alt="<?= htmlspecialchars($item['name'] ?? 'Menu Item') ?>"
                class="menu-thumb">
            <?php else: ?>
              <div class="menu-thumb" style="width: 60px; height: 60px; background-color: #f0f0f0;"></div>
            <?php endif; ?>
            <span><?= htmlspecialchars($item['name'] ?? '') ?></span>
          </td>
          <td><?= htmlspecialchars($item['category'] ?? '') ?></td>
          <td><?= htmlspecialchars($item['status'] ?? '') ?></td>
          <td>
            <?php if (($item['status'] ?? '') === 'published'): ?>
              <a href="/quick_serve/staff/menu/unpublish?id=<?= htmlspecialchars($item['menu_item_id'] ?? '') ?>" class="button">Hide Item</a>
            <?php else: ?>
              <a href="/quick_serve/staff/menu/publish?id=<?= htmlspecialchars($item['menu_item_id'] ?? '') ?>" class="button">Show Item</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endforeach; ?>

  <div class="back-link">
    <a href="/quick_serve/staff/dashboard" class="button">← Back to Dashboard</a>
  </div>
</body>

<script>
  if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
  }

  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
  }
</script>

 <script src="\quick_serve\assets\js\staff\menu_list.js"></script>
</html>