<?php
namespace App\Models;

//Home + Customer + Staff + Admin interface uses this model
use App\Models\DatabaseModel;
use PDO;

class MenuModel extends DatabaseModel
{
    public function searchItems(string $keyword, string $category = ''): array
    {
        $sql = "SELECT * FROM menu_item WHERE status = 'published' AND 
                (name LIKE :kw OR ingredients LIKE :kw OR description LIKE :kw)";

        $params = ['kw' => '%' . $keyword . '%'];

        if ($category) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }

        $sql .= " ORDER BY category, name";
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchNames(string $query): array
    {
        $sql = "SELECT name FROM menu_item WHERE name LIKE :query AND status = 'published' LIMIT 10";
        $stmt = $this->query($sql, ['query' => $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countItems(string $search = '', string $category = ''): int
    {
        $sql = "SELECT COUNT(*) FROM menu_item WHERE status = 'published'";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE :kw OR ingredients LIKE :kw OR description LIKE :kw)";
            $params['kw'] = '%' . $search . '%';
        }

        if ($category) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }

        return $this->query($sql, $params)->fetchColumn();
    }

    public function getPaginatedItems(string $search = '', string $category = '', int $limit = 12, int $offset = 0): array
    {
        $sql = "SELECT * FROM menu_item WHERE status = 'published'";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE :kw OR ingredients LIKE :kw OR description LIKE :kw)";
            $params['kw'] = '%' . $search . '%';
        }

        if ($category) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }

        $sql .= " ORDER BY category, name LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableItems(): array
    {
        $sql = "SELECT * FROM menu_item WHERE status = 'published' ORDER BY category, name";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllItems(): array
    {
        $sql = "SELECT * FROM menu_item ORDER BY category, name";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemsByCategory(string $category): array
    {
        $sql = "SELECT * FROM menu_item WHERE category = :category AND status = 'published'";
        $stmt = $this->query($sql, ['category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getItemById(int $id): ?array
    {
        $sql = "SELECT * FROM menu_item WHERE menu_item_id = :id AND status = 'published'";
        $stmt = $this->query($sql, ['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        return $item ?: null;
    }

    // Admin Toggle availability
    public function updateStatus(int $menuItemId, string $status): bool
    {
        $sql = "UPDATE menu_item SET status = :status WHERE menu_item_id = :id";
        $stmt = $this->query($sql, [
            'status' => $status,
            'id' => $menuItemId
        ]);
        return $stmt->rowCount() > 0;
    }


    public function create(array $data): bool
    {
        $sql = "INSERT INTO menu_item 
        (name, variant_type, description, image_url, price, category, ingredients, status) 
        VALUES 
        (:name, :variant_type, :description, :image_url, :price, :category, :ingredients, :status)";

        $stmt = $this->query($sql, [
            'name' => $data['name'],
            'variant_type' => $data['variant_type'],
            'description' => $data['description'],
            'image_url' => $data['image_url'],
            'price' => $data['price'],
            'category' => $data['category'],
            'ingredients' => $data['ingredients'],
            'status' => $data['status']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE menu_item SET 
                name = :name,
                variant_type = :variant_type,
                description = :description,
                image_url = :image_url,
                price = :price,
                category = :category,
                ingredients = :ingredients,
                status = :status
            WHERE menu_item_id = :id";

        $stmt = $this->query($sql, [
            'id' => $id,
            'name' => $data['name'],
            'variant_type' => $data['variant_type'],
            'description' => $data['description'],
            'image_url' => $data['image_url'],
            'price' => $data['price'],
            'category' => $data['category'],
            'ingredients' => $data['ingredients'],
            'status' => $data['status']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM menu_item WHERE menu_item_id = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function publish(int $id): bool
    {
        $sql = "UPDATE menu_item SET status = 'published' WHERE menu_item_id = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function unpublish(int $id): bool
    {
        $sql = "UPDATE menu_item SET status = 'unpublished' WHERE menu_item_id = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM menu_item WHERE menu_item_id = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        return $item ?: null;
    }

    // Functions from Customer Interface
   public function getByCategory(string $category): array {
    $sql = "SELECT * FROM menu_item WHERE category = :category AND status = 'published' ORDER BY name";
    $stmt = $this->query($sql, ['category' => $category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAll(): array {
    $sql = "SELECT * FROM menu_item WHERE status = 'published' ORDER BY name";
    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

}
