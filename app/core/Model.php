<?php

namespace App\Core;

use App\Core\Database;
use PDO;

class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::connect();
    }

   public static function findById($staffId)
{
    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM staff WHERE id = :id");
    $stmt->execute([':id' => $staffId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function deleteById($table, $id) {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll($table) {
        $stmt = $this->db->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}