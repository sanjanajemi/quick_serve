<?php

namespace App\Models;

use App\Core\Database;
use PDOException;
use PDO;

class OrderModel
{

public static function createWithCustomer(array $customerData, array $orderData): int
{
    $db = Database::connect();
    $db->beginTransaction();

    // Step 1: Check if customer already exists by email
    $stmtCheck = $db->prepare("SELECT customer_id FROM customer WHERE email = ?");
    $stmtCheck->execute([$customerData['email']]);
    $existingCustomer = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($existingCustomer) {
        $customerId = (int)$existingCustomer['customer_id'];
    } else {
        // Insert new customer (no phone column)
        $stmtCustomer = $db->prepare("
            INSERT INTO customer (name, email, account_status)
            VALUES (?, ?, 'active')
        ");
        $stmtCustomer->execute([
            $customerData['name'],
            $customerData['email']
        ]);
        $customerId = (int)$db->lastInsertId();
    }

    // Step 2: Insert order with NULL status
    $stmtOrder = $db->prepare("
        INSERT INTO `order` (customer_id, status, placed_at, comments, final_amount)
        VALUES (?, NULL, NOW(), ?, ?)
    ");
    $stmtOrder->execute([
        $customerId,
        $orderData['comments'] ?? null,
        $orderData['final_amount']
    ]);
    $orderId = (int)$db->lastInsertId();

    // Step 3: Insert items if provided
    if (!empty($orderData['items'])) {
        $stmtItem = $db->prepare("
            INSERT INTO order_item (order_id, menu_item_id, quantity)
            VALUES (?, ?, ?)
        ");
        foreach ($orderData['items'] as $menuItemId => $qty) {
            if ((int)$qty > 0) {
                $stmtItem->execute([$orderId, $menuItemId, $qty]);
            }
        }
    }

    $db->commit();
    return $orderId;
}

    public static function fetchActiveOrdersWithItems()
    {
        $db = Database::connect();
        $stmt = $db->prepare("
    SELECT 
        o.order_id,
        o.customer_id,
        o.status,
        o.placed_at,
        o.comments,
        o.final_amount,
        TIMESTAMPDIFF(MINUTE, o.placed_at, NOW()) AS waiting_time,
        c.name AS customer_name,
        c.email,
        mi.name AS item_name,
        oi.quantity
    FROM `order` o
    JOIN order_item oi ON o.order_id = oi.order_id
    JOIN menu_item mi ON oi.menu_item_id = mi.menu_item_id
    JOIN customer c ON o.customer_id = c.customer_id
    LEFT JOIN cleared_orders co ON o.order_id = co.order_id
    WHERE (o.status IS NULL OR o.status IN ('Received', 'Preparing', 'Ready'))
      AND co.order_id IS NULL
    ORDER BY o.placed_at ASC
");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Group items by order
        $orders = [];
        foreach ($rows as $row) {
            $id = $row['order_id'];
            if (!isset($orders[$id])) {
                $orders[$id] = [
                    'order_id' => $row['order_id'],
                    'customer_id' => $row['customer_id'],
                    'status' => $row['status'],
                    'placed_at' => $row['placed_at'],
                    'comments' => $row['comments'],
                    'final_amount' => $row['final_amount'],
                    'waiting_time' => $row['waiting_time'],
                    'customer_name' => $row['customer_name'],
                    'email' => $row['email'],
                    'items' => []
                ];
            }
            $orders[$id]['items'][] = [
                'name' => $row['item_name'],
                'quantity' => $row['quantity']
            ];
        }

        return array_values($orders);
    }

    //NEW
    public static function fetchAllOrdersForHistory()
    {
        $db = Database::connect();
        $stmt = $db->prepare("
        SELECT o.*, c.name AS customer_name
        FROM `order` o
        LEFT JOIN customer c ON o.customer_id = c.customer_id
        ORDER BY o.placed_at DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function fetchSingleOrder($orderId)
    {
        $db = Database::connect();
        $stmt = $db->prepare("
        SELECT o.*, c.name AS customer_name, c.email,
               mi.name AS item_name, oi.quantity
        FROM `order` o
        JOIN customer c ON o.customer_id = c.customer_id
        JOIN order_item oi ON o.order_id = oi.order_id
        JOIN menu_item mi ON oi.menu_item_id = mi.menu_item_id
        WHERE o.order_id = ?
    ");
        $stmt->execute([$orderId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) return null;

        $order = $rows[0];
        $order['items'] = [];
        foreach ($rows as $row) {
            $order['items'][] = [
                'name' => $row['item_name'],
                'quantity' => $row['quantity']
            ];
        }

        return $order;
    }
    //ADMIN INTERFACE


    public static function fetchActiveOrders()
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT o.order_id, o.customer_id, o.status, o.placed_at, 
                   o.waiting_time, o.comments, o.final_amount,
                   c.name AS customer_name
            FROM `order` o
            JOIN customer c ON o.customer_id = c.customer_id
            WHERE o.status IS NULL 
               OR o.status IN ('Received', 'Preparing', 'Ready', 'Cancelled')
            ORDER BY o.placed_at ASC
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::fetchActiveOrders error: " . $e->getMessage());
            return [];
        }
    }
    public static function setOrderStatus($orderId, $newStatus)
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
                UPDATE `order`
                SET status = :status
                WHERE order_id = :order_id
            ");
            return $stmt->execute([
                ':status' => $newStatus,
                ':order_id' => $orderId
            ]);
        } catch (PDOException $e) {
            error_log("OrderModel::setOrderStatus error: " . $e->getMessage());
            return false;
        }
    }

    public static function fetchOrderHistory($limit = 50)
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
                SELECT o.*, c.name AS customer_name
                FROM `order` o
                JOIN customer c ON o.customer_id = c.customer_id
               WHERE o.status IN ('Ready','Cancelled')
                ORDER BY o.placed_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::fetchOrderHistory error: " . $e->getMessage());
            return [];
        }
    }


    public static function getAllOrdersWithCustomer(): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT o.order_id, o.status, o.placed_at, o.final_amount, o.waiting_time,
                   c.name AS customer_name
            FROM `order` o
            JOIN customer c ON o.customer_id = c.customer_id
            ORDER BY o.placed_at DESC
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('OrderModel::getAllOrdersWithCustomer error: ' . $e->getMessage());
            return [];
        }
    }



