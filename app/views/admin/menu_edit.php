<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" href="\quick_serve\assets\css\admin\menu_edit.css">
  
</head>
<body>
    <h2>Edit Menu Item</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/quick_serve/admin/menu/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $item['menu_item_id'] ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>

        <label>Variant Type</label>
        <input type="text" name="variant_type" value="<?= htmlspecialchars($item['variant_type']) ?>">

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($item['description']) ?></textarea>

        <label>Price (DKK)</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>

        <label>Category</label>
        <input type="text" name="category" value="<?= htmlspecialchars($item['category']) ?>">

        <label>Ingredients</label>
        <textarea name="ingredients"><?= htmlspecialchars($item['ingredients']) ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="published" <?= $item['status'] === 'published' ? 'selected' : '' ?>>Published</option>
            <option value="unpublished" <?= $item['status'] === 'unpublished' ? 'selected' : '' ?>>Unpublished</option>
        </select>
          <label>Image</label>
        <?php if (!empty($item['image_url'])): ?>
            <div class="current-image">
                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Current image" style="max-width:150px;">
                <p></p>
            </div>
        <?php endif; ?>
        <input type="file" name="image_file" accept="image/*">


        <button type="submit">Update Item</button>
            <div class="back-link-container">
        <a href="/quick_serve/admin/menu" class="back-link">← Back to Menu</a>
      </div>
    </form>
    
    <script src="\quick_serve\assets\js\admin\menu_list.js"></script>
</body>
</html>
    
    <script src="\quick_serve\assets\js\admin\menu_list.js"></script>
</body>
</html>