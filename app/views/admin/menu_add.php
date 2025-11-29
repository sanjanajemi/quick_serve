<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="\quick_serve\assets\css\admin\admin_menu.css?v=1.1" />

</head>

<body>

    <h2> Add New Menu Item</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/quick_serve/admin/menu/create">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>

        <label>Variant Type</label>
        <input type="text" name="variant_type" value="<?= htmlspecialchars($old['variant_type'] ?? '') ?>">

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>

        <label>Price (DKK)</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>

        <label>Category</label>
        <input type="text" name="category" value="<?= htmlspecialchars($old['category'] ?? '') ?>">

        <label>Ingredients</label>
        <textarea name="ingredients"><?= htmlspecialchars($old['ingredients'] ?? '') ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="published" <?= ($old['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
            <option value="unpublished" <?= ($old['status'] ?? '') === 'unpublished' ? 'selected' : '' ?>>Unpublished</option>
        </select>

        <label>Image URL</label>
        <input type="text" name="image_url" value="<?= htmlspecialchars($old['image_url'] ?? '') ?>">

        <?php if (!empty($old['image_url'])): ?>
            <img src="<?= htmlspecialchars($old['image_url']) ?>" alt="Preview Image" class="image-preview">
        <?php endif; ?>

        <button type="submit">Create Item</button>
    </form>

    <script src="\quick_serve\assets\js\admin\menu_add.js"></script>
</body>

</html>