    public static function getOrderDetails($orderId): array
    {
        try {
            $db = Database::connect();

            // Fetch order and customer info
            $stmt = $db->prepare("
            SELECT o.*, c.name AS customer_name, c.email
            FROM `order` o
            JOIN customer c ON o.customer_id = c.customer_id
            WHERE o.order_id = :order_id
        ");
            $stmt->execute([':order_id' => $orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) return [];

            //  Fetch order items using correct table name: menu_item (not menu_items)
            $stmtItems = $db->prepare("
            SELECT oi.quantity, oi.unit_price AS price, m.name AS item_name
            FROM order_item oi
            JOIN menu_item m ON oi.menu_item_id = m.menu_item_id
            WHERE oi.order_id = :order_id
        ");
            $stmtItems->execute([':order_id' => $orderId]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            return $order;
        } catch (PDOException $e) {
            error_log("OrderModel::getOrderDetails error: " . $e->getMessage());
            return [];
        }
    }

    public static function getLast7DaysSummary(): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT 
                DATE(placed_at) AS summary_date,
                COUNT(*) AS total_orders,
                SUM(CASE WHEN status IN ('Ready') THEN final_amount ELSE 0 END) AS revenue,
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled_orders
            FROM `order`
            WHERE placed_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
              AND (status IS NULL OR status IN ('Received','Preparing','Ready','Cancelled'))
            GROUP BY DATE(placed_at)
            ORDER BY summary_date ASC
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('OrderModel::getLast7DaysSummary error: ' . $e->getMessage());
            return [];
        }
    }

    public static function getTodayOrderCount(): int
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT COUNT(*) FROM `order`
            WHERE DATE(placed_at) = CURDATE()
        ");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("OrderModel::getTodayOrderCount error: " . $e->getMessage());
            return 0;
        }
    }

