<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class CustomerModel
{
    public static function findByEmail(string $email): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM customer WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        return $customer ?: null;
    }
    public static function create($name, $email, $passwordHash)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO customer (name, email, password_hash, account_status)
            VALUES (?, ?, ?, 'active')
        ");
        return $stmt->execute([$name, $email, $passwordHash]);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM customer WHERE customer_id = :id");
        $stmt->execute([':id' => $id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        return $customer ?: null;
    }

    public static function emailExists(string $email): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT COUNT(*) FROM customer WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public static function getAllCustomers(): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT customer_id, name, email FROM customer ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("CustomerModel::getAllCustomers error: " . $e->getMessage());
            return [];
        }
    }

    public static function getCustomerById(int $customerId): array
    {
        try {
            $db = Database::connect();

            // Get customer info
            $stmt = $db->prepare("SELECT * FROM customer WHERE customer_id = :customer_id");
            $stmt->execute([':customer_id' => $customerId]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$customer) return [];

            // Get order history
            $stmtOrders = $db->prepare("
            SELECT order_id, placed_at, status, final_amount
            FROM `order`
            WHERE customer_id = :customer_id
            ORDER BY placed_at DESC
        ");
            $stmtOrders->execute([':customer_id' => $customerId]);
            $customer['orders'] = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

            return $customer;
        } catch (\PDOException $e) {
            error_log("CustomerModel::getCustomerById error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAllCustomersWithOrders(): array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM customer ORDER BY name ASC");
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($customers as &$customer) {
            $stmtOrders = $db->prepare("
            SELECT order_id, placed_at, status, final_amount
            FROM `order`
            WHERE customer_id = :customer_id
            ORDER BY placed_at DESC
        ");
            $stmtOrders->execute([':customer_id' => $customer['customer_id']]);
            $customer['orders'] = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
        }

        return $customers;
    }

    public static function updateProfile($id, $name, $email)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE customer SET name=?, email=? WHERE customer_id=?");
        return $stmt->execute([$name, $email, $id]);
    }

    public static function updatePassword($id, $newHash)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE customer SET password_hash=? WHERE customer_id=?");
        return $stmt->execute([$newHash, $id]);
    }

    public static function updateAvatar($id, $avatarName)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE customer SET avatar=? WHERE customer_id=?");
        return $stmt->execute([$avatarName, $id]);
    }

    public static function deleteAccount($id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM customer WHERE customer_id=?");
        return $stmt->execute([$id]);
    }

    //  Toggle account status (activate/deactivate)
    public static function updateStatus(int $id, string $status): bool
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("UPDATE customer SET account_status = :status WHERE customer_id = :id");
            return $stmt->execute([':status' => $status, ':id' => $id]);
        } catch (\PDOException $e) {
            error_log("CustomerModel::updateStatus error: " . $e->getMessage());
            return false;
        }
    }

    // Delete customer (and their orders)
    public static function deleteCustomer(int $id): bool
    {
        try {
            $db = Database::connect();
            $stmtOrders = $db->prepare("DELETE FROM `order` WHERE customer_id = :id");
            $stmtOrders->execute([':id' => $id]);

            $stmt = $db->prepare("DELETE FROM customer WHERE customer_id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("CustomerModel::deleteCustomer error: " . $e->getMessage());
            return false;
        }
    }
}
