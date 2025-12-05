<?php
namespace App\Controllers;

//Customer inteface uses this controller

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\MenuModel;
use App\Models\CartModel;

class OrderController
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

    /* 
       ORDER HISTORY (READ)
   */
    public function orderHistory()
    {
        $this->protect();

        $customerId = $_SESSION['customer_id'];
        $orders = OrderModel::getCustomerOrders($customerId);

        require_once __DIR__ . '/../views/customer/order_history.php';
    }

 
      // RECEIPT PAGE (READ)
  
    public function receipt()
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
            $_SESSION['order_error'] = "Order not found.";
            header("Location: /quick_serve/customer/orders");
            exit;
        }

        $items = OrderModel::getItems($orderId);

        require_once __DIR__ . '/../views/customer/receipt.php';
    }

   
      // CANCEL ORDER (UPDATE)

    public function cancel()
    {
        $this->protect();

        $customerId = $_SESSION['customer_id'];
        $orderId = $_POST['order_id'] ?? null;

        if (!$orderId) {
            header("Location: /quick_serve/customer/orders");
            exit;
        }

        OrderModel::cancelOrder($orderId, $customerId);

        $_SESSION['order_success'] = "Order #$orderId cancelled.";
        header("Location: /quick_serve/customer/orders");
        exit;
    }

   
    //   REORDER (CREATE)

   public function reorder()
{
    $this->protect();

    $customerId = $_SESSION['customer_id'];
    $orderId = $_POST['order_id'] ?? null;

    if (!$orderId) {
        header("Location: /quick_serve/customer/orders");
        exit;
    }

    // 1. Load old order items
    $items = OrderModel::getItems($orderId);

    if (empty($items)) {
        $_SESSION['order_error'] = "Could not reorder — no items found.";
        header("Location: /quick_serve/customer/orders");
        exit;
    }

    // 2. Create a new order first
    $newOrderId = OrderModel::createOrder($customerId, 0, null);

    // 3. Add old items to new order
    foreach ($items as $item) {
        OrderModel::addItem(
            $newOrderId,
            $item['menu_item_id'],
            $item['unit_price'],   
            $item['quantity']
        );
    }

    $_SESSION['order_success'] = "Items added to cart!";
    header("Location: /quick_serve/customer/cart");
    exit;
}
}