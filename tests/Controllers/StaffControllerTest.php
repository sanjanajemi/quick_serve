<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\StaffController;
use App\Core\Database;

class StaffControllerTest extends TestCase
{
    protected $controller;
    protected $db;

    protected function setUp(): void
    {
        $this->controller = new StaffController();
        $this->db = Database::connect();
    }

    public function testUpdateOrderStatusWithMissingData()
    {
        // Simulate empty input
        $_POST = [];
        ob_start();
        $this->controller->updateOrderStatus();
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertFalse($response['success']);
        $this->assertEquals('Missing data', $response['error']);
    }

    public function testClearOrderWithValidId()
    {
        // Insert a fake order into cleared_orders
        $orderId = 123;
        $stmt = $this->db->prepare("DELETE FROM cleared_orders WHERE order_id = ?");
        $stmt->execute([$orderId]);

        $_POST = ['order_id' => $orderId];
        ob_start();
        $this->controller->clearOrder();
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertTrue($response['success']);

        // Verify DB change
        $stmt = $this->db->prepare("SELECT order_id FROM cleared_orders WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $this->assertEquals($orderId, $stmt->fetchColumn());
    }

    public function testRestoreOrderRemovesFromClearedOrders()
    {
        $orderId = 456;
        $stmt = $this->db->prepare("INSERT IGNORE INTO cleared_orders (order_id) VALUES (?)");
        $stmt->execute([$orderId]);

        $_POST = ['order_id' => $orderId];
        ob_start();
        $this->controller->restoreOrder();
        ob_end_clean();

        // Verify DB change
        $stmt = $this->db->prepare("SELECT order_id FROM cleared_orders WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $this->assertFalse($stmt->fetchColumn());
    }

    public function testPollOrdersReturnsCount()
    {
        ob_start();
        $this->controller->pollOrders();
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertArrayHasKey('order_count', $response);
        $this->assertIsInt($response['order_count']);
    }
}