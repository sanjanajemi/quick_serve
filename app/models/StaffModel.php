<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class StaffModel
{
public static function findById($staffId)
{
    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
    $stmt->execute([':staff_id' => $staffId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public static function update($id, $data) {
    $db = Database::connect();
    $stmt = $db->prepare("UPDATE staff SET name = ?, email = ?, phone = ?, role = ?, profile_picture = ? WHERE staff_id = ?");
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['role'],
        $data['profile_picture'],
        $id
    ]);

}

public static function updatePassword($staffId, $hashedPassword)
{
    $db = Database::connect();
    $stmt = $db->prepare("UPDATE staff SET password = ? WHERE staff_id = ?");
    $stmt->execute([$hashedPassword, $staffId]);
}

public static function deleteById($staffId)
{
    $db = (new Database())->connect();
    $stmt = $db->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->execute([$staffId]);
}

//Admin functions

public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM staff ORDER BY staff_id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public static function create($data)
{
    $db = Database::connect();
    $profilePicture = '';

    if (!empty($_FILES['profile_picture']['name'])) {
        $filename = basename($_FILES['profile_picture']['name']);
        $target = __DIR__ . '/../../public/uploads/' . $filename;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
            $profilePicture = $filename;
        }
    }

    $stmt = $db->prepare("INSERT INTO staff (name, email, password, role, phone, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");

    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['password'],  
        $data['role'],
        $data['phone'] ?? '',
        $profilePicture
    ]);
}
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM staff WHERE staff_id = ?");
        $stmt->execute([$id]);
    }


}