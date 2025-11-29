<?php
namespace App\Controllers;

use App\Models\OrderModel;

class FeedbackController
{
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function protect()
    {
        $this->startSession();
        if (!isset($_SESSION['customer_id'])) {
            header("Location: /quick_serve/login");
            exit;
        }
    }

    /* ============================================================
       SHOW FEEDBACK FORM
    ============================================================ */
    public function feedbackForm()
    {
        $this->protect();

        $customerId = $_SESSION['customer_id'];
        $orderId = $_GET['order_id'] ?? null;

        if (!$orderId) {
            header("Location: /quick_serve/customer/orders");
            exit;
        }

        $order = OrderModel::getOrderById($orderId, $customerId);

        if (!$order) {
            $_SESSION['feedback_error'] = "Order not found.";
            header("Location: /quick_serve/customer/orders");
            exit;
        }

        // Only completed orders can receive feedback
        if ($order['status'] !== 'completed' && $order['status'] !== 'ready') {
            $_SESSION['feedback_error'] = "You can only leave feedback after the order is completed.";
            header("Location: /quick_serve/customer/receipt?order_id=" . $orderId);
            exit;
        }

        require_once __DIR__ . '/../views/customer/feedback.php';
    }

    /* ============================================================
       SUBMIT FEEDBACK
    ============================================================ */
    public function submitFeedback()
    {
        $this->protect();

        $customerId = $_SESSION['customer_id'];
        $orderId = $_POST['order_id'] ?? null;
        $feedback = trim($_POST['feedback_message'] ?? '');

        if (!$orderId || $feedback === '') {
            $_SESSION['feedback_error'] = "Please enter your feedback.";
            header("Location: /quick_serve/customer/feedback?order_id=" . $orderId);
            exit;
        }

        // Ensure order belongs to user
        $order = OrderModel::getOrderById($orderId, $customerId);
        if (!$order) {
            $_SESSION['feedback_error'] = "Order not found.";
            header("Location: /quick_serve/customer/orders");
            exit;
        }

        // Save feedback in order table
        $pdo = \App\Core\Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `order`
            SET feedback = ?
            WHERE order_id = ? AND customer_id = ?
        ");
        $stmt->execute([$feedback, $orderId, $customerId]);

        $_SESSION['feedback_success'] = "Thank you for your feedback!";
        header("Location: /quick_serve/customer/receipt?order_id=" . $orderId);
        exit;
    }
}