    public static function getTodayStats(): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT 
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled_orders,
                COUNT(*) AS total_orders
            FROM `order`
            WHERE DATE(placed_at) = CURDATE()
        ");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['cancelled_orders' => 0, 'total_orders' => 0];
        } catch (PDOException $e) {
            error_log('OrderModel::getTodayStats error: ' . $e->getMessage());
            return ['cancelled_orders' => 0, 'total_orders' => 0];
        }
    }

    public static function deleteOrder($orderId): bool
    {
        try {
            $db = Database::connect();

            // Delete order items first
            $stmtItems = $db->prepare("DELETE FROM order_item WHERE order_id = ?");
            $stmtItems->execute([$orderId]);

            // Then delete the order itself
            $stmtOrder = $db->prepare("DELETE FROM `order` WHERE order_id = ?");
            return $stmtOrder->execute([$orderId]);
        } catch (PDOException $e) {
            error_log("OrderModel::deleteOrder error: " . $e->getMessage());
            return false;
        }
    }

    public static function deleteOldOrders(): bool
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            DELETE FROM `order`
            WHERE placed_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("OrderModel::deleteOldOrders error: " . $e->getMessage());
            return false;
        }
    }


    public static function searchOrders($query): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
SELECT * FROM `order`
WHERE order_id LIKE :query
   OR status LIKE :query
   OR (:query = 'Not Set' AND status IS NULL)
        ");
            $stmt->execute([':query' => "%$query%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::searchOrders error: " . $e->getMessage());
            return [];
        }
    }

    // testing for admin
    public static function getAvailableStatuses(): array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT DISTINCT status FROM `order` ORDER BY status ASC");
        $stmt->execute();
        $statuses = $stmt->fetchAll(PDO::FETCH_COLUMN);


        $statuses = array_filter($statuses);
        array_unshift($statuses, null);
        return $statuses;
    }


    public static function getOrdersByDate($date): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT o.order_id, o.status, o.placed_at, o.final_amount, o.waiting_time,
                   o.comments, o.customer_id, c.name AS customer_name
            FROM `order` o
            JOIN customer c ON o.customer_id = c.customer_id
            WHERE DATE(o.placed_at) = :date
            ORDER BY o.placed_at ASC
        ");
            $stmt->execute([':date' => $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getOrdersByDate error: " . $e->getMessage());
            return [];
        }
    }
    public static function getRecentOrders($limit = 5)
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
            SELECT o.order_id, o.status, o.placed_at AS order_date
            FROM `order` o
            ORDER BY o.placed_at DESC
            LIMIT :limit
        ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("OrderModel::getRecentOrders error: " . $e->getMessage());
            return [];
        }
    }
    
     //customer interface
     public static function createOrder($customerId, $amount, $comments = null)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO `order` (customer_id, placed_at, final_amount, comments, status)
            VALUES (?, NOW(), ?, ?, 'pending')
        ");
        $stmt->execute([$customerId, $amount, $comments]);

        return $pdo->lastInsertId();
    }

    public static function getCustomerOrders($customerId)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `order` WHERE customer_id=? ORDER BY placed_at DESC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOrderById($id, $customerId)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `order` WHERE order_id=? AND customer_id=?");
        $stmt->execute([$id, $customerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function cancelOrder($orderId, $customerId)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `order` 
            SET status='cancelled' 
            WHERE order_id=? AND customer_id=?
        ");
        return $stmt->execute([$orderId, $customerId]);
    }

     public static function addItem($orderId, $menuItemId, $unitPrice, $qty)
    {
        $total = $unitPrice * $qty;

        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO order_item (order_id, menu_item_id, unit_price, quantity, total_price)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$orderId, $menuItemId, $unitPrice, $qty, $total]);
    }

    public static function getItems($orderId)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT oi.*, mi.name, mi.image_url
            FROM order_item oi
            JOIN menu_item mi ON oi.menu_item_id = mi.menu_item_id
            WHERE order_id=?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
