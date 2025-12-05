<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Management</title>
    <link rel="stylesheet" href="\quick_serve\assets\css\admin\menu_list.css?v=7">
</head>
<body>

   <div class="menu-header">
  <h2>📋 Menu Management</h2>
  <div class="menu-actions">
       <a href="/quick_serve/admin/menu/add" class="btn add-btn">Add New Item</a>
    <a href="/quick_serve/admin/dashboard" class="btn dashboard-btn">Back to Dashboard</a>
 
  </div>
</div>

    <div class="card-container">
        <?php foreach ($menuItems as $item): ?>
        <div class="menu-card">
            <?php if (!empty($item['image_url'])): ?>
                <img src="<?= htmlspecialchars($item['image_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="Image">
            <?php else: ?>
                <div class="no-image">No image</div>
            <?php endif; ?>

            <h3><?= htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
            <p><strong>Variant:</strong> <?= htmlspecialchars($item['variant_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Price:</strong> DKK <?= number_format($item['price'] ?? 0, 2) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($item['category'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <div class="status-badge <?= htmlspecialchars($item['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <?= ucfirst($item['status'] ?? '') ?>
            </div>

            <div class="actions">
                <a href="/quick_serve/admin/menu/edit?id=<?= htmlspecialchars($item['menu_item_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">✏️ Edit</a>
                <a href="/quick_serve/admin/menu/delete?id=<?= htmlspecialchars($item['menu_item_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="delete" onclick="return confirm('Delete this item?')">🗑️ Delete</a>
                <?php if (($item['status'] ?? '') === 'published'): ?>
                    <a href="/quick_serve/admin/menu/unpublish?id=<?= htmlspecialchars($item['menu_item_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">📤 Unpublish</a>
                <?php else: ?>
                    <a href="/quick_serve/admin/menu/publish?id=<?= htmlspecialchars($item['menu_item_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">📥 Publish</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script src="\quick_serve\assets\js\admin\menu_list.js"></script>
</body>
</html>