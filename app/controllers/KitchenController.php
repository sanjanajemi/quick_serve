<?php
namespace App\Controllers;

require_once __DIR__ . '/../../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../../libs/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\View;
use App\Models\StaffModel;
use App\Models\MenuModel;
use App\Helpers\SessionHelper;
use App\Core\Database;
use App\Models\OrderModel;
use PDO;

class KitchenController
{
    public function kitchenView()
    {
        $orders = OrderModel::fetchActiveOrdersWithItems();
        View::render('staff.kitchen_view', ['orders' => $orders]);
    }


    public function updateOrderStatus()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $orderId = $input['order_id'] ?? null;
        $status = $input['status'] ?? null;

        if (!$orderId || !$status) {
            echo json_encode(['success' => false, 'error' => 'Missing data']);
            return;
        }

        $db = Database::connect();
        $stmt = $db->prepare("UPDATE `order` SET status = ? WHERE order_id = ?");
        $success = $stmt->execute([$status, $orderId]);

        echo json_encode(['success' => $success]);
    }



    public function pollOrders()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT COUNT(*) FROM `order` WHERE status IS NULL OR status IN ('Received', 'Preparing')");
        $count = $stmt->fetchColumn();
        echo json_encode(['order_count' => (int)$count]);
    }


    public function sendEmail()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $orderId = $input['order_id'] ?? null;

        if (!$orderId) {
            echo json_encode(['success' => false, 'error' => 'Missing order ID']);
            return;
        }

        $order = OrderModel::fetchSingleOrder($orderId);
        if (!$order || empty($order['email'])) {
            echo json_encode(['success' => false, 'error' => 'Order or email not found']);
            return;
        }

        $config = require __DIR__ . '/../../config/email.php';
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $config['port'];

            // Sender and recipient
            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($order['email'], $order['customer_name']);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Your Quick Serve Order #{$orderId} is Ready!";
            $mail->Body = $this->buildEmailTemplate($order);

            // Send email
            $mail->send();

            // log email sending
            $logDir = __DIR__ . '/../../storage/logs/';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }

            $logEntry = date('Y-m-d H:i:s') . " | Sent to {$order['email']} | Order #{$orderId} | Status: {$order['status']}\n";
            file_put_contents($logDir . 'email.log', $logEntry, FILE_APPEND);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $logDir = __DIR__ . '/../../storage/log/';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }

            $errorMessage = date('Y-m-d H:i:s') . " | EMAIL FAILED | Order #{$orderId} | Error: {$mail->ErrorInfo}\n";
            file_put_contents($logDir . 'email_error.log', $errorMessage, FILE_APPEND);

            error_log("PHPMailer error: " . $mail->ErrorInfo);
            echo json_encode(['success' => false, 'error' => 'Email failed']);
        }
    }

    private function buildEmailTemplate($order)
    {
        $itemsHtml = '';
        foreach ($order['items'] as $item) {
            $itemName = htmlspecialchars($item['name']);
            $quantity = (int) $item['quantity'];
            $itemsHtml .= "<li>{$itemName} * {$quantity}</li>";
        }

        $status = htmlspecialchars($order['status'] ?? 'Not Set');
        $comments = htmlspecialchars($order['comments'] ?? '');
        $customerName = htmlspecialchars($order['customer_name']);
        $orderId = (int) $order['order_id'];


        $cardColor = '#dae6f4ff';

        return "
<html>
<body style='font-family: Arial, sans-serif; background-color: #06253eff; padding: 20px; color: #333;'>
    <div style='max-width: 600px; margin: auto; background-color: {$cardColor}; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>
        <h2 style='color: #2c3e50; margin-bottom: 10px;'>Order #{$orderId}</h2>
        <p style='font-size: 16px;'>Hi {$customerName},</p>
        <p style='font-size: 18px; color: #062f49ff; font-weight: bold;'>🎉 Your order is ready!</p>
        <h4 style='margin-top: 20px;'>Items Ordered:</h4>
        <ul style='padding-left: 20px; font-size: 15px;'>{$itemsHtml}</ul>
        " . ($comments ? "<p style='margin-top: 15px; font-style: italic; color: #555;'>Kitchen note: {$comments}</p>" : "") . "
        <p style='margin-top: 30px;'>Thank you for ordering with <strong>Brock Cafe</strong>!</p>
        <p style='font-size: 12px; color: #888; margin-top: 40px;'>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
";
    }


    public function clearOrder()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $orderId = $data['order_id'] ?? null;

        if ($orderId) {
            $db = \App\Core\Database::connect();
            $stmt = $db->prepare("INSERT IGNORE INTO cleared_orders (order_id) VALUES (:id)");
            $stmt->execute(['id' => $orderId]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    public function orderHistory()
    {
        \App\Helpers\SessionHelper::requireStaffLogin();

        $db = \App\Core\Database::connect();
        $stmt = $db->prepare("
        SELECT 
            o.order_id,
            o.customer_id,
            o.status,
            o.placed_at,
            o.final_amount,
            c.name AS customer_name,
            co.order_id AS is_cleared
        FROM `order` o
        JOIN customer c ON o.customer_id = c.customer_id
        LEFT JOIN cleared_orders co ON o.order_id = co.order_id
        ORDER BY o.placed_at DESC
    ");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        \App\Core\View::render('staff.order_history', ['orders' => $orders]);
    }

    public function restoreOrder()
    {
        \App\Helpers\SessionHelper::requireStaffLogin();

        $orderId = $_POST['order_id'] ?? null;
        $success = false;

        if ($orderId) {
            try {
                $db = \App\Core\Database::connect();
                $stmt = $db->prepare("DELETE FROM cleared_orders WHERE order_id = :id");
                $success = $stmt->execute([':id' => $orderId]);
            } catch (\PDOException $e) {
                error_log("KitchenController::restoreOrder error: " . $e->getMessage());
                $success = false;
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }


    public function orderDetails()
    {
        $orderId = $_GET['order_id'] ?? null;
        if (!$orderId) {
            exit('Order ID is required');
        }

        $order = \App\Models\OrderModel::getOrderDetails($orderId);
        if (empty($order)) {
            exit('Order not found');
        }

        \App\Core\View::render('staff.order_details', ['order' => $order]);
    }


    // GET: Show Cancel Orders page
    public function cancelOrderPage()
    {
        $db = Database::connect();

        $stmt = $db->query("
            SELECT o.order_id, o.status, o.placed_at, o.final_amount,
                   c.name AS customer_name, c.email AS customer_email
            FROM `order` o
            JOIN customer c ON o.customer_id = c.customer_id
            WHERE o.status IS NULL OR o.status IN ('Received','Preparing','Ready')
        ");
        $orders = $stmt->fetchAll();

        require __DIR__ . '/../views/staff/cancel_order.php';
    }

    // POST: Cancel order via JS fetch()
    public function cancelOrder()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $orderId = $input['order_id'] ?? null;

        if (!$orderId) {
            echo json_encode(['success' => false, 'error' => 'Missing order ID']);
            return;
        }

        $db = Database::connect();
        $stmt = $db->prepare("
        SELECT o.order_id, o.status, c.name AS customer_name, c.email
        FROM `order` o
        JOIN customer c ON o.customer_id = c.customer_id
        WHERE o.order_id = ?
    ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order || empty($order['email'])) {
            echo json_encode(['success' => false, 'error' => 'Order or email not found']);
            return;
        }

        // Update status
        $stmt = $db->prepare("UPDATE `order` SET status = 'Cancelled' WHERE order_id = ?");
        if (!$stmt->execute([$orderId])) {
            echo json_encode(['success' => false, 'error' => 'Failed to update order']);
            return;
        }

        // Send cancellation email
        $config = require __DIR__ . '/../../config/email.php';
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $config['port'];

            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($order['email'], $order['customer_name']);

            $mail->isHTML(true);
            $mail->Subject = "Your Quick Serve Order #{$orderId} has been Cancelled.";
            $mail->Body = $this->buildCancellationTemplate($order);

            $mail->send();

            echo json_encode(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $e) {
            error_log("PHPMailer error: " . $mail->ErrorInfo);
            echo json_encode(['success' => false, 'error' => 'Email failed']);
        }
    }

    private function buildCancellationTemplate($order)
    {
        $customerName = htmlspecialchars($order['customer_name']);
        $orderId = (int)$order['order_id'];

        return "
<html>
<body style='font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;'>
    <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>
        <h2 style='color: #c62828; margin-bottom: 10px;'>Order #{$orderId} Cancelled</h2>
        <p style='font-size: 16px;'>Hi {$customerName},</p>
        <p style='font-size: 16px; color: #c62828; font-weight: bold;'>We regret to inform you that your order has been cancelled.</p>
        <p style='margin-top: 20px;'>If you have any questions, please contact our support team.</p>
        <p style='margin-top: 30px;'>Thank you for choosing <strong>Quick Serve</strong>.</p>
        <p style='font-size: 12px; color: #888; margin-top: 40px;'>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
";
    }

    public function addOrderPage()
    {
        \App\Helpers\SessionHelper::requireStaffLogin();

        // Fetch menu items so staff can select them
        $menuModel = new \App\Models\MenuModel();
        $menuItems = $menuModel->getAvailableItems();

        // Render the Add Order page with menu items
        \App\Core\View::render('staff.add_order_page', [
            'menuItems' => $menuItems
        ]);
    }

    public function findCustomer()
    {
        \App\Helpers\SessionHelper::requireStaffLogin();

        $email = $_GET['email'] ?? '';
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->findByEmail($email);

        header('Content-Type: application/json');
        if ($customer) {
            echo json_encode(['success' => true, 'customer' => $customer]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function placeOrder()
    {
        SessionHelper::requireStaffLogin();

        $customerData = [
            'name'  => $_POST['customer_name'],
            'email' => $_POST['customer_email'],
            'phone' => $_POST['customer_phone'] ?? null
        ];

        $orderData = [
            'comments'     => $_POST['comments'],
            'final_amount' => $_POST['final_amount'],
            'items'        => $_POST['items'] ?? []
        ];

        $orderId = \App\Models\OrderModel::createWithCustomer($customerData, $orderData);

        View::render('staff.order_success', [
            'orderId' => $orderId,
            'customerName' => $customerData['name']
        ]);
    }

 public function receiptPage()
{
   SessionHelper::requireStaffLogin();

    $orderId = $_GET['order_id'] ?? null;
    if (!$orderId) {
        echo "❌ No order ID provided.";
        return;
    }

    $order = OrderModel::getOrderDetails((int)$orderId);

    if (empty($order)) {
        echo "❌ Order not found.";
        return;
    }

    View::render('staff.receipt', [
        'order' => $order
    ]);
}

public function orderSuccessPage()
{
     SessionHelper::requireStaffLogin();
    View::render('staff.order_success');
}

}
