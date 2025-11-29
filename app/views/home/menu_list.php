<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuickServe Menu</title>
    <link rel="stylesheet" href="/quick_serve/assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .back-link {
            display: block;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            color: #00c9ff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        input[type="text"], select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 16px;
            border: none;
            background-color: #00c9ff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0090cc;
        }
        .menu-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .menu-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 250px;
            padding: 15px;
            box-shadow: 2px 2px 6px rgba(0,0,0,0.1);
            background-color: #fff;
            text-align: left;
            transition: transform 0.2s ease;
        }
        .menu-card:hover {
            transform: scale(1.02);
        }
        .menu-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .menu-card h3 {
            margin: 10px 0 5px;
        }
        .menu-card p {
            margin: 5px 0;
        }
        .pagination {
            text-align: center;
            margin-top: 30px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 6px 12px;
            border: 1px solid #ccc;
            text-decoration: none;
            border-radius: 4px;
            color: #333;
        }
        .pagination a.active {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <a href="/quick_serve/home/dashboard" class="back-link">← Back to Dashboard</a>
    <h1>Brock Cafe</h1>

   

    <div class="menu-container">
        <?php if (empty($menuItems)): ?>
            <p>No items found.</p>
        <?php else: ?>
            <?php foreach ($menuItems as $item): ?>
                <a href="/quick_serve/menu/item?id=<?= $item['menu_item_id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="menu-card">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h3><?= htmlspecialchars($item['name']) ?> <?= $item['variant_type'] ? "({$item['variant_type']})" : "" ?></h3>
                        <p><?= htmlspecialchars($item['description']) ?></p>
                        <p><strong>Price:</strong> DKK <?= number_format($item['price'], 2) ?></p>
                        <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>"
               class="<?= ($i == $page) ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</body>
</html>