<?php
namespace App\Core;
use PDO;
use PDOException;

class Database {
    private static $instance = null;

    // Connect to the database using PDO
    public static function connect() {
        if (self::$instance === null) {
           $config = require __DIR__ . '/../../config/db.php';
            try {
                self::$instance = new PDO(
                    "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
                    $config['user'],
                    $config['password']
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}