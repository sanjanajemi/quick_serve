<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($menuItem['name']) ?> · QuickServe</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
            padding: 40px 20px;
            margin: 0;
            color: #333;
        }

        .menu-item-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: contain;
        }

        .item-container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .item-container img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .item-container h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #104e5f;
        }

        .item-container p {
            margin: 8px 0;
            font-size: 1rem;
            color: #555;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #00c9ff;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .ingredients-box {
            margin-top: 30px;
            padding: 20px;
            background-color: #fefefe;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: left;
        }

        .ingredients-box h3 {
            margin-top: 0;
            font-size: 1.3rem;
            color: #104e5f;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .ingredients-list {
            list-style: none;
            padding-left: 0;
            margin-top: 12px;
        }

        .ingredients-list li {
            padding: 8px 12px;
            margin-bottom: 6px;
            background-color: #e0f7fa;
            border-radius: 6px;
            color: #104e5f;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .ingredients-list li:hover {
            background-color: #b2ebf2;
        }
    </style>
</head>

<body>
    <div class="item-container">
        <img
            src="<?= htmlspecialchars($menuItem['image_url']) ?>"
            alt="<?= htmlspecialchars($menuItem['name']) ?>"
            class="menu-item-image" />

        <h1><?= htmlspecialchars($menuItem['name']) ?> <?= $menuItem['variant_type'] ? "({$menuItem['variant_type']})" : "" ?></h1>
        <p><strong>Description:</strong> <?= htmlspecialchars($menuItem['description']) ?></p>
        <p><strong>Price:</strong> DKK <?= number_format($menuItem['price'], 2) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($menuItem['category']) ?></p>

        <?php if (!empty($menuItem['ingredients'])): ?>
            <div class="ingredients-box">
                <h3>🧂 Ingredients</h3>
                <ul class="ingredients-list">
                    <?php foreach (explode(',', $menuItem['ingredients']) as $ingredient): ?>
                        <li><?= htmlspecialchars(trim($ingredient)) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <a href="/quick_serve/home/menu" class="back-link">← Back to Menu</a>
    </div>
</body>

</html>