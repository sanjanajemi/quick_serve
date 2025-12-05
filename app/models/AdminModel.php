<?php
namespace App\Models;

//Admin inteface uses this model

use App\Core\Database;
use PDO;

class AdminModel
{
    public static function findById($adminId)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM admin WHERE admin_id = :admin_id");
        $stmt->execute([':admin_id' => $adminId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
  public static function create($data): ?int
{
    $db = Database::connect();
    $stmt = $db->prepare("INSERT INTO admin (name, email, password) VALUES (:name, :email, :password)");
    $success = $stmt->execute([
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':password' => $data['password']
    ]);

    return $success ? (int)$db->lastInsertId() : null;
}
        public function update(int $id, array $data): bool
    {
        $db = Database::connect();

        $fields = [];
        $params = [':id' => $id];

        if (isset($data['name'])) {
            $fields[] = "name = :name";
            $params[':name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = $data['password'];
        }

        if (empty($fields)) {
            return false;
        }

       $sql = "UPDATE admin SET " . implode(', ', $fields) . " WHERE admin_id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

public static function deleteById(int $id): bool
{
    $db = Database::connect();
    $stmt = $db->prepare("DELETE FROM admin WHERE admin_id = :id");
    return $stmt->execute([':id' => $id]);
}

}